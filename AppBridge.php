<?php

// Require Composer autoloader. 
// Note that this means that we have access to Laravel and Symfony components!
$autoloaderFile = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloaderFile)) {
    require $autoloaderFile;
} else {
    throw new \Exception(
        'Error: Could not find autoloader file "'.$autoloaderFile.'". Did you run "composer install"?'
    );
}

// Require Laravel's helpers file with useful helper functions
require __DIR__.'/vendor/laravel/framework/src/Illuminate/Foundation/helpers.php';
require __DIR__.'/vendor/laravel/framework/src/Illuminate/Support/helpers.php';

/**
 * This class tries to give the (attempt of an) answer to the question:
 * How can we use parts of Contentify *outside* of the actual Contentify
 * application, especially when it is not yet installed?
 * This script & class allows us to access some(!) parts of Contentify
 * from the outside. 
 * ATTENTION: There is no "safe sandbox" - there is no protection against
 * use/calls of classses and functions that do not work outside Contentify.
 * So if you use this class, better know what you are doing.
 */
class AppBridge 
{

    /**
     * Path of the application directory
     * 
     * @var string
     */
    protected $appDir = __DIR__.'/';

    /**
     * Path of the config directory
     * 
     * @var string
     */
    protected $configDir = '';

    /**
     * Path of the storage directory
     * 
     * @var string
     */
    protected $storageDir = '';

    /**
     * Array that stores the configs so we do not have 
     * to reload them for each config access
     * 
     * @var array
     */
    protected $configs = [];

    public function __construct()
    {
        $this->configDir = $this->appDir.'config/';
        $this->storageDir = $this->appDir.'storage/';
    }

    /**
     * Returns the path to the app directory
     * 
     * @return string
     */
    public function getAppDir() 
    {
        return $this->appDir;
    }

    /**
     * Returns the path to the config directory
     * 
     * @return string
     */
    public function getConfigDir()
    {
        return  $this->configDir;
    }

    /**
     * Returns the path to the storage directory
     *
     * @return string
     */
    public function getStorageDir()
    {
        return  $this->storageDir;
    }

    /**
     * Loads and returns the values of a config file. 
     * Does not check if the file is a valid config file!
     * Use getConfig() if you do not want to enforce reloading.
     *
     * @param string $name The (file) name of the config; without path and extension
     * @return array[]
     */
    public function loadConfig($name)
    {
        $config = include $this->getConfigDir().$name.'.php';

        return $config;
    }

    /**
     * Returns the values of a config file.
     * Uses caching so it does not read the file
     * for each request.
     * 
     * @param string $name The (file) name of the config; without path and extension
     * @return array[]
     */
    public function getConfig($name)
    {
        if (array_key_exists($name, $this->configs)) {
            return $this->configs[$name];
        } else {
            $config = $this->loadConfig($name);

            $this->configs[$name] = $config;

            return $config;
        }
    }

    /**
     * Returns the part of the database config that contains 
     * the connection details of a specific connection.
     * 
     * @param string $connection Key/name of the connection. Empty = use default
     * @return array
     */
    public function getDatabaseConnectionDetails($connection = '')
    {
        $config = $this->getConfig('database');

        if (! $connection) {
            $connection = $config['default'];
        }

        return $config['connections'][$connection];
    }

    /**
     * Creates a new database connection object and returns it
     * 
     * @return \PDO
     */
    public function createDatabaseConnection()
    {
        $configFile = $this->storageDir.'app/database.ini';
        $settings = parse_ini_file($configFile);

        // This will only work for MySQL database. If we ever want to support
        // more than just MySQL we have to adapt this code.
        // (Unfortunately we cannot use Laravel's ConnectionFactory class easily.)
        $driver = $this->getDatabaseConnectionDetails()['driver'];
        $dsn = $driver.':dbname='.$settings['database'].';host='.$settings['host'];

        $pdo = new PDO($dsn, $settings['username'], $settings['password']);

        return $pdo;
    }

    /**
     * Returns true if the application is installed
     * 
     * @return bool
     */
    public function isAppInstalled()
    {
        $filename = $this->storageDir.'app/.installed';

        return file_exists(__DIR__.'/../'.$filename);
    }

    /**
     * Determine if PHP is being run from the CLI
     * 
     * @return bool
     */
    public function isCli()
    {
        return (php_sapi_name() === 'cli');
    }

}
