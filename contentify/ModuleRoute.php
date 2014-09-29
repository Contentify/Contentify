<?php namespace Contentify;

use Str, Config, Route;

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
     * Set the context (name) of the module.
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
            $path               = Config::get('modules::path');
            $studly             = Str::title(str_replace('/', ' ', $path));
            $this->namespace    = str_replace(' ', '\\', $studly);
        }

        $this->modelPath        = $this->namespace.'\\'.$moduleName.'\Models\\';
        $this->controllerPath   = $this->namespace.'\\'.$moduleName.'\Controllers\\';
    }

    /**
     * Bind a model to a route
     * 
     * @param string $modelName The name of the model (without namespace)
     * @return Illuminate\Routing\Route
     */
    public function model($modelName)
    {
        return Route::model($this->moduleName, $this->modelPath.$modelName);
    }

    /**
     * Create a route for method get.
     * 
     * @param  string $route
     * @param  mixed  $target
     * @return Illuminate\Routing\Route
     */
    public function get($route, $target)
    {
        return $this->createRoute('get', $route, $target);
    }

    /**
     * Create a route for method post.
     * 
     * @param  string $route
     * @param  mixed  $target
     * @return Illuminate\Routing\Route
     */
    public function post($route, $target)
    {
        return $this->createRoute('post', $route, $target);
    }

    /**
     * Create a route for method put.
     * 
     * @param  string $route
     * @param  mixed  $target
     * @return Illuminate\Routing\Route
     */
    public function put($route, $target)
    {
        return $this->createRoute('put', $route, $target);
    }

    /**
     * Create a route for method any.
     * 
     * @param  string $route
     * @param  mixed  $target
     * @return Illuminate\Routing\Route
     */
    public function any($route, $target)
    {
        return $this->createRoute('any', $route, $target);
    }

    /**
     * Create a route for several methods.
     *
     * @param  array  $methods
     * @param  string $route
     * @param  mixed  $target
     * @return Illuminate\Routing\Route
     */
    public function match($methods, $route, $target)
    {
        return $this->createRoute($methods, $route, $target);
    }

    /**
     * Controller routing (for RESTful controllers).
     * 
     * @param  string $route
     * @param  string $target
     * @param  array  $parameters
     * @return void
     */
    public function controller($route, $target, $parameters = array())
    {
        //if (Config::get('app.debug')) $_SESSION['ModuleRoute.controller'] = $target; // Debugging

        Route::controller($route, $this->controllerPath.$target, $parameters);
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
        //if (Config::get('app.debug')) $_SESSION['ModuleRoute.resource'] = $target; // Debugging

        Route::resource($route, $this->controllerPath.$target, $parameters);
    }

    /**
     * Create the route. Add paths.
     * 
     * @param  string|array             $methods
     * @param  string                   $route
     * @param  mixed                    $target
     * @return Illuminate\Routing\Route
     */
    protected function createRoute($methods, $route, $target)
    {
        //if (Config::get('app.debug')) $_SESSION['ModuleRoute.route'] = $target; // Debugging

        /*
         * Ignore closures:
         */
        if (is_string($target) or is_array($target)) {
            
            /* 
             * Alway create an array:
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