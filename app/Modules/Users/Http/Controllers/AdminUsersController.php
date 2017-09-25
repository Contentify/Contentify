<?php namespace App\Modules\Users\Http\Controllers;

use ModelHandlerTrait;
use Exception, Input, Response, Activation, Sentinel, HTML, User, Hover, BackController;

class AdminUsersController extends BackController {

    use ModelHandlerTrait {
        update as traitUpdate;
    }

    protected $icon = 'user';

    public function __construct()
    {
        $this->modelName = 'User';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'   => ['<a href="'.url('admin/activities').'" class="btn btn-default">'.HTML::fontIcon('history')
                .' Activities</a>'],
            'tableHead' => [
                trans('app.id')             => 'id', 
                trans('app.username')       => 'username',
                trans('app.email')          => 'email',
                trans('users::membership')  => null,
                trans('users::banned')      => 'banned',
            ],
            'tableRow'  => function($user)
            {
                if ($user->image) Hover::image(asset('uploads/users/'.$user->image));

                if ($user->hasAccess('internal', PERM_READ)) {
                    $membership = HTML::fontIcon('check');
                } else {
                    $membership = HTML::fontIcon('close');
                }

                if ($user->banned) {
                    $banned = HTML::fontIcon('lock');
                } else {
                    $banned = HTML::fontIcon('unlock');
                }

                return [
                    $user->id,
                    raw(Hover::pull().HTML::link('users/'.$user->id.'/'.$user->slug, $user->username)),
                    $user->email,
                    raw($membership),
                    raw($banned),
                ];            
            },
            'searchFor' => 'username',
            'actions'   => [
                'edit',
                function($user) {
                    return icon_link('user', 
                        trans('app.edit_profile'), 
                        url('users/'.$user->id.'/edit'));
                }
            ]
        ]);
    }

    public function update($id)
    {
        $user = User::findOrFail($id);
        
        /*
         * Ensure that "Admins" are not able to promote themselves to "Superadmins"
         */
        if (! user()->isSuperAdmin()) { 
            $roleIds = Input::get('_relation_roles');

            foreach ($roleIds as $roleId) {
                // $roleId may be an empty string
                if ($roleId) {
                    $role = Sentinel::findRoleById($roleId);

                    if ($role->permissions['superadmin'] == '1') {
                        $this->alertError(trans('app.access_denied'));
                        return;
                    }
                }                
            }
        }

        return $this->traitUpdate($id);
    }

    /**
     * Activate or deactivates a user.
     *
     * @param  int  $id       The ID of the user
     * @param  bool $activate Activate (true) or deactivate (false)?
     * @return Response
     */
    public function activate($id, $activate = true)
    {
        $user = User::findOrFail($id);

        if (! $this->checkAccessDelete() or (! user()->isSuperAdmin() and $user->isSuperAdmin())) {
            return Response::make(trans('app.access_denied'), 403);
        }

        try {           
            if ($activate) {
                $activation = Activation::exists($user);

                if (! $activation) {
                     $activation = Activation::create($user);
                }

                $completed = Activation::complete($user, $activation->code);
                return Response::make('1', 200);
            } else {
                Activation::remove($user);
                return Response::make('0', 200);
            }          
        } catch (Exception $e) {
            return Response::make(trans('app.not_found'), 500);
        }
    }
}