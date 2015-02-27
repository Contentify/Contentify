<?php namespace App\Modules\Users\Controllers;

use Cartalyst\Sentry\Users\WrongPasswordException;
use Str, Validator, Sentry, Redirect, Input, User, FrontController;

class UsersController extends FrontController {

    public function __construct()
    {
        $this->modelName = '\User';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'   => null,
            'tableHead' => [
                trans('app.id')                 => 'id',  
                trans('app.username')           => 'username',
                trans('app.name')               => 'first_name',
                trans('users::registration')    => 'created_at',
                trans('users::last_login')      => 'last_login',
            ],
            'tableRow' => function($user)
            {
                return [
                    $user->id,
                    raw(link_to('users/'.$user->id.'/'.$user->slug, $user->username)),
                    $user->first_name.' '.$user->last_name,
                    $user->created_at,
                    $user->last_login ? $user->last_login->toDateString() : null
                ];            
            },
            'actions'   => null,
            'searchFor' => 'username'
        ], 'front');
    }

    public function show($id)
    {
        $user = User::whereId($id)->whereActivated(true)->firstOrFail();

        $user->access_counter++;
        $user->save();

        $this->pageView('users::show', compact('user'));
    }

    public function edit($id)
    {
        if ((! user()) or (user()->id != $id and (! $this->checkAccessUpdate()))) {
            $this->alertError(trans('app.access_denied'));
            return;
        }

        $user = User::findOrFail($id);

        $this->pageView('users::form', compact('user'));
    }

    public function update($id)
    {
        if ((! user()) or (user()->id != $id and (! $this->checkAccessUpdate()))) {
            $this->alertError(trans('app.access_denied'));
            return;
        }

        $user = User::findOrFail($id);

        $user->fill(Input::all());
        $user->slug = Str::slug($user->username);

        if (! $user->validate()) {
            return Redirect::route('users.edit', [$id])
                ->withInput()->withErrors($user->validatorMessages());
        }

        if (Input::hasFile('image')) {
            $result = $user->uploadImage('image');
            if ($result) return $result;
        }

        if (Input::hasFile('avatar')) {
            $result = $user->uploadImage('avatar');
            if ($result) return $result;
        }

        $user->save();

        $this->alertFlash(trans('app.updated', ['Profile']));
        return Redirect::route('users.edit', [$id]);
    }

    public function editPassword($id)
    {
        if (! $this->checkAuth()) return;

        $user = User::findOrFail($id);

        $this->pageView('users::password', compact('user'));
    }

    public function updatePassword($id)
    {
        if ((! user()) or (user()->id != $id)) {
            $this->alertError(trans('app.access_denied'));
            return;
        }

        /*
         * Validation
         */
        $rules = array('password'  => 'required|min:6|confirmed');

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to("users/{$id}/password")->withErrors($validator);
        }

        $user = User::findOrFail($id);

        try
        {
            $credentials = array(
                'email'    => $user->email,
                'password' => Input::get('password_current'),
            );

            /*
             * Try to authenticate the user. If it succeeds the
             * "old password" is valid.
             */
            Sentry::authenticate($credentials, false);
        }
        catch (WrongPasswordException $e)
        {
            return Redirect::to("users/{$id}/password")->withErrors(['message' => $e->getMessage()]);
        }

        /*
         * Save the new password. Please note that we do not need to
         * crypt the password. The user model inherits from SentryUser and
         * will do the work.
         */
        $user->password = Input::get('password'); 
        $user->save();

        $this->alertFlash(trans('app.updated', ['Password']));
        return Redirect::to("users/{$id}/edit");

    }

    public function globalSearch($subject)
    {
        $users = User::where('username', 'LIKE', '%'.$subject.'%')->get();

        $results = array();
        foreach ($users as $user) {
            $results[$user->username] = URL::to('users/'.$user->id.'/show');
        }

        return $results;
    }
}