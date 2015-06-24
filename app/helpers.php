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
 * @return User|null The User model
 */
function user()
{
    return Sentry::getUser();
}

/**
 * Shortcut that creates a new instance of the Raw class.
 * Methods may use the Raw class to prevent (HTML etc.) code
 * from auto escaping.
 * 
 * @param  string $value The string value
 * @param  string $escape Another value that's going to get auto escaped
 * @return Raw
 */
function raw($value, $escape = null)
{
    return new Raw($value, $escape);
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
 * Returns HTML code of an "icon link" - a link with an icon (and maybe a text).
 * An icon font will be used to render the icon.
 * 
 * @param  string  $icon        The name of the icon
 * @param  string  $url         The link URL
 * @param  string  $title       The link title
 * @param  boolean $showTitle   Show the title text?
 * @param  array   $attributes  Apply these HTML attributes to the link element
 * @return string
 */
function icon_link($icon, $title, $url, $showTitle = false, $attributes = array())
{
    return HTML::iconLink($icon, $title, $url, $showTitle, $attributes);
}

/**
 * Returns HTML code for a button element. It may include an icon element and a title text.
 * 
 * @param  string $title The button title text
 * @param  string $url   The URL the button is targeting at
 * @param  string $icon  The name of the icon. It's rendered by an icon font.
 * @param  array  $options  Apply these HTML attributes to the link element
 * @return string
 */
function button($title, $url, $icon = '', $options = array())
{
    return HTML::button($title, $url, $icon);
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

    if (strpos($imageUrl, '://') === false) {
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
 *
 * @deprecated
 */
function page_class()
{ 
    $pageClass = preg_replace('/\d-/', '', implode( '-', Request::segments() ) ); 
    $pageClass = str_replace('admin-', '', $pageClass);
    return ! empty( $pageClass ) && $pageClass != '-' ? $pageClass : 'homepage'; 
}

/**
 * Returns true if the application is installed
 * 
 * @return bool
 */
function installed()
{
    $path = storage_path('app/.install');

    return ! File::exists($path);
}