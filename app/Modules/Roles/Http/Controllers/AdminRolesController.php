<?php 

namespace App\Modules\Roles\Http\Controllers;

use App\Modules\Roles\Role;
use ModelHandlerTrait;
use Hover, BackController;

class AdminRolesController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'lock';

    public function __construct()
    {
        $this->modelName = 'Role';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'name'
            ],
            'tableRow' => function($role)
            {
                return [
                    $role->id,
                    raw(Hover::modelAttributes($role, ['creator'])->pull(), $role->name),
                ];            
            },
            'searchFor' => 'name'
        ]);
    }

}