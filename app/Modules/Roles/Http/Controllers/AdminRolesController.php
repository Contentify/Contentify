<?php 

namespace App\Modules\Roles\Http\Controllers;

use App\Modules\Roles\Role;
use BackController;
use Hover;
use ModelHandlerTrait;

class AdminRolesController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'lock';

    public function __construct()
    {
        $this->modelClass = Role::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'name'
            ],
            'tableRow' => function(Role $role)
            {
                return [
                    $role->id,
                    raw(Hover::modelAttributes($role, ['creator', 'updated_at'])->pull(), $role->name),
                ];
            },
            'searchFor' => 'name'
        ]);
    }

}
