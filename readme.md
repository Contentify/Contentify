![Contentify Logo](http://www.contentify.org/img/hero_small.png)

## Contentify CMS

[![Laravel](https://img.shields.io/badge/Laravel-5.1-orange.svg?style=flat-square)](http://laravel.com)
[![Source](http://img.shields.io/badge/source-Contentify/Contentify-blue.svg?style=flat-square)](https://github.com/Contentify/Contentify)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

Contentify is an eSports CMS based on the popular Laravel 5.1 framework. Build your team website with a modern CMS.

Website: [contentify.org](http://contentify.org/)

### Clone Repository

Clone this repository (`master` branch) via git. Via console, switch to the Contentify directory and run `php composer.phar install`. Then follow the instructions in the [wiki](https://github.com/Contentify/Contentify/wiki/Installation).

### Download

Download it here: [contentify.org/share/releases/contentify_1_0_0.zip](http://contentify.org/share/releases/contentify_1_0_0.zip)

To install Contentify please follow the instructions in the [wiki](https://github.com/Contentify/Contentify/wiki/Installation).

### Demo

* URL: [demo.contentify.org](http://demo.contentify.org/)
* Email: `demo@contentify.org`
* Password: `demodemo`

> The server resets (database, uploaded files and cache) twice per hour.

You may download the demo website project here: [contentify.org/share/contentify_demo.zip](http://www.contentify.org/share/contentify_demo.zip)

### Contribution

Create an issue right here on GitHub whenever you spot a bug. If you have a solution that fixes the bug, create a fork, commit your changes and then create a pull request.

#### PHP Coding style

Contentify follows the [PSR-2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) with these exceptions:

* All PHP files MUST NOT end with a single blank line.
* There CAN be one `use` keyword per declaration. (But we often merge declarations, e. g. `use Crypt, URL, HTML, DB;`)
* The last case segment of a `switch` structure CAN have a `break` keyword. (But usually we omit it.)
* Closures MUST NOT be declared with a space after the `function` keyword.
