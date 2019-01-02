![Contentify Logo](http://www.contentify.org/img/hero_small.png)

## Contentify CMS - v2.6

[![Laravel](https://img.shields.io/badge/Laravel-5.4-orange.svg?style=flat-square)](http://laravel.com)
[![Source](http://img.shields.io/badge/source-Contentify/Contentify-blue.svg?style=flat-square)](https://github.com/Contentify/Contentify)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)
[![Gitter](https://img.shields.io/gitter/room/badges/shields.svg?style=flat-square)](https://gitter.im/Contentify)

Contentify is an esports CMS based on the PHP framework Laravel 5.4. Build your gaming website with a modern CMS.

Website: [contentify.org](http://contentify.org/)

### Get the production version

Download it here: [2.6](https://github.com/Contentify/Contentify/releases/tag/v2.6)

To install Contentify please follow the instructions in the [wiki](https://github.com/Contentify/Contentify/wiki/Installation).

### Get the developer version

Clone this repository (`2.6` branch) via git. Via console, switch to the Contentify directory and run `php composer.phar install`. 
Then follow the instructions in the [wiki](https://github.com/Contentify/Contentify/wiki/Installation).

### Update

To update from v2.5 to 2.6:
* Make a backup of your files and your database!
* Clear the cache by running `php artisan cache:clear` via console or deleting all files and folders in 
`storage/framework/cache` and `storage/framework/views`
* Delete these folders in the current Contentify installation: `vendor`, `app`, `contentify`, `resources`
* Download the files for the update and copy & paste them into the Contentify folder. Replace existing files.
* If you made changes to the config files in the `config` folder, you have to re-apply them
* Now run the updater script via console with `php <contentify>/public/update.php` or via browser with `http://localhost/public/update.php`.

Changes: [Changelog.md](changelog.md)

### Demo

* URL: [demo.contentify.org](http://demo.contentify.org/)
* Email: `demo@contentify.org`
* Password: `demodemo`

> The server resets (database, uploaded files and cache) twice per hour.

> NOTE: The demo website is running with Contentify 2.0 Beta.

### Support

You can get free support via GitHub's [issue](https://github.com/Contentify/Contentify/issues) section or via [e-mail](mailto:contact@contentify.org). 

### Contribution

Contributions welcome! Create an issue right here on GitHub whenever you spot a bug. 
If you have a solution that fixes the bug, create a fork, commit your changes and then create a pull request.
Especially do not hesitate to create a pull request for new features.

#### PHP Coding style

Contentify follows the [PSR-2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) 
with these exceptions:

* All PHP files MUST NOT end with a single blank line.
* The last case segment of a `switch` structure CAN have a `break` keyword. (But usually, we omit it.)
* Closures MUST NOT be declared with a space after the `function` keyword.
