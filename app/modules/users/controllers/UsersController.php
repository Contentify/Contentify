<?php namespace App\Modules\Users\Controllers;

use User, FrontController;

class UsersController extends FrontController {

    public function __construct()
    {
        $this->model = '\User';

        parent::__construct();
    }

    public function index()
    {
        $this->buildIndexPage([
            'buttons'   => null,
            'tableHead' => [
                t('ID')             => 'id', 
                t('Username')       => 'username',
                t('Name')           => 'first_name',
                t('Registration')   => 'created_at',
                t('Last Login')     => 'last_login',
            ],
            'tableRow' => function($user)
            {
                return [
                    $user->id,
                    link_to('users/'.$user->id, $user->username),
                    $user->first_name.' '.$user->last_name,
                    $user->created_at->toDateString(),
                    $user->last_login->toDateString()
                ];            
            },
            'actions'   => null,
            'searchFor' => 'username'
        ], 'front');
    }

    public function show($id)
    {
        $user = User::whereId($id)->whereActivated(true)->first();

        $user->access_counter++;
        $user->save();

        $this->pageView('users::show', compact('user'));
    }

    public function globalSearch($subject)
    {
        $usersCollection = User::where('username', 'LIKE', '%'.$subject.'%')->get();

        $results = array();
        foreach ($usersCollection as $users) {
            $results[$users->username] = URL::to('users/'.$user->id.'/show');
        }

        return $results;
    }
}