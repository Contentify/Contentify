<?php namespace Contentify\Models;

use Cartalyst\Sentry\Users\Eloquent\User as SentryUser;
use Cache, DB, Exception, File, InterImage, Redirect, Input, Validator, Sentry, Session;

class User extends SentryUser {

    const CACHE_KEY_MESSAGES = 'users::messageCounter.';

    protected $fillable = [
        'username', 
        'email', 
        'password',

        'first_name', 
        'last_name',
        'gender',
        'country_id',
        'language_id',
        'birthdate',
        'occupation',
        'website',

        'about',
        'signature',

        'skype',
        'steam_id',

        'cpu',
        'graphics',
        'ram',
        'motherboard',
        'drives',
        'display',
        'mouse',
        'keyboard',
        'headset',
        'mousepad',

        'game',
        'food',
        'drink',
        'music',
        'film',
    ];

    /**
     * Array containing validator messages after validate method was called.
     * @var Illuminate\Support\MessageBag
     */
    private $validatorMessages = null;

    public static function boot()
    {
        parent::boot();
        
        self::updated(function($user)
        {
            /*
             * Update the locale (since its stored in a session variable we need to update it)
             * when changed in profile or use just logged in.
             */
            if (user() and user()->id == $user->id) {
                Session::forget('locale');
            }
        });
    }

    /**
     * Validate the user with Laravel's validator class. Return true if valid.
     * 
     * @return boolean
     */
    public function validate()
    {

        /*
         * Welcome to the dark side of Laravel.
         * We cannot let the User class inherit from BaseModel so we have to use 
         * the Laravel validator class here. Ewwww.
         */
        $validator = Validator::make(
            [
                'username'      => $this->username,
                'email'         => $this->email,
                'gender'        => $this->gender,
                'country_id'    => $this->country_id,
                'language_id'   => $this->language_id,
            ],
            [
                'username'      => "alpha_spaces|required|min:3|not_in:edit,password|unique:users,username,{$this->id},id",
                'email'         => "email|required|unique:users,email,{$this->id},id",
                'gender'        => 'between:0,4',
                'country_id'    => 'exists:countries,id',
                'language_id'   => 'exists:languages,id'
            ]
        );

        $this->validatorMessages = $validator->messages();

        return $validator->passes();
    }

    public function validatorMessages()
    {
        return $this->validatorMessages;
    }

    public function country()
    {
        return $this->belongsTo('App\Modules\Countries\Models\Country');
    }

    public function language()
    {
        return $this->belongsTo('App\Modules\Languages\Models\Language');
    }

    public function teams()
    {
        return $this->belongsToMany('App\Modules\Teams\Models\Team')->withPivot('task', 'description', 'position');
    }

    /**
     * See if a user has access to the passed permission(s).
     * Permissions are merged from all groups the user belongs to
     * and then are checked against the passed permission(s).
     *
     * If multiple permissions are passed, the user must
     * have access to all permissions passed through, unless the
     * "all" flag is set to false.
     *
     * Super users have access no matter what.
     *
     * @param  string|array  $permissions   String of a single permission or array of multiple permissions
     * @param  bool          $level         Desired level
     * @param  bool          $all           Do all permission need to hit the level?
     * @return bool
     */
    public function hasAccess($permissions, $level = 1, $all = true)
    {
        if ($this->isSuperUser())
        {
            return true;
        }

        return $this->hasPermission($permissions, $level, $all);
    }

    /**
     * See if a user has access to the passed permission(s).
     * This overwrites Sentry's lowlevel permission system
     * and adds the level attribute.
     *
     * @param  string|array  $permissions   String of a single permission or array of multiple permissions
     * @param  bool          $level         Desired level
     * @param  bool          $all           Do all permission need to hit the level?
     * @return bool
     */
    public function hasPermission($permissions, $level = 1, $all = true)
    {
        $mergedPermissions = $this->getMergedPermissions(); // Permission the user has

        if ( ! is_array($permissions)) {
            $permissions = (array) $permissions; // Ensure $permissions is an array
        }

        foreach ($permissions as $permission) {
            $matched = false;

            foreach ($mergedPermissions as $mergedPermission => $value) {
                if ($permission == $mergedPermission) {
                    if ($mergedPermissions[$permission] >= $level) { // Compare with desired level
                        if (! $all) {
                            return true;
                        } else {
                            $matched = true;
                        }
                    }
                    break;
                }
            }

            if ($all and ! $matched) {
                return false; // Return false if $all = true and a permission is not given
            }
        }

        if ($all) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Alias for isSuperUser()
     * 
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return $this->isSuperUser();
    }

    /**
     * The throttle system is not part of the Sentry core module.
     * This helper method accesses the banned attribute.
     *
     * @return boolean
     */
    public function isBanned()
    {
        // This is what Sentry gives us to get the banned attribute... Not cool.
        // If there is a better way let us know.
        $throttle = Sentry::findThrottlerByUserId($this->id);

        return $throttle->isBanned();
    }

    /**
     * This is a copy of a BaseModel method (for compatibility).
     * 
     * @return boolean
     */
    public function isSoftDeleting()
    {
        return property_exists($this, 'forceDeleting');
    }

    /**
     * This is a copy of a BaseModel method (for compatibility).
     * 
     * @param  bool $local If true, return a local path (e. g. "C:\Contentify\public/uploads/games/")
     * @return string
     */
    public function uploadPath($local = false)
    {
        $path = '/uploads/users/';

        if ($local) {
            return public_path().$path;
        }
        return url('/').$path;
    }

    /**
     * Tries to upload an image
     * 
     * @param  string $fieldName Name of the form field
     * @return Redirect|null
     */
    public function uploadImage($fieldName)
    {
        $file       = Input::file($fieldName);
        $extension  = $file->getClientOriginalExtension();

        try {
            $imgData = getimagesize($file->getRealPath()); // Try to gather infos about the image
        } catch (Exception $e) {

        }

        if (! isset($imgData[2]) or ! $imgData[2]) {
            return Redirect::route('users.edit', [$this->id])
            ->withInput()->withErrors([trans('app.invalid_image')]);
        }

        $filePath = public_path().'/uploads/users/';

        if (File::exists($filePath.$this->getOriginal($fieldName))) {
            File::delete($filePath.$this->getOriginal($fieldName));
        }

        $fileName           = $this->id.'_'.$fieldName.'.'.$extension;
        $uploadedFile       = $file->move($filePath, $fileName);
        $this->$fieldName   = $fileName;
        $this->save();

        if ($fieldName == 'image') {
            if (File::exists($filePath.'60/'.$this->getOriginal($fieldName))) {
                File::delete($filePath.'60/'.$this->getOriginal($fieldName));
            }

            InterImage::make($filePath.'/'.$fileName)->resize(60, 60, function ($constraint) {
                                        $constraint->aspectRatio();
                                    })->save($filePath.'60/'.$fileName);
        }
    }

    /**
     * Counts the new messages of this user.
     * Uses caching.
     * 
     * @return int
     */
    public function countMessages()
    {
        $key = self::CACHE_KEY_MESSAGES.$this->id;

        if (Cache::has($key)) {

            return Cache::get($key);
        }

        $result = DB::table('messages')->select(DB::raw('COUNT(*) AS count'))->whereReceiverId($this->id)->whereReceiverVisible(true)->
            whereNew(true)->first();

        Cache::forever($key, $result->count);

        return $result->count;
    }

}