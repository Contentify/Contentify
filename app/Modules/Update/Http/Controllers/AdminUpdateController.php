<?php namespace App\Modules\Update\Http\Controllers;

use App\Modules\Update\UpdateThread;
use Response, Config, Cache, Exception, BackController;

class AdminUpdateController extends BackController {

    /**
     * Base of the URL to the updater file.
     */
    const UPDATER_URL = 'http://contentify.org/share/update/updater-v';

    /**
     * Defines the desired value for max_execution_time (=5 minutes)
     */
    const EXECUTION_TIME_TARGET = 300;

    /**
     * Defines the cache key used for storing the time of the start of the update
     */
    const CACHE_KEY_TIMESTAMP = 'app.update.timestamp';

    protected $icon = 'cloud-download-alt';

    /**
     * Shows the update intro page. Shows information about the update,
     * informs about problems and shows a "start" button if an update is possible.
     * 
     * @return void
     */
    public function index()
    {
        $running = Cache::has(self::CACHE_KEY_TIMESTAMP);

        $currentVersion = Config::get('app.version');
        $nextVersion = $this->incrementVersion($currentVersion);

        $url = $this->buildUpdaterUrl();

        $fileHeaders = @get_headers($url);

        $serverReachable = true;
        if (! $fileHeaders) {
            $serverReachable = false;
            $nextVersion = null;
        } elseif (in_array('HTTP/1.1 404 Not Found', $fileHeaders)) {
            $nextVersion = null;
        }

        $data = [
            'executionTimeTarget' => self::EXECUTION_TIME_TARGET, 
            'running' => $running,
            'serverReachable' => $serverReachable,
            'nextVersion' => $nextVersion
        ];
        $this->pageView('update::admin_index', $data);
    }

    /**
     * Tries to download the updater and then perform the update
     * 
     * @return void
    */
    public function update()
    {
        Cache::forever(self::CACHE_KEY_TIMESTAMP, time());

        /*
         * If possible (safe mode not enabled), set the execution time limit
         * to more than just the default 30 seconds.
         */
        if (! ini_get('safe_mode') and ini_get('max_execution_time') < self::EXECUTION_TIME_TARGET) {
            set_time_limit(self::EXECUTION_TIME_TARGET);
        }

        $url = $this->buildUpdaterUrl();

        $updaterPath = storage_path('app/Updater.php');
        $result = file_put_contents($updaterPath, fopen($url, 'r'));

        if ($result === false) {
            Cache::forget(self::CACHE_KEY_TIMESTAMP);
            $this->alertError('Error: Downloading the updater failed.');
            return;
        }

        $result = require $updaterPath;
        /** @var UpdaterInterface $updater */
        $updater = new \Contentify\Updater($this);

        try {
            $updater->update();

            $this->alertInfo('Update completed. Welcome to version '.$updater->getVersion().'!');
        } catch (Exception $ex) {
            $this->alertInfo('Update failed. There was an error during the update process: '.$ex->getMessage());
        }

        Cache::forget(self::CACHE_KEY_TIMESTAMP);
    }

    /**
     * Pass a correctly formatted version number and the method will increases the minor part.
     * it will never increase the major part.
     * Warning: The version number will not be validated! Passing 
     * a not correctly formatted version number might cause an error!
     *
     * Examples:
     * 1 => 1.1
     * 1.0 => 1.1
     * 1.0.1 => 1.1
     * 1.0-beta => 1.1
     * 
     * @param  string $version Correctly formatted version number
     * @return string
     */
    protected function incrementVersion($version)
    {
        // Currently this is not meant for pre-release version numbers,
        // for example '1.0-beta', so we simply cut this part.
        $parts = explode('-', $version);
        $version = $parts[0];

        // Get parts of the version (major, minor, et.c)
        $parts = explode('.', $version);
        $major = $parts[0];

        if (sizeof($parts) == 1) {
            return $major.'.1';
        }

        $minor = $parts[1];
        $minor++;

        return $major.'.'.$minor;
    }

    /**
     * Returns the URL of the CDN with the download of the updater
     * of the next version
     * 
     * @return string URL
     */
    protected function buildUpdaterUrl()
    {
        $currentVersion = Config::get('app.version');
        $nextVersion = $this->incrementVersion($currentVersion);

        return self::UPDATER_URL.$nextVersion;
    }

}