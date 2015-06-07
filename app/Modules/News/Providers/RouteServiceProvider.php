<?php namespace App\Modules\News\Providers;

use Caffeinated\Modules\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider {

    protected $namespace = 'App\Modules\News\Http\Controllers';

    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function($router)
        {
            require (config('modules.path').'/News/Http/routes.php');
        });
    }

}