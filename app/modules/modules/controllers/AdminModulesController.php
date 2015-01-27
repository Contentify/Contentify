<?php namespace App\Modules\Modules\Controllers;

use App\Modules\Modules\Models\Module;
use Cache, HTML, BackController;

class AdminModulesController extends BackController {

    const CACHE_KEY = 'modules::installation.';

    protected $icon = 'cubes';

    public function __construct()
    {
        $this->modelName = 'Module';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'       => null,
            'dataSource'    => Module::findAll(),
            'brightenFirst' => false,
            'tableHead'     => [
                trans('app.title')          => null,
                trans('modules::enabled')   => null,
                trans('app.state')          => null,
            ],
            'tableRow'      => function($module)
            {
                if ($module->enabled()) {
                    $enabled = HTML::fontIcon('check');
                } else {
                    $enabled = HTML::fontIcon('close');
                }

                /*
                 * Display if the module is installed
                 */
                $state = Cache::get(self::CACHE_KEY.$module->title, null);
                if ($state === true) {
                    $state = trans('app.valid');
                }
                if ($state === false) {
                    $state = trans('app.invalid');
                }

                return [
                    $module->title,
                    raw($enabled),
                    $state,
                ];            
            },
            'actions'   => [
                'install',
                function($module) {
                    if ($module->installer() !== false) {
                        return icon_link('plus-circle',
                            trans('modules::install'), 
                            url('admin/modules/'.$module->title.'/install/0'),
                            false,
                            ['data-confirm' => trans('modules::installation')]);
                    }
                }
            ]
        ]);
    }

    /**
     * Module install method
     * 
     * @param  string  $name Name of the module
     * @param  integer $step Current step, starting at 0
     * @return void
     */
    public function install($name, $step = 0)
    {
        if (! $this->checkAccessCreate()) return;

        $module = new Module(['title' => $name]);

        $installerFile = $module->installer();

        require_once $installerFile;

        $class      = 'App\modules\\'.$name.'\Installer';
        $installer  = new $class($name, $step);
        $result     = $installer->execute();

        if ($result === false or $result === null) {
            Cache::forever(self::CACHE_KEY.$name, false);
            $this->message(trans('modules::fail'));            
        } elseif ($result === true) {
            $installer->after();
            Cache::forever(self::CACHE_KEY.$name, true);
            $this->message(trans('modules::success'));
        } else {
            $this->pageOutput($result);
        }
    }

}