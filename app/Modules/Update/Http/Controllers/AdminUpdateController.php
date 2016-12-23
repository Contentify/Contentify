<?php namespace App\Modules\Update\Http\Controllers;

use App\Modules\Update\UpdateThread;
use Response, Config, Cache, Exception, BackController;

class AdminUpdateController extends BackController {

    /**
     * Base of the URL to the repository. This is just used to check for
     * existence, not to download something.
     */
    const REPO_URL = 'https://github.com/Contentify/Contentify/releases/tag/v';

    /**
     * Defines the desired value for max_execution_time (=5 minutes)
     */
    const EXECUTION_TIME_TARGET = 300;

    /**
     * Defines the cache key used for storing the time of the start of the update
     */
    const CACHE_KEY_TIMESTAMP = 'app.update.timestamp';

    /**
     * Defines the cache key used for storing the result of the update process
     */
    const CACHE_KEY_RESULT = 'app.update.result';

    protected $icon = 'cloud-download';

    /**
     * Shows the update intro page. Shows information about the update,
     * informs about problems and shows a "start" button if an update is possible.
     * 
     * @return void
     */
    public function index()
    {
        $running = Cache::has(self::CACHE_KEY_TIMESTAMP);

        // Check if git is installed. We need it to perform the update.
        $result = exec('git --version');
        $expectedResult = 'git version ';
        $hasGit = (substr($result, 0, strlen($expectedResult)) === $expectedResult);

        // Check if there is a newer version
        $currentVersion = Config::get('app.version');
        $nextVersion = $this->incrementVersion($currentVersion);
        $nextVersion = '2.0-rc'; // TODO FIXME REMOVE THIS!
        $url = self::REPO_URL.$nextVersion;

        $fileHeaders = @get_headers($url);
        if (! $fileHeaders || $fileHeaders[0] != 'HTTP/1.1 200 OK') {
            $nextVersion = null;
        }

        $data = [
            'executionTimeTarget' => self::EXECUTION_TIME_TARGET, 
            'running' => $running, 
            'hasGit' => $hasGit,
            'nextVersion' => $nextVersion
        ];
        $this->pageView('update::admin_index', $data);
    }

    /**
     * Tries to perform the update
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

        $currentVersion = Config::get('app.version');
        $nextVersion = '2.0-rc'; // TODO FIXME REMOVE THIS!
        $nextVersion = $this->incrementVersion($currentVersion);

        $output = null;
        $returnCode = null;
        $result = exec('git checkout -f '.$nextVersion, $output, $returnCode);

        Cache::forever(self::CACHE_KEY_RESULT, $output);
        Cache::forget(self::CACHE_KEY_TIMESTAMP);

        // TODO Implement the unfinished part of the updater with an asynchronous update process
        //$this->pageView('update::admin_update_overview', compact('threadId'));
        $this->completed();
    }

    /**
     * Returns the current status. 
     * Returns -1 if the update process is not running,
     * returns the duration (in seconds) otherwise.
     * Notice that this method might be called AFTER the update
     * so this implementation has to be compatible with the
     * updater of the old AND the new version!
     * 
     * @return Response
     */
    public function status()
    {
        $timestamp = Cache::get(self::CACHE_KEY_TIMESTAMP);

        if ($timestamp === null) {
            return Response::make('-1');
        } else {
            $duration = time() - $timestamp;
            return Response::make($duration);
        }
    }

    /**
     * Shows the result of the update process.
     * Notice that this method might be called AFTER the update
     * so this implementation has to be compatible with the
     * updater of the old AND the new version!
     * 
     * @return void
     */
    public function completed()
    {
        $result = Cache::get(self::CACHE_KEY_RESULT);

        $this->alertInfo('Update process finished.', $result);
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

}