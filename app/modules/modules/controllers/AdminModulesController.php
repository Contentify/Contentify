<?php namespace App\Modules\Modules\Controllers;

use App\Modules\Modules\Models\Module as Module;
use HTML, BackController;

class AdminModulesController extends BackController {

    protected $icon = 'package.png';

    public function __construct()
    {
        $this->model = 'Module';

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
            ],
            'tableRow'      => function($module)
            {
                if ($module->enabled()) {
                    $enabled = HTML::image('icons/tick.png');
                } else {
                    $enabled = HTML::image('icons/cross.png');
                }

                return [
                    $module->title,
                    $enabled
                ];            
            },
            'actions'   => [
                'install',
                function($module) {
                    if ($module->installer() !== false) {
                        return image_link('add',
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
        $module = new Module(['title' => $name]);

        $installerFile = $module->installer();

        require_once $installerFile;

        $class      = 'App\modules\\'.$name.'\Installer';
        $installer  = new $class($name, $step);
        $result     = $installer->execute();

        if ($result === false or $result === null) {
            $this->message(trans('modules::fail'));            
        } elseif ($result === true) {
            $this->message(trans('modules::success'));
        } else {
            $this->pageOutput($result);
        }
    }

}