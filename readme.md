![Contentify Logo](http://www.contentify.org/img/hero_small.png)

## Contentify CMS - v2.2

[![Laravel](https://img.shields.io/badge/Laravel-5.3-orange.svg?style=flat-square)](http://laravel.com)
[![Source](http://img.shields.io/badge/source-Contentify/Contentify-blue.svg?style=flat-square)](https://github.com/Contentify/Contentify)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)
[![Gitter](https://img.shields.io/gitter/room/badges/shields.svg?style=flat-square)](https://gitter.im/Contentify)

Contentify is an esports CMS based on the popular Laravel 5.3 framework. Build your team website with a modern CMS.

Website: [contentify.org](http://contentify.org/)

### Clone Repository

Clone this repository (`2.2` branch) via git. Via console, switch to the Contentify directory and run `php composer.phar install`. Then follow the instructions in the [wiki](https://github.com/Contentify/Contentify/wiki/Installation).

### Download

Download it here: [2.2](https://github.com/Contentify/Contentify/releases/tag/v2.2)

To install Contentify please follow the instructions in the [wiki](https://github.com/Contentify/Contentify/wiki/Installation).

### Update

> Important: Contentify now requires PHP 5.6.4 or 7.x!

To update from v2.1 to 2.2:
* Make a backup of your files and your database!
* Delete the `vendor` folder in the current Contentify installation
* Clear the cache by running `php artisan cache:clear` via console or deleting all files and folders in 
`storage/framework/cache` and `storage/framework/views`
* Download the files for the update and copy & paste them into the Contentify folder. Replace existing files.
* If you have made changes to the files in the `config` folder, you have to add them again.
* Now run the updater script via console with `php <contentify>/public/update.php` or via browser with `http://localhost/public/update.php`.

If you have created a module for Contentify 2.1 or below please also take a look at the
[guide to upgrade to Larvel 5.3](https://laravel.com/docs/5.3/upgrade#upgrade-5.3.0) since the changes mentioned there
may affect your code. Especially take a look at the changes regarding routing (resource parameters and the 
`Route::controller` method which is no longer available). 
You also have to update the module providers. Compare your providers with those in Contentify's default modules to see the changes.

### Demo

* URL: [demo.contentify.org](http://demo.contentify.org/)
* Email: `demo@contentify.org`
* Password: `demodemo`

> The server resets (database, uploaded files and cache) twice per hour.

> NOTE: The demo website is running with Contentify 2.0 Beta.

### Support

You can get free support via GitHub's [issue](https://github.com/Contentify/Contentify/issues) section or via e-mail. Both Contentify 1.x and 2.x have long term support (LTS) that includes bug fixes. 

Our version strategy is partially dependent on the release strategy of the underlying framework, Laravel. This means we will publish major releases quite often but they might not implement a lot of new features. Unfortunately the Contentify team does not have enough resources to write tools that allow website admins to update their Contentify websites to new major releases. We regret this but this is the only way we can stick close to current Laravel versions and do major changes to the CMS.

### Contribution

Create an issue right here on GitHub whenever you spot a bug. If you have a solution that fixes the bug, create a fork, commit your changes and then create a pull request.

#### PHP Coding style

Contentify follows the [PSR-2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) with these exceptions:

* All PHP files MUST NOT end with a single blank line.
* There CAN be one `use` keyword per declaration. (But we often merge declarations, e. g. `use Crypt, URL, HTML, DB;`)
* The last case segment of a `switch` structure CAN have a `break` keyword. (But usually we omit it.)
* Closures MUST NOT be declared with a space after the `function` keyword.
