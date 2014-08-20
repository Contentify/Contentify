# Server Requirements

* PHP 5.4.0
* MCrypt PHP Extension
* FileInfo PHP Extension

You will also need a MySQL Database and it's recommended to activate Apache's mod_rewrite module (or Nginx' HttpRewriteModule).

We strongly recommend to use Webspace that you can configure. You should be able to create vhosts at least. And there is a command-line interface called [Artisan](http://laravel.com/docs/artisan). You should ensure you can run it. With Artisan you may [enable maintenance mode](http://laravel.com/docs/configuration#maintenance-mode).

# Install Missing PHP Extensions

The first step on your Windows environment is to open `php/php.ini` and to search for the name of the extension like so: `;extension=php_fileinfo.dll` If you find this line remove the semicolon to activate the extension. If you don't find it, you need to download the extension first and then add it to the `php.ini`. Here is a guide for MCrypt: [Link](http://www.myoddweb.com/2010/11/18/install-mcrypt-for-php-on-windows/)

# Get Contentify

Download the Contentify core files from one of the official sources. Visit our website [Contentify.it](http://contentify.it/) to get the files. Store them inside a folder so that only the subfolder "public" is accessible from the web. If only the "public" is accessible from outside all other folders are protected from direct access. When on Apache you may want to [create a
Virtual Host](http://laravel-recipes.com/recipes/25) for your project to achieve this. The aim is to have a URL like `http://localhost/contentify/` instead of `http://localhost/contentify/public/`. For testing or developing purposes it's okay to put the whole CMS folder inside the public web folder. But remember, this is not meant for production stage.

# Configuration

Config files live in `app/config`. Important config files are:

* *app.php*: Application settings such as title and encryption key
* *database.php*: Database settings such as connection setup

# Installation

* (Download Contentify and configurate it)
* Run Composer with `php composer.phar update` to download the Laravel framework and to update dependencies
* Run migrations with `php artisan migrate --package=cartalyst/sentry`
* Run the installer. Example call: `http://localhost/contentify/install`

The official Laravel docs have a [chapter covering the installation](http://laravel.com/docs/installation).

# Something Is Going Wrong?

Installing Laravel can be a little tricky. If you experience problems don't hesistate to contact our [support team](http://contentify.it/support).