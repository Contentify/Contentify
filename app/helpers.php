<?php

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
|
| Beside Laravel's very own helpers we may want to create our own.
| This file is the location to store these helper functions.
|
*/

/**
 * Shortcut. Returns the current user model or null.
 * 
 * @return \Contentify\Models\User|null
 */
function user()
{
    // Note: The getUser() method returns a \Contentify\Models\User object
    // which implements the \Cartalyst\Sentinel\Users\UserInterface
    return Sentinel::getUser();
}

/**
 * Returns true, if HTTPS/SSL enforcement is active, false otherwise
 *
 * @deprecated This function is no longer used by Contentify. It's likely to be removed.
 *
 * @return bool
 */
function enforce_https()
{
    return Config::get('app.https');
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
 * @param  string  $image      The link image
 * @param  string  $title      The link title
 * @param  string  $url        The link URL
 * @param  boolean $showTitle  Show the title text?
 * @param  array   $attributes Apply these HTML attributes to the link element
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
 * @param  string  $icon       The name of the icon
 * @param  string  $url        The link URL
 * @param  string  $title      The link title
 * @param  boolean $showTitle  Show the title text?
 * @param  array   $attributes Apply these HTML attributes to the link element
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
 * @return string
 */
function button($title, $url, $icon = '')
{
    return HTML::button($title, $url, $icon);
}

/**
 * Pass in an image name, with or without extension, with or without path. Returns the URL.
 *
 * @deprecated This function is deprecated and will be removed in the future
 * 
 * @param  string $image The image
 * @return string        The URL
 */
function get_image_url($image)
{
    if (! $image) {
        return '';
    }

    $imageUrl = $image;

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
 * @param string $sortBy Attribute of the entity, e.g. "id"
 * @param string $order  Current sorting order, "asc" or "desc"
 * @param null   $search
 * @return string
 */
function sort_switcher($sortBy, $order = null, $search = null)
{
    return HTML::sortSwitcher($sortBy, $order, $search);
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
 * Replaces ASCII-Codes in a text with HTML-Codes for emoji icons.
 * We should cache the outcome.
 * 
 * @param  string $text
 * @return string
 */
function emojis($text)
{
    $emojis = [
        '&lt;/3'    => 'Heart', // </3
        '&lt;3'     => 'Heart Broken', // <3
        ':)'        => 'Smile',
        ':-)'       => 'Smile',
        ':D'        => 'Yell',
        '=)'        => 'Big Smile',
        ':*('       => 'Cry',
        ':('        => 'Sad',
        ':-('       => 'Sad',
        ':O'        => 'Surprise',
        ':p'        => 'Tongue',
        ';)'        => 'Wink',
        'xD'        => 'Hilarious',
        '&gt;.&lt;' => 'Afraid', // >.<
        ':|'        => 'Neutral',
        ':x'        => 'Speechless',
    ];

    foreach ($emojis as $short => $long) {
        $image  = strtolower(str_replace(' ', '_', $long));
        $path   = asset('img/default/emojis/'.$image.'.png');
        $text   = str_replace($short, '<img class="emoji" src="'.$path.'" title="'.$long.'" alt="'.$long.'">', $text);
    }

    return $text;
}


/**
 * Loads an image file and encodes it with base 64.
 * Returns the result as a string with meta information.
 * Use this function to load images that are not stored in the "public" folder.
 *
 * @param string $filename The file name, including path and extension
 * @return string
 */
function load_image_encoded($filename)
{
    $type = pathinfo($filename, PATHINFO_EXTENSION);
    $data = file_get_contents($filename);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    return $base64;
}

/**
 * Tries to translate a module/model/controller name.
 * 
 * @param  string $name   The name of the module/model/controller
 * @param  string $module The name of the module
 * @return string 
 */
function trans_object($name, $module = null)
{
    $translator = app('translator');

    $name       = Str::snake($name);
    $moduleKey  = $module.'::object_'.$name;
    $appKey     = 'app.object_'.$name;

    if ($module and $translator->has($moduleKey)) {
        return trans($moduleKey);
    }

    if ($translator->has($appKey)) {
        return trans($appKey);
    }

    return $name;
}

/**
 * Returns true if the application is installed
 * 
 * @return bool
 */
function installed()
{
    $path = storage_path('app/.installed');

    return File::exists($path);
}
