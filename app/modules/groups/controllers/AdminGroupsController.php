<?php namespace App\Modules\Groups\Controllers;

use App\Modules\Groups\Models\Group;
use ModelHandlerTrait;
use Hover, BackController;

class AdminGroupsController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'lock';

    public function __construct()
    {
        $this->modelName = 'Group';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'name'
            ],
            'tableRow' => function($group)
            {
                return [
                    $group->id,
                    raw(Hover::modelAttributes($group, ['creator'])->pull(), $group->name),
                ];            
            },
            'searchFor' => 'name'
        ]);
    }

}