<?php

/*
|--------------------------------------------------------------------------
| View Composers and View Creators
|--------------------------------------------------------------------------
|
| This is the right place to setup view composers and view creators 
| that do not belong to modules.
|
*/

View::composer('backend', function($view)
{ 
    // User profile picture
    if (Sentry::getUser()->image) {
        $userImage = 'uploads/users/thumbnails/'.Sentry::getUser()->image;
    } else {
        $userImage = 'theme/user.png';
    }
    $view->with('userImage', $userImage);

    // Contact messages
    $view->with('contactMessages', '[contactMessages]');
});