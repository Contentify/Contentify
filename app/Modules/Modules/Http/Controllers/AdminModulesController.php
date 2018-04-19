<?php

namespace App\Modules\Modules\Http\Controllers;

use App\Modules\Modules\Module;
use BackController;
use Cache;
use HTML;
use ModuleInstaller;

class AdminModulesController extends BackController
{

    const CACHE_KEY = 'modules::installation.';

    protected $icon = 'cubes';

    public function __construct()
    {
        $this->modelClass = Module::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'       => null,
            'dataSource'    => Module::findAll(),
            'brightenFirst' => false,
            'tableHead'     => [
                trans('app.title')     => null,
                trans('app.enabled')   => null,
                trans('app.state')     => null,
            ],
            'tableRow'      => function(Module $module)
            {
                if ($module->enabled()) {
                    $enabled = HTML::fontIcon('check');
                } else {
                    $enabled = HTML::fontIcon('times');
                }

                /*
                 * Display if the module is installed
                 * @TODO Using the cache is not reliable - better store the state in the database
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
                function(Module $module) {
                    if ($module->installer() !== false) {
                        return icon_link('plus-circle',
                            trans('app.install'), 
                            url('admin/modules/'.$module->title.'/install/0'),
                            false,
                            ['data-confirm' => trans('modules::installation')]
                        );
                    }
                }
            ]
        ]);
    }

    /**
     * Module install method
     * 
     * @param  string $name The name of the module
     * @param  int    $step The number of the current step, starting at 0
     * @return void
     */
    public function install($name, $step = 0)
    {
        if (! $this->checkAccessCreate()) {
            return;
        }

        $module = new Module(['title' => $name]);

        $installerFile = $module->installer();

        require_once $installerFile;

        $class = 'App\modules\\'.$name.'\Installer';
        /** @var ModuleInstaller $installer */
        $installer  = new $class($name, $step);
        $result = $installer->execute();

        if ($result === false or $result === null) {
            Cache::forever(self::CACHE_KEY.$name, false);
            $this->alertError(trans('modules::fail'));
        } elseif ($result === true) {
            $installer->after();
            Cache::forever(self::CACHE_KEY.$name, true);
            $this->alertSuccess(trans('modules::success'));
        } else {
            $this->pageOutput($result);
        }
    }

}