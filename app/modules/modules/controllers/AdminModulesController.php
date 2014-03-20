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
                            url('#'));
                    }
                }
            ]
        ]);
    }

}