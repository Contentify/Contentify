They idea of module routing is to simplify module related routing. Every module may have its own `routes.php` file that includes the module's routes. Let's take a look at a module route in good old Laravel style:

    Route::get('admin/games/edit/{id}', 'App\Modules\Games\Controllers\AdminController@getEdit');

The controller's name is prefixed with a giant namespace string. What if we could get rid of it? Let's take another look on the same route but this time with module routing:

    ModuleRoute::context(__DIR__);
    ModuleRoute::get('admin/games/edit/{id}', 'AdminController@getEdit');

The context method tells the ModuleRoute class to prefix our `AdminController` with the namespace of the current module. ModuleRoute offers several methods which mimic methods of the Route class:

* *model*
* *get*
* *post*
* *put*
* *patch*
* *delete*
* *options*
* *any*
* *match*
* *controller*
* *resource*

All of these must not be called before a context has been set. Take a look at the [official Laravel docs](http://laravel.com/docs/routing) to learn more about these methods.