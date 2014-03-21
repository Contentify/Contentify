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
        $this->buildIndexPage([
            'buttons'       => null,
            'dataSource'    => Module::findAll(),
            'brightenFirst' => false,
            'tableHead'     => [
                t('Title')      => null,
                t('Enabled')    => null,
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
                            t('Install'), 
                            url('admin/modules/'.$module->title.'/install/0'));
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
            $this->message('Error: Module installation failed.');            
        } elseif ($result === true) {
            $this->message('Module installation completed.');
        } else {
            $this->pageOutput($result);
        }
    }

}