<?php namespace Contentify;

use Illuminate\Support\Facades\Config as LaravelConfig;
use Cache, DB, DateTime;

class Config extends LaravelConfig {

    const CACHE_KEY_PREFIX = 'caonfig.keys.';

    /**
     * The result of the last has() call
     * 
     * @var stdClass
     */
    protected static $lastResult = null;

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string  $key
     * @param  bool    $dbLookup If false, do not access the database table
     * @return bool
     */
    public static function has($key, $dbLookup = true)
    {
        // LaravelConfig::has() will return true for an invalid (=not namespaced) key! So we can't trust has() blindly.
        if ((strpos($key, '.') !== false or strpos($key, '::') !== false) and LaravelConfig::has($key)) {
            return true;
        } elseif ($dbLookup) {
            if (Cache::has(self::CACHE_KEY_PREFIX.$key)) {
                return true;
            } else {
                self::$lastResult = DB::table('config')->whereName($key)->first(); // Temporarily save the result 
                
                return (self::$lastResult != null);   
            }
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
        // LaravelConfig::has() will return true for an invalid (=not namespaced) key! So we can't trust has() blindly.
        if ((strpos($key, '.') !== false or strpos($key, '::') !== false) and LaravelConfig::has($key)) {
            return LaravelConfig::get($key, $default);
        } elseif ($dbLookup and self::has($key)) {
            if (Cache::has(self::CACHE_KEY_PREFIX.$key)) {
                return Cache::get(self::CACHE_KEY_PREFIX.$key);
            } else {
                Cache::put(self::CACHE_KEY_PREFIX.$key, self::$lastResult->value, 60);

                return self::$lastResult->value;
            }
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
    public function store($key, $value)
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

        Cache::put(self::CACHE_KEY_PREFIX.$key, $value, 60);
    }

    /**
     * Delete a given configuration value from DB.
     *
     * @param  string  $key
     * @return void
     */
    public function delete($key)
    {
        $result = DB::table('config')->whereName($key)->delete();

        Cache::forget(self::CACHE_KEY_PREFIX.$key);
    }
}