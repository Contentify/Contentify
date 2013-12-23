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
        $userImage = asset('uploads/users/thumbnails/'.Sentry::getUser()->image);
    } else {
        $userImage = asset('theme/user.png');
    }
    $view->with('userImage', $userImage);

    // Contact messages
    $count = DB::table('contact_messages')->where('new', true)->count();
    if ($count > 0) {
        $contactMessages = link_to('admin/contact', $count.t(' new messages'));
    } else {
        $contactMessages = 'No new messages.';
    }
    $view->with('contactMessages', $contactMessages);   
});