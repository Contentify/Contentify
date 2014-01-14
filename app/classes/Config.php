<?php namespace Contentify;

use Illuminate\Support\Facades\Config as LaravelConfig;
use DB;

class Config extends LaravelConfig {

    /**
     * The result of the last has() call
     * 
     * @var stdClass
     */
    private static $lastResult = NULL;

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public static function has($key)
    {
        self::$lastResult = DB::table('config')->whereName($key)->first(); // Temporarily save the result
        
        return (self::$lastResult != NULL);
    }

    /**
     * Get the specified configuration value.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        if (self::has($key)) {
            return self::$lastResult->value;
        } else {
            return LaravelConfig::get($key, $default);
        }
    }

    /**
     * Save a given configuration value to DB.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public static function store($key, $value)
    {
        $result = DB::table('config')->whereName($key)->update(array('value' => $value, 'updated_at' => new \DateTime()));

        /*
         * If the key does not exist we need to create it
         */
        if ($result == 0) {
            DB::table('config')->insert(array('name' => $key, 'value' => $value, 'updated_at' => new \DateTime()));
        }
    }
}