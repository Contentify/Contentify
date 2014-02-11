<?php namespace Contentify;

use Illuminate\Support\Facades\Config as LaravelConfig;
use DB, DateTime;

class Config extends LaravelConfig {

    /**
     * The result of the last has() call
     * 
     * @var stdClass
     */
    private static $lastResult = null;

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public static function has($key)
    {
        self::$lastResult = DB::table('config')->whereName($key)->first(); // Temporarily save the result
        
        return (self::$lastResult != null);
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
     * Store a given configuration value into DB.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public static function store($key, $value)
    {
        $result = DB::table('config')->whereName($key)
            ->update(array('value' => $value, 'updated_at' => new DateTime()));

        /*
         * If the key does not exist we need to create it
         * $result contians the number of affected rows.
         * With using a timestamp we ensure that when updating a value
         * the row is always affacted, eventhough if the value does not change.
         */
        if ($result == 0) {
            DB::table('config')->insert(array('name' => $key, 'value' => $value, 'updated_at' => new DateTime()));
        }
    }

    /**
     * Delete a given configuration value from DB.
     *
     * @param  string  $key
     * @return void
     */
    public static function delete($key)
    {
        $result = DB::table('config')->whereName($key)->delete();
    }
}