<?php

namespace App\Modules\Diag\Http\Controllers;

use \Carbon\Carbon;
use App, Config, View, BackController, File, DB, PDO;

class AdminDiagController extends BackController {

    protected $icon = 'info-circle';
    
    public function getIndex()
    {
        if (! $this->checkAccessRead()) return;

        /*
         * Check if Laravel is compiled to one single file
         */
        $filename = app('path.base').'/bootstrap/cache/compiled.php';
        if (File::exists($filename)) {
            $optimized = '1 - '.trans('app.compiled').': '.Carbon::createFromTimeStamp(filemtime($filename));
        } else {
            $optimized = 0;
        }  

        /*
         * Count disabled modules
         */
        $moduleBase = app()['modules'];
        $disabled = sizeof($moduleBase->disabled());

        /*
         * Create array with names and values
         */
        $placeholder = (Config::get('app.key') == '01234567890123456789012345678912');
        $appClass = get_class(app());
        $opcacheExists = (int) function_exists('opcache_get_status');
        $opcacheEnabled = $opcacheExists and opcache_get_status()['opcache_enabled'] ? 1 : 0;
        $diskFreeSpace = function_exists('disk_free_space') ?  round(disk_free_space('.') / 1024 / 1024).'M' : '?';

        $settings = [
            'PHP.version'               => phpversion(),
            'PHP.os'                    => PHP_OS,
            'PHP.ini'                   => php_ini_loaded_file(),
            'PHP.disk_free_space'       => $diskFreeSpace,
            'PHP.memory_limit'          => ini_get('memory_limit'),
            'PHP.max_execution_time'    => ini_get('max_execution_time'),
            'PHP.post_max_size'         => ini_get('post_max_size'),
            'PHP.upload_max_filesize'   => ini_get('upload_max_filesize'),
            'PHP.date.timezone'         => date_default_timezone_get(),
            'Server.addr'               => $_SERVER['SERVER_ADDR'],
            'Server.name'               => $_SERVER['SERVER_NAME'],
            'Server.software'           => $_SERVER['SERVER_SOFTWARE'],
            'Laravel.version'           => $appClass::VERSION,
            'Artisan optimized'         => $optimized,
            'App.environment'           => App::environment(),
            'App.url'                   => Config::get('app.url'),
            'App.debug'                 => (int) Config::get('app.debug'),
            'App.key'                   => $placeholder ? '<b>'.trans('app.placeholder').'</b>' : trans('app.valid'),
            'App.timezone'              => Config::get('app.timezone'),
            'Cache.default'             => Config::get('cache.default'),
            'Modules.disabled'          => $disabled,
            'Mail.pretend'              => (int) Config::get('mail.pretend'),
            'OPcache.installed'         => $opcacheExists,
            'OPcache.enabled'           => $opcacheEnabled,
            'Xdebug.enabled'            => extension_loaded('xdebug') ? 1 : 0,
        ];

        /*
         * If we use MySQL as database, add values of some MySQL variables.
         */
        if (Config::get('database.default') == 'mysql') {
            $fetchMethod = Config::get('database.fetch');
            DB::connection()->setFetchMode(PDO::FETCH_ASSOC); // We need to get the result as array of arrays 
            $sqlVars = DB::select('SHOW VARIABLES');
            DB::connection()->setFetchMode($fetchMethod);

            $settings['MySQL.max_connections']      = $this->getSqlVar($sqlVars, 'max_connections');
            $settings['MySQL.max_user_connections'] = $this->getSqlVar($sqlVars, 'max_user_connections');
        }
    
        $this->pageView('diag::admin_index', compact('settings'));
    }

    /**
     * Helper function. Returns the value of a variable from a MySQl variables array retrieved from the database.
     * 
     * @param  array    $vars    Array of variables
     * @param  string   $varName Name of variable
     * @return mixed
     */
    protected function getSqlVar($vars, $varName)
    {
        $varName = strtolower($varName);

        foreach ($vars as $var) {
            if ($var['Variable_name'] == $varName) {
                return $var['Value'];
            }
        }

        return null;
    }
    
}