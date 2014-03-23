<?php namespace Contentify;

use Exception, URL;

/**
 * Open Graph protocol officials docs: http://ogp.me/
 */
class OpenGraph {

    /**
     * Array containing the tags
     * @var array
     */
    protected $tags;

    /**
     * Enables validation. A violation of the standard will throw an exception.
     * @var boolean
     */
    protected $validate;

    /**
     * Constructor call
     * 
     * @param boolean $validate Enable validation?
     */
    public function __construct($validate = false)
    {
        $this->tags     = array();
        $this->validate = $validate;
    }

    /**
     * Getter for the validation mode.
     * 
     * @return bool
     */
    public function valid()
    {
        return $this->validate;
    }

    /**
     * Setter for the validation mode.
     *
     * @param  bool  $strict
     * @return array
     */
    public function validate($validate = true)
    {
        $this->validate = $validate;

        return $this;
    }

    /**
     * Getter for the tags.
     * 
     * @return array
     */
    public function tags()
    {
        return $this->tags;
    }

    /**
     * True if at least one tag with the given name exists.
     * It's possible that a tag has multiple values.
     * 
     * @param  string  $name
     * @return boolean
     */
    public function has($name)
    {
        foreach ($this->tags as $tag) {
            if ($tag['name'] == $name) return true;
        }

        return false;
    }

    /**
     * Remove all tags with the given name
     * 
     * @param  string  $name
     */
    public function forget($name)
    {
        foreach ($this->tags as $key => $tag) {
            if ($tag['name'] == $name) unset($this->tags[$key]);
        }

        return $this;
    }

    /**
     * Adds a custom tag to the list of tags
     * 
     * @param string $name
     * @param string $value
     */
    public function tag($name, $value)
    {
        $this->tags[] = compact('name', 'value');

        return $this;
    }

    /**
     * Adds attribute tags to the list of tags
     * 
     * @param string    $tagName    The name of the base tag
     * @param array     $attributes Array with attributes (pairs of name and value)
     * @return void
     */
    public function attributes($tagName, $attributes = array(), $valid = array())
    {
        foreach ($attributes as $name => $value) {
            if ($this->validate and sizeof($valid) > 0) {
                if (! in_array($name, $valid)) {
                    throw new Exception("Open Graph: Invalid attribute '{$name}' (unknown type)");
                }
            }

            $this->tags[] = ['name' => $tagName.':'.$name, 'value' => $value];
        }

        return $this;
    }

    /**
     * Adds a title tag
     * 
     * @param  string $title
     * @return OpenGraph
     */
    public function title($title)
    {
        $title = trim($title);

        if ($this->validate and ! $title) {
            throw new Exception("Open Graph: Invalid title (empty)");
        }

        $this->forget('title');

        $this->tags[] = ['name' => 'title', 'value' => strip_tags($title)];

        return $this;
    }

    /**
     * Adds a type tag.
     * 
     * @param  string $type
     * @return OpenGraph
     */
    public function type($type)
    {
        $types = [
            'music.song',
            'music.album',
            'music.playlist',
            'music.radio_station',
            'video.movie',
            'video.episode',
            'video.tv_show',
            'video.other',
            'article',
            'book',
            'profile',
            'website',
        ];

        if ($this->validate and ! in_array($type, $types)) {
            throw new Exception("Open Graph: Invalid type '{$type}' (unknown type)");
        }

        $this->forget('type');

        $this->tags[] = ['name' => 'type', 'value' => $type];

        return $this;
    }

    /**
     * Adds an image tag.
     * If the URL is relative its converted to an absolute one.
     * 
     * @param  string $imageFile    The URL of the image file
     * @param  array  $attributes   Array with additional attributes (pairs of name and value)
     * @return OpenGraph
     */
    public function image($imageFile, $attributes = null)
    {
        if ($this->validate and ! $imageFile) {
            throw new Exception("Open Graph: Invalid image URL (empty)");
        }

        if (strpos($imageFile, '://') === false) {
            $imageFile = asset($imageFile);
        }

        if ($this->validate and ! filter_var($imageFile, FILTER_VALIDATE_URL)) {
            throw new Exception("Open Graph: Invalid image URL '{$imageFile}'");
        }

        $this->tags[] = ['name' => 'image', 'value' => $imageFile];

        if ($attributes) {
            $valid = [
                'secure_url',
                'type',
                'width',
                'height',
            ];

            $this->attributes('image', $attributes, $valid);
        }

        return $this;
    }

