<?php

class ModuleRoute {
	/**
	* The name of the module
	*/
	private static $moduleName;

	/**
	* The path to the models of the module
	*/
	private static $modelPath;

	/**
	* The path to the controllers of the module
	*/
	private static $controllerPath;

	/**
	* Set the context (name) of the module.
	*/
	public static function context($moduleName)
	{
		self::$moduleName 		= $moduleName;

		self::$modelPath 		= 'App\Modules\\'.$moduleName.'\Models\\';
		self::$controllerPath 	= 'App\Modules\\'.$moduleName.'\Controllers\\';
	}

	/**
	* Bind a model to a route
	*/
	public static function model($modelName)
	{
		return Route::model(self::$moduleName, self::$modelPath.$modelName);
	}

	/**
	* Create route for method get.
	*/
	public static function get($route, $target)
	{
		return self::createRoute('get', $route, $target);
	}

	/**
	* Create route for method post.
	*/
	public static function post($route, $target)
	{
		return self::createRoute('post', $route, $target);
	}

	/**
	* Create route for method any.
	*/
	public static function any($route, $target)
	{
		return self::createRoute('any', $route, $target);
	}

	/**
	* Controller routing (for RESTful controllers).
	*/
	public static function controller($route, $target, $parameters = array())
	{
		// Debugging
		if (Config::get('app.debug')) $_SESSION['ModuleRoute.controller'] = $target;

		// Route::controller() only a controller name (string) as target.
		Route::controller($route, self::$controllerPath.$target, $parameters);
	}

	/**
	* Controller routing (for resource controllers).
	*/
	public static function resource($route, $target, $parameters = array())
	{
		// Debugging
		if (Config::get('app.debug')) $_SESSION['ModuleRoute.resource'] = $target;

		Route::resource($route, self::$controllerPath.$target, $parameters);
	}

	/**
	* Create the route. Add paths.
	*/
	private static function createRoute($method, $route, $target)
	{
		// Debugging
		if (Config::get('app.debug')) $_SESSION['ModuleRoute.route'] = $target;

		// Ignore closures:
		if (is_string($target) or is_array($target)) {
			
			// Alway create an array:
			if (! is_array($target)) {
				$target = ['uses' => $target];
			}

			// Determine if the target is a controller method.
			// If so, add the controller path.
			if (str_contains($target['uses'], '@')) {
				$target['uses'] = self::$controllerPath.$target['uses'];
			}
		}

		return Route::$method($route, $target);
	}

}