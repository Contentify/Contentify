<?php

namespace Contentify;

use Cache;
use DateTime;
use DB;
use Illuminate\Support\Facades\Config as LaravelConfig;

/**
 * Classical Laravel config files in the "config" folder are awesome.
 * However, they miss one important feature: They are read-only.
 * This class removes this constraint by using the database to
 * persist config values. Therefore it introduces its store() method.
 * It uses (file) caching to reduce database queries to a minimum.
 */
class Config extends LaravelConfig
{

    /**
     * Cache key prefix: The corresponding values contain booleans that are true
     * if the config key has been retrieved from the DB already
     */
    const CACHE_IN_DB_PREFIX = 'config::inDb.';

    /**
     * Cache key prefix: The corresponding config values were cached after
     * they have been retrieved from the DB
     */
    const CACHE_VALUES_PREFIX = 'config::values.';

    /**
     * Cache time in seconds
     */
    const CACHE_TIME = 300;

    /**
     * Determine if the given configuration value exists.
     *
     * @param string $key      The key for the value
     * @param bool   $dbLookup If false, do not access the database table
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
                // Try to retrieve the config value from the DB. The result will be a \stdClass or null
                $result = DB::table('config')->whereName($key)->first();

                if (is_null($result)) {
                    // Remember that this value is NOT stored in the DB - so we do not have to try to query it
                    Cache::put(self::CACHE_IN_DB_PREFIX.$key, false, self::CACHE_TIME);
                } else {
                    Cache::put(self::CACHE_IN_DB_PREFIX.$key, true, self::CACHE_TIME);

                    // Cache the config value
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
     * @param string $key      The name of the key
     * @param mixed  $default  The default value
     * @param bool   $dbLookup If false, do not access the database table
     * @return mixed
     */
    public static function get($key, $default = null, $dbLookup = true)
    {
        if (installed() and $dbLookup) {
            $dbChecked = Cache::has(self::CACHE_IN_DB_PREFIX.$key);

            if ($dbChecked) {
                $inDb = Cache::get(self::CACHE_IN_DB_PREFIX.$key);

                if ($inDb) {
                    // Return the cached config value
                    return Cache::get(self::CACHE_VALUES_PREFIX.$key);
                }
            } else {
                // Try to retrieve the config value from the DB. The result will be a \stdClass or null
                $result = DB::table('config')->whereName($key)->first();

                if (is_null($result)) {
                    // Remember that this value is NOT stored in the DB - so we do not have to try to query it
                    Cache::put(self::CACHE_IN_DB_PREFIX.$key, false, self::CACHE_TIME);
                } else {
                    Cache::put(self::CACHE_IN_DB_PREFIX.$key, true, self::CACHE_TIME);

                    // Cache the config value
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
     * Store a given configuration value into the "config" table of the DB.
     * If the value does not exist a new record will be created.
     *
     * @param string $key   The name of the key
     * @param mixed  $value The value - will be stored as a string
     * @return void
     */
    public static function store($key, $value)
    {
        $result = DB::table('config')
            ->whereName($key)
            ->update(array('value' => $value, 'updated_at' => new DateTime()));

        /*
         * If the key does not exist we need to create it
         * $result contains the number of affected rows.
         * With using a timestamp we ensure that when updating a value
         * the row is always affected, even though if the value does not change.
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
     * @param string $key The name of the key
     * @return void
     */
    public static function delete($key)
    {
        DB::table('config')->whereName($key)->delete();

        self::clearCache($key);
    }

    /**
     * Clear the cache for a given configuration key.
     * It does not matter if this key exists in the cache.
     *
     * @param string $key The name of the key
     * @return void
     */
    public static function clearCache($key)
    {
        Cache::forget(self::CACHE_VALUES_PREFIX.$key);
        Cache::forget(self::CACHE_IN_DB_PREFIX.$key);
    }

}