    /**
     * Adds a description tag
     * 
     * @param  string   $description The description text
     * @param  int      $description If the text is longer than this it is shortened
     * @return OpenGraph
     */
    public function description($description, $maxLength = 250)
    {
        $description = trim(strip_tags($description));

        $length = strlen($description);

        $description = substr($description, 0, $maxLength);

        if (strlen($description) < $length) $description .= '...';

        $this->forget('description');

        $this->tags[] = ['name' => 'description', 'value' => $description];

        return $this;
    }

    /**
     * Adds a URL tag
     * 
     * @param  string $url
     * @return OpenGraph
     */
    public function url($url = null)
    {
        if (! $url) $url = URL::current();

        if ($this->validate and ! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception("Open Graph: Invalid URL '{$url}'");
        }

        $this->forget('url');

        $this->tags[] = ['name' => 'url', 'value' => $url];

        return $this;
    }

    /**
     * Adds a locale tag
     * 
     * @param  string $locale
     * @return OpenGraph
     */
    public function locale($locale)
    {
        if ($this->validate and ! $locale) {
            throw new Exception("Open Graph: Invalid locale (none set)");
        }

        $this->forget('locale');

        $this->tags[] = ['name' => 'locale', 'value' => $locale];

        return $this;
    }

    /**
     * Adds locale:alternate tags
     * 
     * @param  string $locales An array of alternative locales
     * @return OpenGraph
     */
    public function localeAlternate($locales = array())
    {
        foreach ($locales as $key => $locale) {
            if ($this->validate and ! $locale) {
                throw new Exception("Open Graph: Invalid locale (item key: {$key})");
            }

            $this->tags[] = ['name' => 'locale:alternate', 'value' => $locale];
        }

        return $this;
    }

    /**
     * Adds a site_name tag
     * 
     * @param  string $siteName
     * @return OpenGraph
     */
    public function siteName($siteName)
    {
        if ($this->validate and ! $siteName) {
            throw new Exception("Open Graph: Invalid site_name (empty)");
        }

        $this->forget('site_name');

        $this->tags[] = ['name' => 'site_name', 'value' => $siteName];

        return $this;
    }

    /**
     * Adds a determiner tag.
     * 
     * @param  string $locale
     * @return OpenGraph
     */
    public function determiner($determiner = '')
    {
        $enum = [
            'a', 
            'an', 
            'the',
            'auto',
            ''
        ];

        if ($this->validate and ! in_array($determiner, $enum)) {
            throw new Exception("Open Graph: Invalid determiner '{$determiner}' (unkown value)");
        }

        $this->tags[] = ['name' => 'determiner', 'value' => $determiner];

        return $this;
    }

    /**
     * Adds an audio tag
     * If the URL is relative its converted to an absolute one.
     * 
     * @param  string $video The URL of the video file
     * @param  array  $attributes   Array with additional attributes (pairs of name and value)
     * @return OpenGraph
     */
    public function audio($audioFile, $attributes = null)
    {
        if ($this->validate and ! $audioFile) {
            throw new Exception("Open Graph: Invalid audio URL (empty)");
        }

        if (strpos($audioFile, '://') === false) {
            $audioFile = asset($audioFile);
        }

        if ($this->validate and ! filter_var($audioFile, FILTER_VALIDATE_URL)) {
            throw new Exception("Open Graph: Invalid audio URL '{$audioFile}'");
        }

        $this->tags[] = ['name' => 'audio', 'value' => $audioFile];

        if ($attributes) {
            $valid = [
                'secure_url',
                'type',
            ];

            $tag = $this->lastTag('type');

            $specialValid = array();

            if ($tag and $tag == 'music.song') {
                $specialValid = [
                    'duration',
                    'album',
                    'album:disc',
                    'album:track',
                    'musician',
                ];
            }

            if ($tag and $tag == 'music.album') {
                $specialValid = [
                    'song',
                    'song:disc',
                    'song:track',
                    'musician',
                    'release_date',
                ];
            }

            if ($tag and $tag == 'music.playlist') {
                $specialValid = [
                    'song',
                    'song:disc',
                    'song:track',
                    'creator',
                ];
            }

            if ($tag and $tag == 'music.radio_station') {
                $specialValid = [
                    'creator',
                ];
            }

            $valid = array_merge($valid, $specialValid);

            $this->attributes('audio', $attributes, $valid);
        }

        return $this;
    }

