<?php

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
|
| Beside Laravels very own helpers we may want to create our own.
| This file is the location to store these helper functions.
|
*/

/**
 * Shortcut. Returns the current user entity or null.
 * 
 * @return Cartalyst\Sentry\Users\Eloquent\User The User entitiy
 */
function user()
{
    return Sentry::getUser();
}

/**
 * Returns HTML code of a "image link" - a link with and image (and maybe a text).
 * If $image has no extension, the extension will be ".png".
 * If $image does not contain a path, the path "icons" will be used.
 * If $image has does not start with "http://" an asset link will be created.
 * 
 * @param  string  $image       The link image
 * @param  string  $title       The link title
 * @param  string  $url         The link URL
 * @param  boolean $showTitle   Show the title text?
 * @param  array   $attributes  Apply these HTML attributes to the link element
 * @return string
 */
function image_link($image, $title, $url, $showTitle = false, $attributes = array())
{
    $imageUrl = get_image_url($image);
    $image = HTML::image($imageUrl, $title);

    if ($showTitle) {
        $titleText = ' '.$title;
    } else {
        $titleText = '';
    }

    /* 
     * We have to create our Link without HTML::link(), because
     * that method will not allow to use HTML code.
     */ 
    $attrs = HTML::attributes($attributes);
    $link = '<a class="image-link" href="'.$url.'" title="'.$title.'" '.$attrs.'>'.$image.$titleText.'</a>';

    return $link;
}

/**
 * Returns HTML code for a button element. It may include an image element and a title text.
 * 
 * @param  string $title The button title text
 * @param  string $url   The URL the button is targeting at
 * @param  string $image The Image (see get_image_url())
 * @return string
 */
function button($title, $url, $image = '')
{
    $imageUrl = get_image_url($image);
    if ($image) {
        $image = HTML::image($imageUrl, $title);    
    }
    
    $button = '<button type="button" onclick="document.location.href=\''.$url.'\'">'.$image.' '.$title.'</button>';

    return $button;
}

/**
 * Pass in an image name, with or without extension, with or without path. Returns the URL.
 * 
 * @param  string $image The image
 * @return string        The URL
 */
function get_image_url($image)
{
    if (! $image) return '';

    $imageUrl = $image;

    if (! str_contains($imageUrl, '/')) {
        $imageUrl = 'icons/'.$imageUrl;
    }

    if (! str_contains($imageUrl, '.')) {
        $imageUrl .= '.png';
    }

    if (! starts_with($imageUrl, 'http://')) {
        $imageUrl = asset($imageUrl);
    }

    return $imageUrl;
}

/**
 * Returns HTML code of an order switcher (asc / desc).
 *
 * @param string $order     Attribute of the entity, e.g. "id"
 * @param string $orderType Current sorting, "asc" or "desc"
 * @return string
 */
function order_switcher($order, $orderType = NULL, $search = NULL)
{
    if ($orderType == 'asc') {
        $orderType  = 'desc';
        $image      = 'theme/bullet_arrow_down.png';
    } else {
        $orderType  = 'asc';
        $image      = 'theme/bullet_arrow_up.png';
    }

    $url = URL::current().'?order='.$order.'&ordertype='.$orderType;
    if ($search) $url .= '&search='.urlencode($search);
    return('<a class="order-switcher" href="'.$url.'">'.trans('app.sorting').': <img src="'.asset($image).'" alt="Sorting" width="16" height="16" /></a>');
}