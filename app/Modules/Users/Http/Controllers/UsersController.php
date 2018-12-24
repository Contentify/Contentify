<?php 

namespace App\Modules\Users\Http\Controllers;

use Contentify\GlobalSearchInterface;
use FrontController;
use Illuminate\Database\Eloquent\Builder;
use Input;
use Redirect;
use Sentinel;
use User;
use Validator;

class UsersController extends FrontController implements GlobalSearchInterface
{

    public function __construct()
    {
        $this->modelClass = \User::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'   => null,
            'tableHead' => [
                trans('app.id')                     => 'id',  
                trans('app.username')               => 'username',
                trans('app.name')                   => 'first_name',
                trans('app.object_registration')    => 'created_at',
                trans('users::last_login')          => 'last_login',
            ],
            'tableRow' => function(User $user)
            {
                return [
                    $user->id,
                    raw(link_to('users/'.$user->id.'/'.$user->slug, $user->username)),
                    $user->getRealName(),
                    $user->created_at,
                    $user->last_login ? $user->last_login : null
                ];
            },
            'actions'   => null,
            'searchFor' => 'username',
            'permaFilter' => function(Builder $users)
            {
                return $users->where('id', '!=', 1); // Do not keep the daemon user
            }
        ], 'front');
    }

    /**
     * Show a user
     *
     * @param int $id The ID of the user
     * @return void
     */
    public function show($id)
    {
        /** @var User $user */
        $user = User::whereId($id)->firstOrFail();

        if (! $user->isActivated()) {
            $this->alertInfo(trans('app.access_denied'));
            return;
        }

        $user->access_counter++;
        $user->save();

        $this->title($user->username);
        $this->pageView('users::show', compact('user'));
    }

    /**
     * Show a page with a form to edit the user
     *
     * @param int $id The ID of the user
     * @return void
     */
    public function edit($id)
    {
        if ((! user()) or (user()->id != $id and (! $this->hasAccessUpdate()))) {
            $this->alertError(trans('app.access_denied'));
            return;
        }

        /** @var User $user */
        $user = User::findOrFail($id);

        $this->title($user->username);
        $this->pageView('users::form', compact('user'));
    }

    /**
     * Update a user
     *
     * @param int $id The ID of the user
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function update($id)
    {
        if ((! user()) or (user()->id != $id and (! $this->hasAccessUpdate()))) {
            $this->alertError(trans('app.access_denied'));
            return null;
        }

        /** @var User $user */
        $user = User::findOrFail($id);

        $user->fill(Input::all());

        if (! $user->validate()) {
            return Redirect::route('users.edit', [$id])
                ->withInput()->withErrors($user->validatorMessages());
        }

        if (Input::hasFile('image')) {
            $result = $user->uploadImage('image');
            if ($result) {
                return $result;
            }
        } elseif (Input::get('image') == '.') {
            $user->deleteImage('image');
        }

        if (Input::hasFile('avatar')) {
            $result = $user->uploadImage('avatar');
            if ($result) {
                return $result;
            }
        } elseif (Input::get('avatar') == '.') {
            $user->deleteImage('avatar');
        }

        $user->save();

        $this->alertFlash(trans('app.updated', [trans('app.profile')]));
        return Redirect::route('users.edit', [$id]);
    }

    /**
     * Show a page with a form to change the user password
     *
     * @param int $id The ID of the user
     * @return void
     */
    public function editPassword($id)
    {
        if (! $this->checkAuth()) {
            return;
        }

        $user = User::findOrFail($id);

        $this->pageView('users::password', compact('user'));
    }

    /**
     * Change the password of a user (if possible)
     *
     * @param int $id The ID of the user
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function updatePassword($id)
    {
        if ((! user()) or (user()->id != $id)) {
            $this->alertError(trans('app.access_denied'));
            return null;
        }

        /*
         * Validation
         */
        $rules = array('password'  => 'required|min:6|confirmed');

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to("users/{$id}/password")->withErrors($validator);
        }

        /** @var User $user */
        $user = User::findOrFail($id);

        $credentials = array(
            'email'    => $user->email,
            'password' => Input::get('password_current'),
        );

        /*
         * Try to authenticate the user. If it succeeds the
         * "old password" is valid.
         */
        $authenticatedUser = Sentinel::authenticate($credentials, false);

        if (! $authenticatedUser) {
            $this->alertFlash(trans('app.access_denied'));
            return Redirect::to("users/{$id}/password");
        }

        /*
         * Save the new password. Please note that we do not need to
         * crypt the password. Sentinel will do the work.
         */
        Sentinel::update($user, ['password' => Input::get('password')]);

        $this->alertFlash(trans('app.updated', ['Password']));
        return Redirect::to("users/{$id}/edit");

    }
    
    /**
     * This method is called by the global search (SearchController->postCreate()).
     * Its purpose is to return an array with results for a specific search query.
     * 
     * @param  string $subject The search term
     * @return string[]
     */
    public function globalSearch($subject)
    {
        $users = User::where('username', 'LIKE', '%'.$subject.'%')->get();

        $results = array();
        foreach ($users as $user) {
            $results[$user->username] = url('users/'.$user->id.'/'.$user->slug);
        }

        return $results;
    }
}
