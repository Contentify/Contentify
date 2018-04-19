<?php 

namespace App\Modules\Users\Http\Controllers;

use App\Modules\Roles\Role;
use BackController;
use HTML;
use ModelHandlerTrait;
use Redirect;
use URL;
use UserActivities;
use UserActivity;

class AdminActivitiesController extends BackController 
{

    use ModelHandlerTrait {
        update as traitUpdate;
    }

    protected $icon = 'history';

    public function __construct()
    {
        $this->modelClass = UserActivity::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'   => [
                '<a href="'.url('admin/activities/delete/all').'" class="btn btn-default" data-confirm-delete="1">'
                .HTML::fontIcon('trash').' '.trans('app.delete').'</a>'
            ],
            'tableHead' => [
                trans('app.id')             => 'id',
                trans('users::frontend')    => 'frontend', 
                trans('users::model_class') => 'model_class',
                trans('users::activity')    => 'activity_id',
                trans('app.username')       => 'user_id',
                trans('app.date')           => 'created_at',
            ],
            'tableRow'  => function(UserActivity $userActivity)
            {
                if ($userActivity->frontend) {
                    $frontend = HTML::fontIcon('check');
                } else {
                    $frontend = HTML::fontIcon('times');
                }

                $translatedPermissions = Role::getPermissionTranslations();

                return [
                    $userActivity->id,
                    raw($frontend),
                    $userActivity->model_class,
                    $translatedPermissions[$userActivity->activity_id],
                    raw(HTML::link(URL::route('users.show', [$userActivity->user->id]), $userActivity->user->username)),
                    $userActivity->created_at->dateTime(),
                ];
            },
            'searchFor' => 'model_class',
            'actions'   => []
        ]);
    }

    public function deleteAll()
    {
        UserActivities::deleteAll();

        return Redirect::to('admin/activities');
    }

}