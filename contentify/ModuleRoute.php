<?php namespace Contentify;

use Config, Route;

class ModuleRoute {
  
    /**
     * The namespace for all modules
     * @var  string
     */
    protected $namespace;

    /**
     * The name of the module set by context()
     * @var string
     */
    protected $moduleName;

    /**
     * The path to the models of the module
     * @var string
     */
    protected $modelPath;

    /**
     * The path to the controllers of the module
     * @var string
     */
    protected $controllerPath;

    /**
     * Sets the context (name) of the module.
     * 
     * @param string $moduleName Name or path of the module.
     * @return void
     */
    public function context($moduleName)
    {
        $moduleName = class_basename($moduleName);
        $moduleName = ucfirst(strtolower($moduleName));

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
    public function model($modelName)
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
    public function get($route, $target)
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
    public function post($route, $target)
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
    public function put($route, $target)
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
    public function patch($route, $target)
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
    public function delete($route, $target)
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
    public function options($route, $target)
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
    public function any($route, $target)
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
    public function match($methods, $route, $target)
    {
        return $this->createRoute($methods, $route, $target);
    }

    /**
     * Method stub for the old controller()-method.
     * Controller routing (for RESTful controllers) is no longer supported,
     * because Laravel does no longer support it.
     * Please replace any calls of this method by explicit
     * calls of methods such as get().
     *
     * @param  string $route
     * @param  string $target
     * @param  array  $parameters
     * @return void
     * @throws \Exception
     */
    public function controller($route, $target, $parameters = array())
    {
        // TODO Remove the whole method in the next version of the CMS
        throw new \Exception(
            'Error: Method ModuleRoute::controller() is no longer supported. Please replace any calls by explicit calls'
        );
    }

    /**
     * Controller routing (for resource controllers).
     * 
     * @param  string $route
     * @param  string $target
     * @param  array  $parameters
     * @return void
     */
    public function resource($route, $target, $parameters = array())
    {
        if (! isset($parameters['name'])) {
            $parameters['name'] = str_replace('/', '.', $route);
        }

        Route::resource($route, $this->controllerPath.$target, $parameters);
    }

    /**
     * Create a route group with shared attributes.
     *
     * @param  array  $attributes
     * @param  \Closure  $callback
     * @return void
     */
    public function group(array $attributes, \Closure $callback)
    {
        Route::group($attributes, $callback);
    }

    /**
     * Creates the route. Adds paths.
     * 
     * @param  string|array             $methods
     * @param  string                   $route
     * @param  mixed                    $target
     * @return \Illuminate\Routing\Route
     */
    protected function createRoute($methods, $route, $target)
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

}