So you are a developer and you just have arrived here. How can you start developing websites with this CMS? In this chapter we will try to give an answer to this essential question.

## The Base Layout

The `app/views` folder contains all the "global" template files. They are either plain PHP files (\*.php) or [Blade](Blade) templates (\*.blade.php). The most important sub directory is called `frontend`and it contains those templates that belong to the frontend. There you will find a file named `layout_main.blade`. This is, you guess it, the main layout of the website. Most likely you want to adjust it to the needs (primarily the design and layout) of your website.

> When we talk about "frontend" or "backend" we are referring to interfaces. The frontend interface is what is visible and accessible for everyone: visitors, users, etc. The backend interface is only accessible by administrators.

## Adding Images And More

The `/public` directory contains all thoses files that should be accessible from the outside. This is where the CSS files, the JS files, the images and the upload files life. 

* If you want to add images to the website place them into the `public/img` directory. Images that belong to the CMS are located in the `/public/theme` directory.
* JS files life in the `/public/libs` directory. The main layout includes jQuery, `framework.js` and `frontend.js` by default.
* CSS files are located in the `/public/css` directory. The main layout includes `base.css` and `frontend.css` out of the box. `base.css` defines some general styling and is also included by the backend stylesheet, `backend.css`.
* We use [LESS](http://lesscss.org) as CSS pre-compiler. The LESS files are located in `/less`. You need a LESS compiler to compile LESS files to CSS files. If you do not want to use LESS you may directly edit the CSS files.

Remember that Laravel and Blade provide classes (such as `Form` and `HTML`) and helper functions (such as `url` and `asset()`) to deal with form elements, URLs, assets and so on. You should use them instead of hardcoding HTML form markup and URLs.

Contentify delivers several [widgets](Widgets). They are handy little helpers that make it a piece of cake to display certain content. Take a look into the `controllers` directories of the modules to find them. For example, the forums widget - that shows the latest forum threads - lifes in `app/modules/forums/controllers/LatestThreadsWidgets.php`.

## Start To Code

Contentify aims to provide as many features as possible out of the box. But it's meant to be a solid basement, not a all-in-one solution suitable for every purpose. If you reach its boundaries - expand them! One of our primary goals is to make customization as convenient as possible. 

Therefore, don't hesistate to create your own widgets or even [modules](Modules). Integrate the CSS framework of your choice, for example Bootstrap. It's easy since the HTML files are prepared for Bootstrap. Utilize the power of [Composer](https://getcomposer.org) and [Packagist](https://packagist.org): Choose of thousands of packages that are easy to install since Laravel comes with Composer support. There is even more: Enjoy hundrets of packages that are developed [especially for Laravel](http://packalyst.com).

## Extending the CMS

### Overwriting Module Routes

Routes that are defined in the `app/routes.php` file overwrite routes of modules (or genereally spoken they overwrite routes of packages) if they share the same route path. So let's imagine you want to modify the `getIndex()` method of the `App\Modules\Contact\ContactController` to pass additional data to the contact form view. Instead of editing the method itself create a new route in `app/routes.php` to overwrite the original route:

    Route::get('contact', function()
    {
        // do something
    });

Well, it's not always that simple. Even in this example we face a challenge: We don't have access to controller methods. Since we are not inside a controller context we can't call `$this->pageView()` in the way the original method does. We have to create a new controller that extends the `BaseController` or even the `ContactController` class and implement the method there:

    // app/route.php:
    Route::get('contact', 'MyContactController@getIndex');

    // app/controllers/MyContactController.php:
    <?php

    class MyContactController extends App\Modules\Contact\ContactController {

        public function getIndex()
        {
            $extraData = array('exampleValue' => 123);
            $this->pageView('contact::form', $extraData);
        }

    }