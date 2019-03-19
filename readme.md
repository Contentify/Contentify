![Contentify Logo](http://www.contentify.org/img/hero_small.png)

## Contentify CMS - v3.0-alpha

[![Laravel](https://img.shields.io/badge/Laravel-5.5-orange.svg?style=flat-square)](http://laravel.com)
[![Source](http://img.shields.io/badge/source-Contentify/Contentify-blue.svg?style=flat-square)](https://github.com/Contentify/Contentify)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)
[![Gitter](https://img.shields.io/gitter/room/badges/shields.svg?style=flat-square)](https://gitter.im/Contentify)

Contentify is an esports CMS based on the PHP framework Laravel 5.5. Build your gaming website with a modern CMS.

Website: [contentify.org](http://contentify.org/)

### Get the production version

Download it here: [3.0](https://github.com/Contentify/Contentify/releases/tag/v3.0)

To install Contentify please follow the instructions in the [wiki](https://github.com/Contentify/Contentify/wiki/Installation).

### Get the developer version

Clone this repository (`3.0-dev` branch) via git. Via console, switch to the Contentify directory and run `php composer.phar install`. 
Then follow the instructions in the [wiki](https://github.com/Contentify/Contentify/wiki/Installation).

### Update

To update from v2.6 to 3.0:
* Make a backup of your files and your database!
* Clear the cache by running `php artisan cache:clear` via console or deleting all files and folders in 
`storage/framework/cache` and `storage/framework/views`
* Delete these folders in the current Contentify installation: `vendor`, `app`, `contentify`, `resources`
* Download the files for the update and copy & paste them into the Contentify folder. Replace existing files.
* Please ensure that the `public/uploads/news` folder exists. If it does not, please create it.
* If you made changes to the config files in the `config` folder, you have to re-apply them
* Now run the updater script via console with `php <contentify>/public/update.php` or via browser with `http://localhost/public/update.php`.

Changes: [Changelog.md](changelog.md)

### Demo

* URL: [demo.contentify.org](http://demo.contentify.org/)
* Email: `demo@contentify.org`
* Password: `demodemo`

> The server resets (database, uploaded files and cache) twice per hour.

> NOTE: The demo website is running with Contentify 3.0-alpha.

### Support

You can get free support via GitHub's [issue](https://github.com/Contentify/Contentify/issues) section or via [e-mail](mailto:contact@contentify.org). 

### Contribution

Contributions welcome! [Learn more...](CONTRIBUTING.md)
