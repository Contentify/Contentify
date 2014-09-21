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
 * Shortcut. Returns the current user model or null.
 * 
 * 
 * @return User The User model
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
    return HTML::imageLink($image, $title, $url, $showTitle, $attributes);
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
    return HTML::button($title, $url, $image);
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
 * Returns HTML code for a sort switcher (asc / desc).
 *
 * @param string $sortby    Attribute of the entity, e.g. "id"
 * @param string $order     Current sorting order, "asc" or "desc"
 * @return string
 */
function sort_switcher($sortby, $order = null, $search = null)
{
    return HTML::sortSwitcher($sortby, $order, $search);
}

/**
 * Returns HTML code for a button enabling/disabling recycle bin mode
 * (to restore soft deleted entities).
 * 
 * @return string
 */
function recycle_bin_button()
{
    return HTML::recycleBinButton();
}

/**
 * Helper that returns the name of a class representing the current page.
 * See for more: http://laravelsnippets.com/snippets/add-unique-body-id-helper
 * 
 * @return string
 */
function pageClass()
{ 
    $pageClass = preg_replace('/\d-/', '', implode( '-', Request::segments() ) ); 
    $pageClass = str_replace('admin-', '', $pageClass);
    return ! empty( $pageClass ) && $pageClass != '-' ? $pageClass : 'homepage'; 
}