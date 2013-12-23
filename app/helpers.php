<?php

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
|
| Beside Laravels very own helpers we may want to create or own.
| This file is the location to store these helper functions.
|
*/

/**
 * Returns HTML code of a "image button" - a link with and image (and maybe a text).
 * If $image has no extension, the extension will be ".png".
 * If $image does not contain a path, the path "icons" will be used.
 * If $image has does not start with "http://" an asset link will be created.
 * @param  string  $image     The link image
 * @param  string  $title     The link title
 * @param  string  $url       The link URL
 * @param  boolean $showTitle Show the title text?
 * @return string
 */
function image_button($image, $title, $url, $showTitle = false)
{
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

    $image = HTML::image($imageUrl, $title);

    if ($showTitle) {
        $titleText = ' '.$title;
    } else {
        $titleText = '';
    }

    // We have to create our Link without HTML::link(), because
    // that method will not allow to use HTML code.
    $link = '<a class="image-button" href="'.$url.'" title="'.$title.'">'.$image.$titleText.'</a>';

    return $link;
}

/**
 * Returns HTML code of an order switcher (asc / desc).
 *
 * @param string $order     Attribute of the entity, e.g. "id"
 * @param string $orderType Current sorting, "asc" or "desc"
 * @return string
 */
function orderSwitcher($order, $orderType)
{
    if ($orderType == 'asc') {
        $orderType  = 'desc';
        $image      = 'theme/bullet_arrow_down.png';
    } else {
        $orderType  = 'asc';
        $image      = 'theme/bullet_arrow_up.png';
    }

    $url = URL::current().'?order='.$order.'&ordertype='.$orderType;
    return('<a class="order-switcher" href="'.$url.'">'.t('Sorting').': <img src="'.asset($image).'" alt="Icon" width="16" height="16" /></a>');
}