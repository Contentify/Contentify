Modules are Laravel packages with some sugar. If you prefer pure packages there is no reason why you shouldn't use them over modules. But modules have advantages too. They are easier to create and they may have an installer.

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

## Module Installer

Modules may have their own installer if they need to run a setup process. The installer is a class extending the ModuleInstaller class. It lives in the root of its module folder in the `Installer.php` file.

The execute method of the installer may return three kinds of values:

* *true*: Installation completed
* *false*: Installation terminated (due to an error)
* *string* (or View): Visual output 

If the return values is not a boolean it will be added to the page output. You may use this to render forms if the installer needs to request user input.

    namespace App\Modules\Example;

    use ModuleInstaller;

    class Installer extends ModuleInstaller {

        public function execute($step)
        {
            // Do some Work - e. g. create an initial database seed

            return true;
        }

    }

To start the installation open the "modules" module in the backend. It will display an install button near to modules that have an installer. It's not necessary to update the autoloading classmap. The "modules" module has its own loader.