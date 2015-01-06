<?php namespace App\Modules\Users\Controllers;

use ModelHandlerTrait;
use Exception, Response, Sentry, HTML, User, Hover, BackController;

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
            'buttons'   => null,
            'tableHead' => [
                trans('app.id')             => 'id', 
                trans('app.username')       => 'username',
                trans('app.email')          => 'email',
                trans('users::membership')  => null,
                trans('users::banned')      => null,
            ],
            'tableRow'  => function($user)
            {
                if ($user->image) Hover::image(asset('uploads/users/'.$user->image));

                if ($user->hasAccess('internal', PERM_READ)) {
                    $membership = HTML::fontIcon('check');
                } else {
                    $membership = HTML::fontIcon('close');
                }

                if ($user->isBanned()) {
                    $banned = HTML::fontIcon('lock');
                } else {
                    $banned = HTML::fontIcon('unlock');
                }

                return [
                    $user->id,
                    raw(Hover::pull(), $user->username),
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
                        trans('users::edit_profile'), 
                        url('users/'.$user->id.'/edit'));
                }
            ]
        ]);
    }

    public function update($id)
    {
        $user = User::findOrFail($id);

        if (! user()->isSuperAdmin() and $user->isSuperAdmin()) {
            $this->message(trans('app.access_denied'));
            return;
        }

        return $this->traitUpdate($id);
    }

    /**
     * Bans or unbans a user.
     * 
     * @param  int  $id     The ID of the user
     * @param  bool $ban    Ban (true) or unban (false)?
     * @return Response
     */
    public function ban($id, $ban = true)
    {
        $user = User::findOrFail($id);

        if (! $this->checkAccessDelete() or (! user()->isSuperAdmin() and $user->isSuperAdmin())) {
            return Response::make(trans('app.access_denied'), 500);
        }

        try {
            $throttle = Sentry::findThrottlerByUserId($id);

            if ($ban) {
                $throttle->ban();
                return Response::make('1', 200);
            } else {
                $throttle->unBan();
                return Response::make('0', 200);
            }          
        } catch (Exception $e) {
            return Response::make(trans('app.not_found'), 500);
        }
    }
}