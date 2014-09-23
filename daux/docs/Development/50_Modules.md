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

PHP files that handle backend stuff should have an "admin" prefix.

 `modules.json` is a config file containing the configuration of the module. Minimum example file:

    {
        "enabled": true
    }

Possible properties are:

* *enabled* (boolean): Enables or disables the module
* *search* (array): Array with names of controllers that support the global search
* *admin-nav* (array): Array of arrays that describe items of the backend module navigation. Supported properties are (all of them are optional):
    * *icon* (string): Filename of an icon (default: `newspaper.png`)
    * *category* (int): ID of the navigation category (default: 1)
    * *position* (int): Position inside the navigation category (default: 999)
    * *title* (string): Title of the navigation item (default: name of the module)
    * *url* (string): URL to the controller (default: `admin/<module-name>`)

The `routes.php` file is some kind of a clone of `app/routes.php`. But since this is a module Contentify has a helper class called `ModuleRoute`. It creates some shortcuts to routing functions. Example file:

    ModuleRoute::context(__DIR__);

    ModuleRoute::resource('admin/games', 'AdminGamesController');
    ModuleRoute::post('admin/games/search', 'AdminGamesController@search');

The context method sets the module context to the current module. This is essential to notify the helper about the module it' i's working with. When calling methods such as resource the helper will know to which module they belong. Read more in [the module routing chapter](Module_Routing). Ofcourse, Laravel's `Route` class is still available.

## Module Specifics

Whenever a Laravel method is called that accesses data that is tied to a module `::` is the seperator.

    // Recieve line "name" from app/modules/cookies/lang/../cake.php:
    $name = trans('cookies::cake.name'); 

    // Make view from app/modules/cookies/view/cookie.php:
    $view = View::make('cookies::cookie');

## Module Installer

Modules may have their own installer if they need to run a setup process. The installer is a class extending the `ModuleInstaller` class. It lives in the root of its module folder in the `Installer.php` file.

The execute method of the installer may return three kinds of values:

* *true*: Installation completed
* *false*: Installation terminated (due to an error)
* *string* (or View): Visual output 

If the return values is not a boolean it will be added to the page output. You may use this to render forms if the installer needs to request user input. This fictional example creates a view that renders a form with a single field `tableName` at step 0. The form action is set to the URL of the next step. In step 1 the installer takes the field value and creates a table with the custom name. At the end of step 1 the method returns true to indicate the end of the installation.

    namespace App\Modules\Example;

    use View, ModuleInstaller;

    class Installer extends ModuleInstaller {

        public function execute($step)
        {
            if ($step == 0) {
                $url = $this->nextStep();

                return View::make('cookies::cookie_installer', $url);
            } else {
                $tableName = Input::get('tableName', 'cookies');

                Schema::create($tableName, function($table)
                {
                    $table->increments('id');
                    // ...
                });

                return true;
            }
        }

    }

Did you notice the `nextStep` method? This is a method provided by the base class. Its a helper and returns the URL of the "next step" of the current installation. If passed to a view its possible to create a link the user has to click to proceed to the next step.

To start the installation open the "modules" module in the backend. It will display an install button near to modules that have an installer. It's not necessary to update the autoloading classmap. The "modules" module has its own loader for module installers.