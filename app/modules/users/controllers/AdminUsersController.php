<?php namespace App\Modules\Users\Controllers;

use HTML, User, Hover, BackController;

class AdminUsersController extends BackController {

    protected $icon = 'user.png';

    public function __construct()
    {
        $this->model = '\User';

        parent::__construct();
    }

    public function index()
    {
        $this->buildIndexPage([
            'tableHead' => [
                t('ID')         => 'id', 
                t('Username')   => 'username',
                t('Email')      => 'email',
                t('Membership') => null,
            ],
            'tableRow' => function($user)
            {
                $hover = new Hover();
                if ($user->image) $hover->image(asset('uploads/users/'.$user->image));

                if ($user->hasAccess('internal', PERM_READ)) {
                    $membership = HTML::image('icons/tick.png');
                } else {
                    $membership = HTML::image('icons/cross.png');
                }

                return [
                    $user->id,
                    $hover.$user->username,
                    $user->email,
                    $membership
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

}