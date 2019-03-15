<?php

namespace Contentify;

use Config;
use Route;

/**
 * This class decorates the original Router class of Laravel.
 * It adds support for module routes so that defining module routes becomes easier.
 *
 * @see https://github.com/Contentify/Contentify/wiki/Module-Routing
 * @see \Illuminate\Routing\Router
 */
class ModuleRoute
{

    /**
     * Since Laravel 5.3 we have to manually prefix the names
     * of backend (admin) routes. We use this constant as the prefix.
     * Note that it is not possible to directly access this constant
     * if the facade of this class is used. Use the getter instead!
     */
    const ADMIN_NAME_PREFIX = 'admin.';
  
    /**
     * The namespace for all modules
     *
     * @var  string
     */
    protected $namespace;

    /**
     * The name of the module set by context()
     *
     * @var string
     */
    protected $moduleName;

    /**
     * The path to the models of the module
     *
     * @var string
     */
    protected $modelPath;

    /**
     * The path to the controllers of the module
     *
     * @var string
     */
    protected $controllerPath;

    /**
     * Sets the context (name) of the module.
     * 
     * @param string $moduleName Name or path of the module.
     * @return void
     */
    public function context(string $moduleName)
    {
        $moduleName = class_basename($moduleName);
        $moduleName = ucfirst($moduleName);

        $this->moduleName       = $moduleName;

        /*
         * Create the namespace from the directory path
         */
        if (! $this->namespace) {
            $this->namespace    = Config::get('modules.namespace');
        }

        $this->modelPath        = $this->namespace.$moduleName.'\\';
        $this->controllerPath   = $this->namespace.$moduleName.'\Http\Controllers\\';
    }

    /**
     * Binds a model to a route
     * 
     * @param string $modelName The name of the model (without namespace)
     * @return void
     */
    public function model(string $modelName)
    {
        Route::model($this->moduleName, $this->modelPath.$modelName);
    }

    /**
     * Creates a route for method "get".
     * 
     * @param  string $route
     * @param  mixed  $target
     * @return \Illuminate\Routing\Route
     */
    public function get(string $route, $target) : \Illuminate\Routing\Route
    {
        return $this->createRoute('get', $route, $target);
    }

    /**
     * Creates a route for method "post".
     * 
     * @param  string $route
     * @param  mixed  $target
     * @return \Illuminate\Routing\Route
     */
    public function post(string $route, $target) : \Illuminate\Routing\Route
    {
        return $this->createRoute('post', $route, $target);
    }

    /**
     * Creates a route for method "put".
     * 
     * @param  string $route
     * @param  mixed  $target
     * @return \Illuminate\Routing\Route
     */
    public function put(string $route, $target) : \Illuminate\Routing\Route
    {
        return $this->createRoute('put', $route, $target);
    }

    /**
     * Creates a route for method "patch".
     * 
     * @param  string $route
     * @param  mixed  $target
     * @return \Illuminate\Routing\Route
     */
    public function patch(string $route, $target) : \Illuminate\Routing\Route
    {
        return $this->createRoute('patch', $route, $target);
    }

    /**
     * Creates a route for method "delete".
     * 
     * @param  string $route
     * @param  mixed  $target
     * @return \Illuminate\Routing\Route
     */
    public function delete(string $route, $target) : \Illuminate\Routing\Route
    {
        return $this->createRoute('delete', $route, $target);
    }

    /**
     * Creates a route for method "options".
     * 
     * @param  string $route
     * @param  mixed  $target
     * @return \Illuminate\Routing\Route
     */
    public function options(string $route, $target) : \Illuminate\Routing\Route
    {
        return $this->createRoute('options', $route, $target);
    }

    /**
     * Creates a route for method "any".
     * 
     * @param  string $route
     * @param  mixed  $target
     * @return \Illuminate\Routing\Route
     */
    public function any(string $route, $target) : \Illuminate\Routing\Route
    {
        return $this->createRoute('any', $route, $target);
    }

    /**
     * Creates a route for several methods.
     *
     * @param  array  $methods
     * @param  string $route
     * @param  mixed  $target
     * @return \Illuminate\Routing\Route
     */
    public function match(array $methods, string $route, $target) : \Illuminate\Routing\Route
    {
        return $this->createRoute($methods, $route, $target);
    }

    /**
     * Controller routing (for resource controllers).
     * 
     * @param  string $name
     * @param  string $controller
     * @param  array  $options
     * @return void
     */
    public function resource(string $name, string $controller, array $options = [])
    {
        Route::resource($name, $this->controllerPath.$controller, $options);
    }

    /**
     * Register an array of resource controllers.
     *
     * @param  array $resources
     * @return void
     */
    public function resources(array $resources)
    {
        foreach ($resources as $name => $controller) {
            $this->resource($name, $controller);
        }
    }

    /**
     * Create a route group with shared attributes.
     *
     * @param  array    $attributes
     * @param  \Closure $callback
     * @return void
     */
    public function group(array $attributes, \Closure $callback)
    {
        Route::group($attributes, $callback);
    }

    /**
     * Creates the route. Adds paths.
     * 
     * @param  string|array $methods
     * @param  string       $route
     * @param  mixed        $target
     * @return \Illuminate\Routing\Route
     */
    protected function createRoute($methods, string $route, $target) : \Illuminate\Routing\Route
    {
        /*
         * Ignore closures:
         */
        if (is_string($target) or is_array($target)) {
            
            /* 
             * Always create an array:
             */
            if (! is_array($target)) {
                $target = ['uses' => $target];
            }

            /* 
             * Determine if the target is a controller method.
             * If so, add the controller path.
             */
            if (str_contains($target['uses'], '@')) {
                $target['uses'] = $this->controllerPath.$target['uses'];
            }
        }

        /*
         * $methods can be an array of method verbs or a string with a single method verb.
         */
        if (is_array($methods)) {
            return Route::match($methods, $route, $target);
        } else {
            return Route::$methods($route, $target);
        }
    }

    /**
     * Returns the prefix for the names of backend (admin) routes
     *
     * @return string
     */
    public function getAdminNamePrefix() : string
    {
        return self::ADMIN_NAME_PREFIX;
    }

}
