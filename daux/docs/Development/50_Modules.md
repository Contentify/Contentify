Modules are Laravel packages with some sugar. If you prefer packages there is no reason why you shouldn't use them over modules. But modules have advantages too. They are easier to create.

## Module Files

Modules live in their own folders in `app/modules`. Example folder structure:

    controllers/
        AdminGamesController.php
        GamesController.php
    models/
        Game.php
    views/
        admin_form.blade.php
    modules.json
    routes.php

Module folders usually have at least three subfolders: controllers, models and views. Yep, these are inherited from Laravel packages. They are not mandatory. You can add your own subfolders if you like to. 

PHP files that handle backend stuff should have a "admin" prefix.

 `modules.json` is a config file containing the configuration of the module. Minimum example file:

    {
        "enabled": true
    }

The `routes.php` file is a some kind of a clone of `app/routes.php`. But since this is a module Contentify has a helper class called ModuleRoute. It creates some shortcuts to routing functions. Example file:

    ModuleRoute::context(__DIR__);

    ModuleRoute::resource('admin/games', 'AdminGamesController');
    ModuleRoute::post('admin/games/search', 'AdminGamesController@search');

The context method sets the module context to the current module. This is essential to notify the helper about the module it' i's working with. When calling methods such as resource the helper will know to which module they belong. Read more in [the module routing chapter](Module_Routing).