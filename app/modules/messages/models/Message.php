<?php namespace App\Modules\Messages\Models;

use BBCode, Cache, User, BaseModel;

class Message extends BaseModel {

    const CACHE_KEY = 'messages::texts.';

    protected $slugable = true;

    protected $fillable = [
        'title', 
        'text', 
        'receiver_id',
    ];

    protected $rules = [
        'title'         => 'required|min:3',
        'text'          => 'required|min:3',
        'receiver_id'   => 'required|integer|min:1',
    ];

    public static $relationsData = [
        'creator'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
        'receiver'      => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    /**
     * When creating a message users can fill in the username.
     * This methods tries to find this user by the given username
     * and to set the receiver_id attribute to the user's id.
     *
     * @param string $username The name of the receiver (user)
     * @return boolean
     */
    public function setReceiverByName($username)
    {
        $user = User::whereUsername($username)->first();

        if ($user) {
            $this->receiver_id = $user->id;

            return true;
        } else {
            return false;
        }
    }

    /**
     * Caches this message - we don't want to parse BBCodes each time
     * we want to display a message.
     * 
     * @return void
     */
    public function cache()
    {
        $escape = ! $this->sent_by_system; // System messages may contain HTML code

        $bbcode = new BBCode();
        $rendered = $bbcode->render($this->text, $escape);

        Cache::put(self::CACHE_KEY.$this->id, $rendered, 60);
    }

    /**
     * Renders the message's text (with BBCode converted to HTML code)
     * 
     * @return string
     */
    public function renderText()
    {
        $key = self::CACHE_KEY.$this->id;

        if (! Cache::has($key)) {
            $this->cache();
        }

        return Cache::get($key);
    }

    /**
     * Return just the plain message's text (WITHOUT BBCode).
     * (Similar to render BBCode without the tags but it uses caching.)
     *
     * @param int $max Limits the number of characters. 0/null = no limit
     * @return string
     */
    public function plainText($max = null)
    {
        $text = strip_tags($this->renderText());

        if ($max) {
            if (strlen($text) > $max) {
                $text = substr($text, 0, $max).'...';
            }
        }

        return $text;
    }

}