    /**
     * Adds a video tag
     * If the URL is relative its converted to an absolute one.
     * 
     * @param  string $videoFile The URL of the video file
     * @param  array  $attributes   Array with additional attributes (pairs of name and value)
     * @return OpenGraph
     */
    public function video($videoFile, $attributes = null)
    {
        if ($this->validate and ! $videoFile) {
            throw new Exception("Open Graph: Invalid video URL (empty)");
        }

        if (strpos($videoFile, '://') === false) {
            $videoFile = asset($videoFile);
        }

        if ($this->validate and ! filter_var($videoFile, FILTER_VALIDATE_URL)) {
            throw new Exception("Open Graph: Invalid video URL '{$videoFile}'");
        }

        $this->tags[] = ['name' => 'video', 'value' => $videoFile];

        if ($attributes) {
            $valid = [
                'secure_url',
                'type',
                'width',
                'height',
            ];

            $tag = $this->lastTag('type');
            if ($tag and starts_with($tag['value'], 'video.')) {
                $specialValid = [
                    'actor',
                    'role',
                    'director',
                    'writer',
                    'duration',
                    'release_date',
                    'tag',
                ];

                if ($tag['value'] == 'video.episode') {
                    $specialValid[] = 'video:series';
                }

                $valid = array_merge($valid, $specialValid);
            }

            $this->attributes('video', $attributes, $valid);
        }

        return $this;
    }

    /**
     * Adds article attributes
     * 
     * @param  array  $attributes   Array with attributes (pairs of name and value)
     * @return OpenGraph
     */
    public function article($attributes = array())
    {
        $tag = $this->lastTag('type');
        if (! $tag or $tag['value'] != 'article') {
            throw new Exception("Open Graph: Type has to be 'article' to add article attributes");
        }

        $valid = [
            'published_time',
            'modified_time',
            'expiration_time',
            'author',
            'section',
            'tag',
        ];

        $this->attributes('article', $attributes, $valid);

        return $this;
    }

    /**
     * Adds book attributes
     * 
     * @param  array  $attributes   Array with attributes (pairs of name and value)
     * @return OpenGraph
     */
    public function book($attributes = array())
    {
        $tag = $this->lastTag('type');
        if (! $tag or $tag['value'] != 'book') {
            throw new Exception("Open Graph: Type has to be 'book' to add book attributes");
        }

        $valid = [
            'author',
            'isbn',
            'release_date',
            'tag',
        ];

        $this->attributes('book', $attributes);

        return $this;
    }

    /**
     * Adds profile attributes
     * 
     * @param  array  $attributes   Array with attributes (pairs of name and value)
     * @return OpenGraph
     */
    public function profile($attributes = array())
    {
        $tag = $this->lastTag('type');
        if (! $tag or $tag['value'] != 'book') {
            throw new Exception("Open Graph: Type has to be 'profile' to add profile attributes");
        }

        $valid = [
            'first_name',
            'last_name',
            'username',
            'gender',
        ];

        $this->attributes('profile', $attributes);

        return $this;
    }

    /**
     * Returns the Open Graph tags rendered as HTML
     * 
     * @return string
     */
    public function renderTags()
    {
        $output = '';
        foreach ($this->tags as $tag) {
            $output .= '<meta property="og:'.$tag['name'].'" content="'.$tag['value'].'" />'."\n";
        }

        return $output;
    }

    /**
     * Returns the last tag in the lists of tags with matching name
     * 
     * @param  string       $name The name of the tag
     * @return array|null   Returns the tag (array with name and value) or null
     */
    protected function lastTag($name)
    {
        foreach ($this->tags as $tag) {
            if ($tag['name'] == $name) return $tag;
        }

        return null;
    }

}