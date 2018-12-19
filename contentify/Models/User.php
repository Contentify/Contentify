<?php

namespace Contentify\Models;

use Activation;
use App\Modules\Friends\Friendship;
use App\Modules\Messages\Message;
use BBCode;
use Cache;
use Carbon;
use Cartalyst\Sentinel\Users\EloquentUser as SentinelUser;
use Cartalyst\Sentinel\Users\UserInterface;
use Contentify\ModelHandler;
use Contentify\Traits\SlugTrait;
use DB;
use Exception;
use File;
use Illuminate\Database\Query\Builder;
use Input;
use InterImage;
use Redirect;
use Sentinel;
use Session;
use Validator;

/**
 * @property int                             $id
 * @property string                          $username
 * @property string                          $slug
 * @property string                          $email
 * @property string                          $password
 * @property string                          $first_name
 * @property string                          $last_name
 * @property int                             $gender
 * @property int                             $country_id
 * @property int                             $language_id
 * @property string                          $birthdate
 * @property string                          $occupation
 * @property string                          $website
 * @property string                          $about
 * @property string                          $signature
 * @property string                          $discord
 * @property string                          $skype
 * @property string                          $facebook
 * @property string                          $twitter
 * @property string                          $steam_id
 * @property string                          $cpu
 * @property string                          $graphics
 * @property string                          $ram
 * @property string                          $motherboard
 * @property string                          $drives
 * @property string                          $display
 * @property string                          $mouse
 * @property string                          $keyboard
 * @property string                          $headset
 * @property string                          $mouse_pad
 * @property string                          $game
 * @property string                          $food
 * @property string                          $drink
 * @property string                          $music
 * @property string                          $film
 * @property string                          $steam_auth_id
 * @property string                          $image
 * @property string                          $avatar
 * @property int                             $cup_points
 * @property int                             $posts_count
 * @property bool                            $banned
 * @property int                             $access_counter
 * @property \Carbon                         $created_at
 * @property \Carbon                         $updated_at
 * @property \Carbon                         $last_active
 * @property \Carbon                         $last_login
 * @property \App\Modules\Teams\Team[]       $teams
 * @property \App\Modules\Countries\Country  $country
 * @property \App\Modules\Languages\Language $language
 * @property \User[]                         $friends
 *
 * @method bool hasAccess($permissions, $level = 1)
 * @method bool hasAnyAccess($permissions, $level = 1)
 */
class User extends SentinelUser implements UserInterface
{

    use SlugTrait;

    /**
     * Decides how long a user is considered being online. Unit: seconds (= 5 minutes)
     */
    const ONLINE_TIME = 300;

    /**
     * Name of the cache key for the user message counter
     */
    const CACHE_KEY_MESSAGES = 'users::messageCounter.';

    /**
     * Name of the cache key for the user (forum) signature
     */
    const CACHE_KEY_SIGNATURE = 'users::signature.';

    /**
     * True if model is slugable. Usage in SlugTrait.
     *
     * @var bool
     */
    protected $slugable = true;

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

        'discord',
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
        'mouse_pad',

