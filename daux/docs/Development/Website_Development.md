So you are a developer and you just have arrived here. How can you start developing websites with this CMS? We will try to give you an answer on this essential question in this chapter.

## The Base Layout

The `app/views` folder contains all the "global" template files. They are either plain PHP files (\*.php) or [Blade](Blade) templates (*.blade.php). The most important sub directory is called `frontend`and it contains those templates that belong to the frontend. There you will find a file named `layout_main.blade`. That is, you guess it, the main layout of the website. Most likely you want to it.

> When we talk about "frontend" or "backend" we referring to the interface. The frontend interface is what is visible for everyone: visitors, users, etc. The backend is only accessible by administrators (and users with extended permissions).

## Adding Images And More

The `public` directory contains all thoses files that should be accessible from the outside. This is where the CSS files, the JS files, the images and the upload files life. 

* If you want to add images to the website place them into the `public/img` directory. Images that belong to the CMS are located in the `public/theme` directory.
* JS files life in the `public/libs` directory. The main layout includes jQuery, `framework.js` and `frontend.js` by default.
* CSS files are located right in the `public` directory. The main layout includes `base.css` and `frontend.css` out of the box.

Remember that Laravel and Blade provide classes and helper functions to deal with URLs, assets and so on. You should use them instead of hardcoding URLs.

Contentify delivers several [widgets](Widgets). They are handy little helpers that make it a piece of cake to display content. Take a look into the `controllers` directories of the modules to find them.

## Start To Code

Contentify aims to provide as many features as possible out of the box. But it's meant to be a solid basement, not a all-in-one solution suitable for every purpose. If you reach its boundaries - expand them! One of our primary goals is to make customization as convenient as possible. 

Therefore, don't hesistate to create your own widgets or even [modules](Modules). Integrate the CSS framework of your choice, for example Bootstrap. It's easy since the HTML files are prepared for bootstrap. Utilize the power of [Composer](https://getcomposer.org) and [Packagist](https://packagist.org): Choose of thousands of packages that are easy to install since Laravel comes with Composer support. There is more: There are many packages that are developed [especially for Laravel](http://packalyst.com).