<?php namespace Contentify;

use URL;

/**
 * Open Graph protocol officials docs: http://ogp.me/
 */
class OpenGraph {

    /**
     * Array representing the tags
     * @var array
     */
    protected $tags;

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
    public function hasTag($name)
    {
        foreach ($this->tags as $tag) {
            if ($tag['name'] == $name) return true;
        }        

        return false;
    }

    /**
     * Adds the title property
     * 
     * @param  string $title
     * @return OpenGraph
     */
    public function title($title)
    {
        $this->tags[] = ['name' => 'title', 'value' => strip_tags($title)];

        return $this;
    }

    /**
     * Adds the type property.
     * 
     * @param  string $type
     * @return OpenGraph
     */
    public function type($type)
    {
        $this->tags[] = ['name' => 'type', 'value' => $type];

        return $this;
    }

    /**
     * Adds the image property.
     * If the URL is relative its converted to an absolute one.
     * 
     * @param  string $imageFile The URL of the image file
     * @return OpenGraph
     */
    public function image($imageFile)
    {
        if (strpos($imageFile, '://') === false) {
            $imageFile = asset($imageFile);
        }

        $this->tags[] = ['name' => 'image', 'value' => $imageFile];

        return $this;
    }

    /**
     * Adds the description property
     * 
     * @param  string $description
     * @return OpenGraph
     */
    public function description($description)
    {
        $description = trim(strip_tags(substr($description, 0, 250)));

        $this->tags[] = ['name' => 'description', 'value' => $description];

        return $this;
    }

    /**
     * Adds the URL property
     * 
     * @param  string $url
     * @return OpenGraph
     */
    public function url($url = null)
    {
        if (! $url) $url = URL::current();

        $this->tags[] = ['name' => 'url', 'value' => $url];

        return $this;
    }

    /**
     * Adds the locale property
     * 
     * @param  string $locale
     * @return OpenGraph
     */
    public function locale($locale = null)
    {
        if (! $locale) $locale = Config::get('app.locale');

        $this->tags[] = ['name' => 'locale', 'value' => $locale];

        return $this;
    }

    /**
     * Adds the site_name property
     * 
     * @param  string $siteName
     * @return OpenGraph
     */
    public function siteName($siteName = null)
    {
        if (! $siteName) $siteName = Config::get('app.title');

        $this->tags[] = ['name' => 'site_name', 'value' => $siteName];

        return $this;
    }

    /**
     * Adds the audio property
     * If the URL is relative its converted to an absolute one.
     * 
     * @param  string $video The URL of the video file
     * @return OpenGraph
     */
    public function audio($audioFile)
    {
        if (strpos($audioFile, '://') === false) {
            $audioFile = asset($audioFile);
        }

        $this->tags[] = ['name' => 'audio', 'value' => $audioFile];

        return $this;
    }

    /**
     * Adds the video property
     * If the URL is relative its converted to an absolute one.
     * 
     * @param  string $videoFile The URL of the video file
     * @return OpenGraph
     */
    public function video($videoFile)
    {
        if (strpos($videoFile, '://') === false) {
            $videoFile = asset($videoFile);
        }

        $this->tags[] = ['name' => 'video', 'value' => $videoFile];

        return $this;
    }

    /**
     * Returns the rendered HTML Open Graph tags
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

}