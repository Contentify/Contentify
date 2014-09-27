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
     * @param  bool    $dbLookup If false, do not access the database table
     * @return bool
     */
    public static function has($key, $dbLookup = true)
    {
        // LaravelConfig::has() will return true for an invalid (= not namespaced) key! So we don't trust has() here.
        if (strpos($key, '.') !== false and LaravelConfig::has($key)) {
            return true;
        } elseif ($dbLookup) {
            self::$lastResult = DB::table('config')->whereName($key)->first(); // Temporarily save the result 
            
            return (self::$lastResult != null);   
        } else {
            return false;
        }             
    }

    /**
     * Get the specified configuration value.
     *
     * @param  string  $key      Name of the key
     * @param  mixed   $default  Default value
     * @param  bool    $dbLookup If false, do not access the database table
     * @return mixed
     */
    public static function get($key, $default = null, $dbLookup = true)
    {
        // LaravelConfig::has() will return true for an invalid (= not namespaced) key! So we don't trust has() here.
        if (strpos($key, '.') !== false and LaravelConfig::has($key)) {
            return LaravelConfig::get($key, $default);
        } elseif ($dbLookup and self::has($key)) {
            return self::$lastResult->value;
        } else {
            return $default;
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