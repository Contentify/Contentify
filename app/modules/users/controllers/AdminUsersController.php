<?php namespace App\Modules\Users\Controllers;

use Exception, Response, Sentry, HTML, User, Hover, BackController;

class AdminUsersController extends BackController {

    protected $icon = 'user.png';

    public function __construct()
    {
        $this->model = 'User';

        parent::__construct();
    }

    public function index()
    {
        $this->buildIndexPage([
            'buttons'   => null,
            'tableHead' => [
                t('ID')         => 'id', 
                t('Username')   => 'username',
                t('Email')      => 'email',
                t('Membership') => null,
                t('Banned')     => null,
            ],
            'tableRow'  => function($user)
            {
                $hover = new Hover();
                if ($user->image) $hover->image(asset('uploads/users/'.$user->image));

                if ($user->hasAccess('internal', PERM_READ)) {
                    $membership = HTML::image('icons/tick.png');
                } else {
                    $membership = HTML::image('icons/cross.png');
                }

                if ($user->isBanned()) {
                    $banned = HTML::image('icons/lock_delete.png');
                } else {
                    $banned = HTML::image('icons/lock_open.png');
                }

                return [
                    $user->id,
                    $hover.$user->username,
                    $user->email,
                    $membership,
                    $banned
                ];            
            },
            'searchFor' => 'username',
            'actions'   => [
                'edit',
                function($user) {
                    return image_link('user_edit', 
                        t('Edit profile'), 
                        url('users/'.$user->id.'/edit'));
                }
            ]
        ]);
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
        if (! $this->checkAccessUpdate()) return Response::make(trans('app.access_denied'), 500);

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