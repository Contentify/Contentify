<?php namespace App\Modules\Groups\Controllers;

use App\Modules\Groups\Models\Group as Group;
use Hover, BackController;

class AdminGroupsController extends BackController {

    protected $icon = 'lock.png';

	public function __construct()
	{
		$this->model = 'Group';

		parent::__construct();
	}

    public function index()
    {
        $this->buildIndexForm([
            'tableHead' => [
                t('ID')     => 'id', 
                t('Title')  => 'name'
            ],
            'tableRow' => function($group)
            {
                $hover = new Hover();
                $hover->image($group->permissions);

                return [
                    $group->id,
                    $hover.$group->name,
                ];            
            },
            'searchFor' => 'name'
        ]);
    }

}