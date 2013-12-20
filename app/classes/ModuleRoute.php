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
		Route::model(self::$moduleName, self::$modelPath.$modelName);
	}

	public static function get($route, $target)
	{
		self::createRoute('get', $route, $target);
	}

	public static function post($route, $target)
	{
		self::createRoute('post', $route, $target);
	}

	/**
	* Create the route. Add paths.
	*/
	private static function createRoute($method, $route, $target)
	{
		if (! is_array($target)) {
			$target = ['uses' => $target];
		}

		// Determine if the target is a controller method.
		// If so, add the controller path.
		if (str_contains($target['uses'], '@')) {
			$target['uses'] = self::$controllerPath.$target['uses'];
		}

		Route::$method($route, $target);
	}
}