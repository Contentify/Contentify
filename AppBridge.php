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

// Require Laravels helpers file with useful helper functions
require __DIR__.'/vendor/laravel/framework/src/Illuminate/Foundation/helpers.php';
require __DIR__.'/vendor/laravel/framework/src/Illuminate/Support/helpers.php';

/**
 * This class tries to give the (attempt of an) answer to the question:
 * How can we use parts of Contentify *oustide* of the actual Contentify
 * application, especially when it is not yet installed?
 * This script & class allows us to access some(!) parts of Contentify
 * from outside. 
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
     * Returns the values of a config file. 
     * Does not check if the file is a valid config file!
     *
     * @param string $name The (file) name of the config; without path
     * @return array[]
     */
    public function loadConfig($name)
    {
        $config = include $this->getConfigDir().$name.'.php';

        return $config;
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

        // This will only work for MySQL database
        // TODO: Use config/database.php config file to make this less rigid
        $dsn = 'mysql:dbname='.$settings['database'].';host='.$settings['host'];

        $pdo = new PDO($dsn, $settings['username'], $settings['password']);
    }

    /**
     * Returns true if the application is installed
     * 
     * @return boolean
     */
    public function isAppInstalled()
    {
        $filename = $this->storageDir.'app/.installed';
        return file_exists(__DIR__.'/../'.$filename);
    }

    /**
     * Determine if PHP is being run from the CLI
     * 
     * @return boolean
     */
    public function isCli()
    {
        return (php_sapi_name() === 'cli');
    }

}