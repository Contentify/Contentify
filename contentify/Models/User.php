<?php namespace Contentify\Models;

use Cartalyst\Sentinel\Users\EloquentUser as SentinelUser;
use App\Modules\Messages\Message;
use App\Modules\Friends\Friendship;
use Carbon, Cache, DB, Exception, File, InterImage, Redirect, Input, Validator, Sentinel, Session;

class User extends SentinelUser {

    /**
     * Decides how loing a user is considered being online. Unit: Seconds (= 5 Minutes)
     */
    const ONLINE_TIME = 300;

    /**
     * Name of the cache key for the user message counter
     */
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
        'facebook',
        'twitter',
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
                Session::forget('app.locale');
            }
        });
    }

    /**
     * Get the attributes that should be converted to dates.
     *
     * @return array
     */
    public function getDates()
    {
        return array_merge(parent::getDates(), array('activated_at', 'last_login', 'last_active'));
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
                'signature'     => $this->signature,
            ],
            [
                'username'      => "alpha_numeric_spaces|required|min:3|not_in:edit,password|unique:users,username,{$this->id},id",
                'email'         => "email|required|unique:users,email,{$this->id},id",
                'gender'        => 'between:0,4',
                'country_id'    => 'exists:countries,id',
                'language_id'   => 'exists:languages,id',
                'signature'     => 'max:255', // Warning: Don't make this too big!
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
        return $this->belongsTo('App\Modules\Countries\Country');
    }

    public function language()
    {
        return $this->belongsTo('App\Modules\Languages\Language');
    }

    public function teams()
    {
        return $this->belongsToMany('App\Modules\Teams\Team')->withPivot('task', 'description', 'position');
    }

    /**
     * Returns all friends of this user.
     * NOTE: This is not a relationship. Eloquent does not support this kind of relationship.
     *
     * @return Collection
     */
    public function friends()
    {
        // Step 1: Receive all friendships of this user.
        $friendships = Friendship::friendsOf($this->id)->get();

        // Step 2: Extract the IDs of all friends
        $ids = [];
        foreach ($friendships as $friendship) {
            if ($friendship->sender_id == $this->id) {
                $ids[] = $friendship->receiver_id;
            } else {
                $ids[] = $friendship->sender_id;
            }
        }

        // Step 3: Receive the user models of these friends
        return self::whereIn('id', $ids)->get();
    }

    /**
     * Checks if the user is friend with another user or not.
     * 
     * @param  int      $friendID The user ID of the friend (=other user)
     * @return boolean
     */
    public function isFriendWith($friendID)
    {
        if (array_key_exists('friends', $this->getRelations())) {
            foreach ($this->friends as $friend) {
                if ($friend->id == $friendId) {
                    return true;
                }
            }
            return false;
        }

        $friendship = Friendship::areFriends($this->id, $friendID)->first();

        return ($friendship !== null);
    }

    /**
     * Creates a new message that addresses this user
     * 
     * @param  string   $title        The message title
     * @param  string   $text         The message text
     * @param  int      $creatorId    The user ID of the creator
     * @param  boolean  $sentBySystem [description]
     * @return void
     */
    public function sendMessage($title, $text, $creatorId = null, $sentBySystem = false)
    {
        if (! $creatorId) {
            if (user()) {
                $creatorId = user()->id;
            } else {
                $creatorId = 1; // Daemon
                $sentBySystem = true;
            }
        }

        $message = new Message([
            'title'             => $title,
            'text'              => $text,
        ]);

        $message->creator_id        = $creatorId;
        $message->updater_id        = $creatorId;
        $message->receiver_id       = $this->id;
        $message->sent_by_system    = true;
        $message->createSlug();

        $message->save();
    }

    /**
     * Creates a new system message that addresses this user
     * 
     * @param  string   $title        The message title
     * @param  string   $text         The message text
     * @param  int      $creatorId    The user ID of the creator
     * @return void
     */
    public function sendSystemMessage($title, $text, $creatorId = null)
    {
        $this->sendMessage($title, $text, $creatorId, true);
    }

    /**
     * Returns true if the current user is a super admin
     * 
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return $this->hasAccess('superadmin');
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
            if (File::exists($filePath.'80/'.$this->getOriginal($fieldName))) {
                File::delete($filePath.'80/'.$this->getOriginal($fieldName));
            }

            InterImage::make($filePath.'/'.$fileName)->resize(80, 80, function ($constraint) {
                                        $constraint->aspectRatio();
                                    })->save($filePath.'80/'.$fileName);
        }
    }

    /**
     * Deletes a user image.
     * 
     * @param  string $fieldName Name of the form field
     * @return void
     */
    public function deleteImage($fieldName)
    {
        $filePath = public_path().'/uploads/users/';

        if (File::exists($filePath.$this->getOriginal($fieldName))) {
            File::delete($filePath.$this->getOriginal($fieldName));
        }

        if (File::exists($filePath.'80/'.$this->getOriginal($fieldName))) {
            File::delete($filePath.'80/'.$this->getOriginal($fieldName));
        }

        $this->$fieldName = '';
        $this->save();
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

    /**
     * Decides either this user is online or offline
     * 
     * @return boolean
     */
    public function isOnline()
    {
        if ($this->last_active == null) {
            return false;
        }

        return (time() - $this->last_active->timestamp <= self::ONLINE_TIME);
    }

    /**
     * Scope a query to only include users who are online.
     *
     * @return Builder
     */
    public function scopeOnline($query)
    {
        $dateTime = new Carbon();
        $dateTime->subSeconds(self::ONLINE_TIME);
        return $query->where('last_active', '>=', $dateTime);
    }

    /**
     * Returns all users with access to the passed right(s)
     * 
     * @param  string|array $permissions The permission(s)
     * @param  int          $level       The level of the permission(s)
     * @return Collection
     */
    public function findAllUsersWithAccess($permissions, $level = 1) {
        if ( ! is_array($permissions)) {
            $permissions = (array) $permissions;
        }

        $users = Sentinel::getUserRepository()->get()->filter(function ($user) use ($permissions) {
            return $user->hasAccess($permissions, $level);
        });

        return $users;
    }

}