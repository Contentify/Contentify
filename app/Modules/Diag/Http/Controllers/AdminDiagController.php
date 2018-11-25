<?php

namespace App\Modules\Diag\Http\Controllers;

use App;
use BackController;
use Carbon\Carbon;
use Config;
use DB;
use HTML;
use Jobs;

class AdminDiagController extends BackController
{

    protected $icon = 'heartbeat';
    
    public function getIndex()
    {
        if (! $this->checkAccessRead()) {
            return;
        }

        $alertIcon = HTML::fontIcon('exclamation-triangle').'&nbsp;';

        /*
         * Count disabled modules
         */
        /** @var \Caffeinated\Modules\Contracts\Repository $moduleRepo */
        $moduleRepo = app()['modules'];
        $disabled = sizeof($moduleRepo->disabled());

        /*
         * Create array with names and values
         */
        $placeholder = (Config::get('app.key') == '12345678901234567890123456789012');
        $appClass = get_class(app());
        $opcacheExists = (int) function_exists('opcache_get_status');
        $opcacheEnabled = $opcacheExists and opcache_get_status()['opcache_enabled'] ? 1 : 0;
        $diskFreeSpace = function_exists('disk_free_space') ?  round(disk_free_space('.') / 1024 / 1024).'M' : '?';
        $cronJobInfo = Jobs::lastRunAt() ?
            Carbon::createFromTimeStamp(Jobs::lastRunAt()) :
            '<b>'.$alertIcon.trans('app.no_cron_job').'</b>';

        $settings = [
            [
                'PHP.version'             => phpversion(),
                'PHP.os'                  => PHP_OS,
                'PHP.ini'                 => php_ini_loaded_file(),
                'PHP.disk_free_space'     => $diskFreeSpace,
                'PHP.memory_limit'        => ini_get('memory_limit'),
                'PHP.max_execution_time'  => ini_get('max_execution_time'),
                'PHP.post_max_size'       => ini_get('post_max_size'),
                'PHP.upload_max_filesize' => ini_get('upload_max_filesize'),
                'PHP.display_errors'      => ini_get('display_errors'),
                'PHP.file_uploads'        => ini_get('file_uploads'),
                'PHP.date.timezone'       => date_default_timezone_get(),
            ],
            [
                'Server.addr'     => $_SERVER['SERVER_ADDR'],
                'Server.name'     => $_SERVER['SERVER_NAME'],
                'Server.software' => $_SERVER['SERVER_SOFTWARE'],
            ],
            [
                'Laravel.version'     => $appClass::VERSION,
                'Jobs.last_execution' => $cronJobInfo,
            ],
            [
                'App.version'     => Config::get('app.version'),
                'App.environment' => App::environment(),
                'App.url'         => Config::get('app.url'),
                'App.debug'       => (int) Config::get('app.debug'),
                'App.key'         => $placeholder ? '<b>' . $alertIcon . trans('app.placeholder') . '</b>' : trans('app.valid'),
                'App.timezone'    => Config::get('app.timezone'),
            ],
            [
                'Cache.default'     => Config::get('cache.default'),
                'Modules.disabled'  => $disabled,
                'OPcache.installed' => $opcacheExists,
                'OPcache.enabled'   => $opcacheEnabled,
                'Xdebug.enabled'    => extension_loaded('xdebug') ? 1 : 0,
            ],
        ];

        /*
         * If we use MySQL as database, add values of some MySQL variables.
         */
        if (Config::get('database.default') == 'mysql') {
            $sqlVars = DB::select('SHOW VARIABLES'); // Returns all MySQL variables as an array of objects

            $settings[] = [
                'MySQL.max_connections'      => $this->getSqlVar($sqlVars, 'max_connections'),
                'MySQL.max_user_connections' => $this->getSqlVar($sqlVars, 'max_user_connections'),
            ];
        }
    
        $this->pageView('diag::admin_index', compact('settings'));
    }

    /**
     * Helper function. Returns the value of a variable from a MySQl variables array retrieved from the database.
     * 
     * @param  \StdClass[] $sqlVars Array of objects with variables
     * @param  string      $varName Name of a variable
     * @return mixed
     */
    protected function getSqlVar($sqlVars, $varName)
    {
        $varName = strtolower($varName);

        foreach ($sqlVars as $sqlVar) {
            if ($sqlVar->Variable_name == $varName) {
                return $sqlVar->Value;
            }
        }

        return null;
    }
    
}