When working with Contentify there are some differences - most of them being enhancements - to vanilla Laravel.

## Validation & Relations

Ardent is a Laravel package that enhances Eloquent. It is no longer maintained and does not fully support Laravel 4.2. Therefore we have decided to integrate it into the BaseModel class and to maintain it on our own. In Contentify, always use the BaseModel class over the pure Eloquent class. Below there is an excerpt of the comment model showing what the BaseModel class adds: validation rules and relationships. These rules are the validation rules you already know from Eloquent. relationsData is an array of realationships. In this example an entity called "creator" of type User is added. Ardent handles all of the rest and we can access the creator like this: `$comment->creator->username`

    class Comment extends BaseModel {
        //...

        public static $rules = array(
            'text'  => 'required|min:3',
        );

        public static $relationsData = array(
            'creator' => array(self::BELONGS_TO, 'User'),
        );
    }

Read more in the [Ardent docs](https://github.com/laravelbook/ardent) or the [Validating package docs](https://github.com/dwightwatson/validating).

## Modules

Due to a Laravel package called "Laravel Modules" Contentify has its own kind of packages: [Modules](Modules). Contentify is focused on modules. Some features do not work outside of modules. Therefore extending the CMS usually means extending a module or creating an entire new module.

## User Interfaces

Contentify includes two user interfaces: Frontend and backend. Controllers that are build for the frontend usually inherit from the FrontController class and backend controller from the BackendController class. Names of class (and files) have a prefix to dinstinguish between different interfaces. If a name does not start with a prefix the frontend is the default interface.

# Sentry

Sentry is an authorization and authentication package. It deals with user registration, authentication (login/logout) and permissions. Read more in the [Sentry manual](https://cartalyst.com/manual/sentry).

# Config

The CMS extends Laravel's `Config` class. It overwrites the `has` and `get` methods in a way that they also look up values in a database table called `config`. It uses caching to avoid unnecessary database queries. To store values in this database table, call the `store` method:

    Config::store('key', 'value');

To delete a config value from the database, call `delete`:

    Config::delete('key');

