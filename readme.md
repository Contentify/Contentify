![Contentify Logo](http://www.contentify.org/share/img/logo_180.png)

## Contentify CMS

[![Laravel](https://img.shields.io/badge/Laravel-5.1-orange.svg?style=flat-square)](http://laravel.com)
[![Source](http://img.shields.io/badge/source-Contentify/Contentify-blue.svg?style=flat-square)](https://github.com/Contentify/Contentify)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

Contentify is an eSports CMS based on the popular Laravel 5.1 framework.

Website: [contentify.org](http://contentify.org/)

> WARNING: This is a beta version. 

> WARNING: This repository does include multiple branches, but currently none of them is a release branch. Therefore, if you are interested in testing, please download a curated realease that is meant for testing.

### Releases

The current release is `Beta Three`.

You may download it here: [contentify.org/share/releases/contentify_beta3.zip](http://contentify.org/share/releases/contentify_beta3.zip)

To install Contentify please follow the instructions in the [wiki](https://github.com/Contentify/Contentify/wiki/Installation).

### Demo

* URL: http://46.101.143.161/
* Email: `demo@contentify.org`
* Password: `demodemo`

> The server resets (database, uploaded files and cache) twice per hour.

> Please note that the server does not have the latest build and therefore does not represent the git master branch!

### Contribution

Create an issue right here on Github whenever you spot a bug. If you have a solution that fixes the bug, create a fork, commit your changes and then create a pull request.

#### PHP Coding style

Contentify follows the [PSR-2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) with these exceptions:

* All PHP files MUST NOT end with a single blank line.
* There CAN be one `use` keyword per declaration. (But we often merge declarations, e. g. `use Crypt, URL, HTML, DB;`)
* The last case segment of a `switch` structure CAN have a `break` keyword. (But usually we omit it.)
* Closures MUST NOT be declared with a space after the `function` keyword.