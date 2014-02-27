# Server Requirements

* PHP 5.4
* MCrypt PHP Extension

We strongly recommend to use Webspace that you can configure. You should be able to create vhosts at least. And there is a command-line interface called Artisan. You should ensure you can run it.

# Get Contentify

Download Contentify from one of the official resource. Visits [contentify.it](http://contentify.it/) to download the CMS.

# Configuration

Config files live in app/config.

* *app.php*: Application settings such as title
* *database.php*: Database settings such as connection data

# Installation

* (Download Contentify)
* Run Composer with `php composer.phar update` to download the Laravel framework and to update dependencies
* Run the installer. Example call: `http://localhost/contentify/install`

The official Laravel docs have a [chapter concering the installation](http://laravel.com/docs/installation).