When working with Contentify there are some differences - most of them being enhancements - to vanilla Laravel.

## Ardent

Ardent is a Laravel package that enhances Eloquent. In Contentify, always use Ardent over Eloquent. Below there is an excerpt of the comment model showing what Ardent adds: validation rules and relationships. These rules are the validation rules you already know from Eloquent. relationsData is an array of realationships. In this example an entity called "creator" of type User is added. Ardent handles all of the rest and we can access the creator like this: `$comment->creator->username`

    class Comment extends Ardent {
        //...

        public static $rules = array(
            'text'  => 'required|min:3',
        );

        public static $relationsData = array(
            'creator' => array(self::BELONGS_TO, 'User'),
        );
    }

Read more in the [Ardent docs](https://github.com/laravelbook/ardent).

## Modules

Due to a Laravel package called "Laravel Modules" Contentify has its own kind of packages: [Modules](Modules).

## Surfaces

Contentify includes two surfaces: Frontend and backend. Controllers that are build for the frontend usually inherit from the FrontController class and backend controller from the BackendController class. Names of class (and files) have a prefix to dinstinguish between different surfaces. If a name does not start with a prefix the frontend is the default.

# Sentry

Sentry is a authorization and authentication package. It deals with user registration, authentication (login/logout) and permissions. Read more in the [Sentry manual](https://cartalyst.com/manual/sentry).