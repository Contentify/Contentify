<?php 

namespace App\Modules\Users\Http\Controllers;

use ModelHandlerTrait;
use Exception, Input, Response, Redirect, Str, Activation, Sentinel, HTML, User, Hover, BackController;

class AdminUsersController extends BackController 
{

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
                /** @var \App\Modules\Users\User $user */
                if ($user->image) Hover::image(asset('uploads/users/'.$user->image));

                if ($user->hasAccess('internal', PERM_READ)) {
                    $membership = HTML::fontIcon('check');
                } else {
                    $membership = HTML::fontIcon('times');
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
                    return icon_link('edit', trans('app.edit_profile'), url('users/'.$user->id.'/edit')).' ';
                },
                function($user) {
                    return icon_link(
                        'trash',
                        trans('app.delete'),
                        url('admin/users/'.$user->id).'?method=DELETE&_token='.csrf_token()
                    );
                },
            ]
        ]);
    }

    public function update($id)
    {
        $user = User::findOrFail($id);
        
        /*
         * Ensure that admins are not able to promote themselves to superadmins
         */
        if (! user()->isSuperAdmin()) { 
            $roleIds = Input::get('_relation_roles');

            foreach ($roleIds as $roleId) {
                // $roleId may be an empty string
                if ($roleId) {
                    $role = Sentinel::findRoleById($roleId);

                    if ($role->permissions['superadmin'] == '1') {
                        $this->alertError(trans('app.access_denied'));
                        return null;
                    }
                }
            }
        }

        return $this->traitUpdate($id);
    }

    /**
     * This method does not delete a user but deactivates the account and removes profile information
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        // This call also checks for permissions
        $this->activate($id, false);

        $user = User::findOrFail($id);

        // Do not allow to disable your own account
        if (user()->id == $id) {
            return Response::make(trans('app.access_denied'), 403);
        }

        $user->deleteImage('image');
        $user->deleteImage('avatar');

        $ignoreFillables = ['email', 'username', 'country_id', 'language_id'];
        $fillables = $user->getFillable();
        foreach ($fillables as $fillable) {
            if (! in_array($fillable, $ignoreFillables))
                $user->{$fillable} = null;
        }

        $user->banned = true;
        $user->password = Str::random();
        $user->cup_points = 0;
        $user->steam_auth_id = null;

        $user->save();

        $this->alertFlash(trans('app.successful'));
        return Redirect::to('admin/users');
    }

    /**
     * Activates or deactivates a user.
     *
     * @param  int  $id       The ID of the user
     * @param  bool $activate Activate (true) or deactivate (false)?
     * @return \Illuminate\Http\Response
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
        } catch (Exception $exception) {
            return Response::make(trans('app.not_found'), 500);
        }
    }
}