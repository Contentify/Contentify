<?php namespace Contentify;

use Illuminate\Support\Facades\Config as LaravelConfig;
use Cache, DB, DateTime;

class Config extends LaravelConfig {

    /**
     * Cache key prefix: The corresponding values contain bools that are true if the
     * config key has been retrieved from the DB already
     */
    const CACHE_IN_DB_PREFIX = 'config::inDb.';

    /**
     * Cache key prefix: The corresponding values were cached after they habe been
     * retrieved from the DB
     */
    const CACHE_VALUES_PREFIX = 'config::values.';

    /**
     * Cache time in seconds
     */
    const CACHE_TIME = 300;

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string  $key
     * @param  bool    $dbLookup If false, do not access the database table
     * @return bool
     */
    public static function has($key, $dbLookup = true)
    {
        if (installed() and $dbLookup) {
            $dbChecked = Cache::has(self::CACHE_IN_DB_PREFIX.$key);

            if ($dbChecked) {
                $inDb = Cache::get(self::CACHE_IN_DB_PREFIX.$key);

                if ($inDb) {
                    return true;
                }
            } else {
                $result = DB::table('config')->whereName($key)->first();

                if (is_null($result)) {
                    Cache::put(self::CACHE_IN_DB_PREFIX.$key, false, self::CACHE_TIME);
                } else {
                    Cache::put(self::CACHE_IN_DB_PREFIX.$key, true, self::CACHE_TIME);
                    Cache::put(self::CACHE_VALUES_PREFIX.$key, $result->value, self::CACHE_TIME);
                    return true;                    
                }
            }
        }

        if ((strpos($key, '.') !== false or strpos($key, '::') !== false) and LaravelConfig::has($key)) {
            return true;
        }

        return false;
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
        if (installed() and $dbLookup) {
            $dbChecked = Cache::has(self::CACHE_IN_DB_PREFIX.$key);

            if ($dbChecked) {
                $inDb = Cache::get(self::CACHE_IN_DB_PREFIX.$key);

                if ($inDb) {
                    return Cache::get(self::CACHE_VALUES_PREFIX.$key);
                }
            } else {
                $result = DB::table('config')->whereName($key)->first();

                if (is_null($result)) {
                    Cache::put(self::CACHE_IN_DB_PREFIX.$key, false, self::CACHE_TIME);
                } else {
                    Cache::put(self::CACHE_IN_DB_PREFIX.$key, true, self::CACHE_TIME);
                    Cache::put(self::CACHE_VALUES_PREFIX.$key, $result->value, self::CACHE_TIME);
                    return $result->value;                    
                }
            }
        }

        if ((strpos($key, '.') !== false or strpos($key, '::') !== false) and LaravelConfig::has($key)) {
            return LaravelConfig::get($key, $default);
        }

        return $default;
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
         * $result contains the number of affected rows.
         * With using a timestamp we ensure that when updating a value
         * the row is always affacted, eventhough if the value does not change.
         */
        if ($result == 0) {
            DB::table('config')->insert(array('name' => $key, 'value' => $value, 'updated_at' => new DateTime()));
        }

        Cache::put(self::CACHE_IN_DB_PREFIX.$key, true, self::CACHE_TIME);
        Cache::put(self::CACHE_VALUES_PREFIX.$key, $value, self::CACHE_TIME);
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

        self::clearCache($key);
    }

    /**
     * Clear the cache for a given configuration key.
     *
     * @param  string  $key
     * @return void
     */
    public static function clearCache($key)
    {
        Cache::forget(self::CACHE_VALUES_PREFIX.$key);
        Cache::forget(self::CACHE_IN_DB_PREFIX.$key);
    }

}