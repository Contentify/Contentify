<?php

namespace Contentify;

use HTML;

class Hover {

    /**
     * The content to display
     * @var string
     */
    private $content = null;

    /**
     * Code for an HTML element that contains the content
     * @var string
     */
    private $wrapperTag = '<div class="hover-ui">%%</div>';

    /**
     * Constructor.
     * 
     * @param string $text Text to add
     */
    public function __construct($text = '')
    {
        if ($text) {
            $this->text($text);
        }
    }

    public function __toString()
    {
        return $this->render();
    }

    /**
     * Adds text to the content.
     * 
     * @param string $text The text to add
     * @return Hover
     */
    public function text($text)
    {
        if ($text) {
            $this->content .= $text;
        }
        return $this;
    }

    /**
     * Adds a text line to the content.
     * 
     * @param string $line The text line to add
     * @return Hover
     */
    public function line($line)
    {
        if ($line) {
            $this->content .= '<p>'.$line.'</p>';
        }
        return $this;
    }

    /**
     * Adds an image to the content.
     * 
     * @param string $url        Image URL
     * @param string $alt        Image alt attribute
     * @param array  $attributes Image attributes
     * @return Hover
     */
    public function image($url, $alt = null, $attributes = array())
    {
        if ($url) {
            $this->content .= HTML::image($url, $alt, $attributes);
        }
        return $this;
    }

    /**
     * Adds a heading to the content
     * 
     * @param  string $heading The heading to add
     * @return Hover
     */
    public function heading($heading)
    {
        if ($heading) {
            $this->content .= '<h3>'.$heading.'</h3>';
        }
        return $this;
    }

    /**
     * Adds model attribute values to the content
     * 
     * @param object $model      A model object
     * @param array  $attributes Array of model attribute names
     * @return Hover
     */
    public function modelAttributes($model, $attributes = array())
    {
        foreach ($attributes as $attribute) {
            switch ($attribute) {
                case 'icon':
                    if ($model->icon) {
                        $this->image($model->uploadPath().$model->icon);
                    }
                    break;
                case 'image':
                    if ($model->image) {
                        $this->image($model->uploadPath().$model->image);
                    }
                    break;
                case 'access_counter':
                    $this->line($model->access_counter.'x '.trans('app.accessed'));
                    break;
                case 'creator':
                    if ($model->creator) {
                        $this->line(trans('app.creator').': '.$model->creator->username);
                    }
                    break;
                default:

            }
        }
        return $this;
    }

    /**
     * Clear the content
     * 
     * @return Hover
     */
    public function clear()
    {
        $this->content = null;
        return $this;
    }

    /**
     * Renders the hover UI element.
     * 
     * @return string
     */
    public function render()
    {
        if ($this->content) {
            return str_replace('%%', $this->content, $this->wrapperTag);
        } else {
            return '';
        }
    }

    /**
     * Renders the hover UI element and clears the content.
     * 
     * @return string
     */
    public function pull()
    {
        $output = $this->render();

        $this->clear();

        return $output;
    }

}