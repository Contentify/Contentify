Contentify is a CMS based on the popular Laravel 4 framework. Laravel is developed and maintained by Taylor Otwell and a small [team of developers](http://laravel.com/docs/introduction#development-team). It's one of the most advanced PHP frameworks ever made. Many believe it's the upcoming star of PHP frameworks.

Contentify enhances Laravel. Understanding Laravel is a key requirement to understand Contentify. We believe that it's quite simple to learn the basics of Laravel so this should not be show stopper. We recommend ["Code Bright" by Dayle Rees](http://daylerees.com/codebright/codebright) as a beginner guide. If you run into troubles with Laravel feel free to contact our [support team](http://contentify.it/support) or ask the Laravel community for help. These guys are great and will do their best to help you.

# Why We Choose Laravel

* Clean and simple Syntax
* High code quality
* Based on composer (vendor packages)
* Fast development
* Great community

# Laravel Resources

* [Official Website](http://laravel.com/)
* [Official Docs](http://laravel.com/docs)
* [Official Forums](http://laravel.io/forum)
* [GitHub](https://github.com/laravel/laravel)
* [Best Beginner Guide: Code Bright](http://daylerees.com/codebright/codebright) (also available as book)
* [@LaravelNews](https://twitter.com/laravelnews), [@DayleRees](https://twitter.com/daylerees), [@TaylorOtwell](https://twitter.com/taylorotwell)
* [Screencast](https://laracasts.com/) (liable to pay costs)

# Laravel In 10 Minutes

Learning Laravel within only 10 minutes is impossible - sorry. This is a super-condensed guide to explain some of the basics without digging into details. Use the resources mentioned above to learn more. The official docs are probably your best friend when doing so.

## What Is A Framework?

[Wikipedia says](http://en.wikipedia.org/wiki/Software_framework) "a software framework is a universal, reusable software platform to develop applications, products and solutions". Laravel is like the basement of a house: You build the rest of the house upon it. It has tons of features in his pockets to do the basic work so web developers do not have to waste time with implementing basic stuff. Laravel can handle routing, templating, database access and many more things for us.

## Model-View-Controller

Model-View-Controller is a popular software design pattern. Laravel implements MVC. The idea is seperation of concerns: For example the view-part (HTML templates) is responsible for generating an output representation. Dividing the application into different kinds of components has important advantages. Think about a front-end developer: He or she does not have to deal with PHP code. According to MVC it's possible to avoid writing any PHP code when working with the front-end part, because this only concerns the view. Laravel offers Blade templating with a PHP-less syntax.

* Models: Entities. In PHP code: Classes and objects. Models are stored in a database.
* Views: Handle the output representation, usually of models.
* Controllers: Process user request ("load this page") to a response (the page).

## Composer And Packagist

[Composer](https://getcomposer.org/) is a PHP command line tool and a dependency manager. It installs vendor libraries for an application by guaranteeing that all libraries have their dependent third party libraries. It also supports autoloading. Unfortunately it needs to run a command to rebuild the autoload classmap.

> Whenever a new class is added to the application the autoloading classmap needs an update. This command will start the update: `php composer.phar dumpautoload`

Composer calls these libraries "packages". [Packagist](https://packagist.org/) is the main Composer repository. "It lets you find packages and lets Composer know where to get the code from".

## Requests And Routing

A request arrives to your webserver. How can PHP know what to do with this request? Well, Laravel's routing can handle requests. Routes are stored in a file called "routes.php". They can handle requests of different types, e. g. GET and POST and they decide which part of the application will process the request.

## Responses

Who will process a request? A controller. Controllers handle requests and return responses. For example a user agents requests the URL "/cookies/123". A controller named CookieController handles this particular request. The controller retrieves the cookie with the ID 123 from the database as an object (model). It passes the object to a view and returns this view as a response. Now the user can enjoy a delicous cookie recipe. Responses have a header and a content part. The header contains a status code such as the infamous 404 ("Not Found").

## Database

Laravel has a class named DB to access the database. It can handle raw SQL queries or use the database query builder to create and run database queries. But Laravel has more to offer: Eloquent is the object-relational mapper of Laravel. Eloquent is great when working with models.

## Official Quickstart Guide

There is an [official quickstart guide](http://laravel.com/docs/quick).