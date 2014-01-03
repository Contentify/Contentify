<?php

class ModuleRoute {
	/**
	 * The name of the module
	 * @var string
	 */
	private static $moduleName;

	/**
	 * The path to the models of the module
	 * @var string
	 */
	private static $modelPath;

	/**
	 * The path to the controllers of the module
	 * @var string
	 */
	private static $controllerPath;

	/**
	 * Set the context (name) of the module.
	 * @param string $moduleName Name or path of the module.
	 */
	public static function context($moduleName)
	{
		$moduleName = class_basename($moduleName);
		$moduleName = ucfirst(strtolower($moduleName));

		self::$moduleName 		= $moduleName;

		self::$modelPath 		= 'App\Modules\\'.$moduleName.'\Models\\';
		self::$controllerPath 	= 'App\Modules\\'.$moduleName.'\Controllers\\';
	}

	/**
	 * Bind a model to a route
	 * @param string $modelName The name of the model (without namespace)
	 */
	public static function model($modelName)
	{
		return Route::model(self::$moduleName, self::$modelPath.$modelName);
	}

	/**
	 * Create route for method get.
	 * @param  string $route
	 * @param  mixed  $target
	 */
	public static function get($route, $target)
	{
		return self::createRoute('get', $route, $target);
	}

	/**
	 * Create route for method post.
	 * @param  string $route
	 * @param  mixed  $target
	 */
	public static function post($route, $target)
	{
		return self::createRoute('post', $route, $target);
	}

	/**
	 * Create route for method any.
	 * @param  string $route
	 * @param  mixed  $target
	 */
	public static function any($route, $target)
	{
		return self::createRoute('any', $route, $target);
	}

	/**
	 * Controller routing (for RESTful controllers).
	 * @param  string $route
	 * @param  string $target
	 * @param  array  $parameters
	 */
	public static function controller($route, $target, $parameters = array())
	{
		if (Config::get('app.debug')) $_SESSION['ModuleRoute.controller'] = $target; // Debugging

		// Route::controller() only accepts a controller name (string) as target.
		Route::controller($route, self::$controllerPath.$target, $parameters);
	}

	/**
	 * Controller routing (for resource controllers).
	 * @param  string $route
	 * @param  string $target
	 * @param  array  $parameters
	 */
	public static function resource($route, $target, $parameters = array())
	{
		if (Config::get('app.debug')) $_SESSION['ModuleRoute.resource'] = $target; // Debugging

		Route::resource($route, self::$controllerPath.$target, $parameters);
	}

	/**
	 * Create the route. Add paths.
	 * @param  string $method
	 * @param  string $route
	 * @param  mixed  $target
	 */
	private static function createRoute($method, $route, $target)
	{
		if (Config::get('app.debug')) $_SESSION['ModuleRoute.route'] = $target; // Debugging

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