        'game',
        'food',
        'drink',
        'music',
        'film',
    ];

    /**
     * Array containing validator messages after validate method was called.
     *
     * @var \Illuminate\Support\MessageBag
     */
    private $validatorMessages = null;

    public static function boot()
    {
        parent::boot();

        self::saving(function(User $user)
        {
           if ($user->slug === null) {
               $user->createSlug(true, 'username');
           }
        });
        
        self::updated(function(User $user)
        {
            if (user() and user()->id == $user->id) {
                /*
                 * Update the locale (since it is stored in a session variable we need to update it)
                 * when changed in profile or user just logged in.
                 */
                Session::forget('app.locale');

                /*
                 * Forget the cached (forum) signature so it has to be rebuilt with the new signature
                 */
                if ($user->isDirty('signature')) {
                    Cache::forget(self::CACHE_KEY_SIGNATURE.$user->id);
                }
            }
        });
    }

    /**
     * Get the attributes that should be converted to dates.
     *
     * @return string[]
     */
    public function getDates()
    {
        return array_merge(parent::getDates(), array('activated_at', 'last_login', 'last_active'));
    }

    /**
     * Validate the user with Laravel's validator class. Return true if valid.
     * 
     * @return bool
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
     * ATTENTION: This is not a relationship. Eloquent does not support this kind of relationship.
     *
     * @return \Illuminate\Database\Eloquent\Collection
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
     * @param  int $friendId The user ID of the friend (=other user)
     * @return bool
     */
    public function isFriendWith($friendId)
    {
        if (array_key_exists('friends', $this->getRelations())) {
            foreach ($this->friends as $friend) {
                if ($friend->id == $friendId) {
                    return true;
                }
            }
            return false;
        }

        $friendship = Friendship::areFriends($this->id, $friendId)->first();

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
                $creatorId = 1; // This is the daemon user
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
        $message->sent_by_system    = $sentBySystem;
        $message->createSlug();

        $message->save();
    }

    /**
     * Creates a new system message that addresses this user
     * 
     * @param  string   $title        The title of the message
     * @param  string   $text         The text of the message
     * @param  int      $creatorId    The user ID of the creator
     * @return void
     */
    public function sendSystemMessage($title, $text, $creatorId = null)
    {
        $this->sendMessage($title, $text, $creatorId, true);
    }
    
    /**
     * Returns the formatted real name of the current user
     *
     * @return string
     */
    public function getRealName()
    {
        return trim($this->first_name.' '.$this->last_name);
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
     * The throttle system is not part of the Sentinel core module.
     * This helper method accesses the activated attribute.
     *
     * @return boolean
     */
    public function isActivated()
    {
        return Activation::completed($this);
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
     * @param  string $fieldName The name of the form field
     * @return null|\Illuminate\Http\RedirectResponse
     */
    public function uploadImage($fieldName)
    {
        $file       = Input::file($fieldName);
        $extension  = $file->getClientOriginalExtension();

        try {
            $imgData = getimagesize($file->getRealPath()); // Try to gather info about the image
        } catch (Exception $e) {
            // Do nothing
        }

        if (! in_array(strtolower($extension), ModelHandler::ALLOWED_IMG_EXTENSIONS)) {
            return Redirect::route('users.edit', [$this->id])
            ->withInput()->withErrors([trans('app.invalid_image')]);
        }

        // Check if image has a size. If not, it's not an image. Does not work for SVGs.
        if (strtolower($extension) !== 'svg' and (! isset($imgData[2]) or ! $imgData[2])) {
            return Redirect::route('users.edit', [$this->id])
                ->withInput()->withErrors([trans('app.invalid_image')]);
        }

        $filePath = public_path().'/uploads/users/';

        if (File::exists($filePath.$this->getOriginal($fieldName))) {
            File::delete($filePath.$this->getOriginal($fieldName));
        }

        $filename           = $this->id.'_'.$fieldName.'.'.$extension;
        $uploadedFile       = $file->move($filePath, $filename);
        $this->$fieldName   = $filename;
        $this->save();

        if ($fieldName == 'image') {
            if (File::exists($filePath.'80/'.$this->getOriginal($fieldName))) {
                File::delete($filePath.'80/'.$this->getOriginal($fieldName));
            }

            InterImage::make($filePath.'/'.$filename)->resize(80, 80, function ($constraint) {
                /** @var \Intervention\Image\Constraint $constraint */
                $constraint->aspectRatio();
            })->save($filePath.'80/'.$filename);
        }
    }

    /**
     * Deletes a user image.
     * 
     * @param  string $fieldName The name of the form field
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

        $result = DB::table('messages')->select(DB::raw('COUNT(*) AS count'))->whereReceiverId($this->id)->
            whereReceiverVisible(true)->whereNew(true)->first();

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
     * @param Builder $query The Eloquent Builder object
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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function findAllUsersWithAccess($permissions, $level = 1) {
        if (! is_array($permissions)) {
            $permissions = (array) $permissions;
        }

        $users = Sentinel::getUserRepository()->get()->filter(function (self $user) use ($permissions, $level) {
            return $user->hasAccess($permissions, $level);
        });

        return $users;
    }

    /**
     * If called the first time for the current user,
     * renders and returns the (forum) signature of the current user.
     * If called again, takes the rendered signature from the cache.
     *
     * @return string
     */
    public function renderSignature()
    {
        $cacheKey = self::CACHE_KEY_SIGNATURE.$this->id;
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        } else {
            $bbcode = new BBCode();

            $rendered = $bbcode->render($this->signature);
            $rendered = emojis($rendered);

            Cache::forever($cacheKey, $rendered);

            return $rendered;
        }
    }

}
