<?php namespace App\Modules\Diag\Http\Controllers;

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
        $filename = app('path.base').'/bootstrap/compiled.php';
        if (File::exists($filename)) {
            $optimized = '1 - '.trans('diag::compiled').': '.Carbon::createFromTimeStamp(filemtime($filename));
        } else {
            $optimized = 0;
        }  

        /*
         * Create array with names and values
         */
        $isPlacehoder = (Config::get('app.key') == '01234567890123456789012345678912');
        $appClass = get_class(app());
        $opcacheExists = (int) function_exists('opcache_get_status');
        $opcacheEnabled = $opcacheExists and opcache_get_status()['opcache_enabled'] ? 1 : 0;
        $settings = [
            'Laravel.version'           => $appClass::VERSION,
            'Artisan optimized'         => $optimized,
            'App.environment'           => App::environment(),
            'App.url'                   => Config::get('app.url'),
            'App.debug'                 => (int) Config::get('app.debug'),
            'App.key'                   => $isPlacehoder ? trans('diag::placeholder') : trans('app.valid'),
            'Cache.driver'              => Config::get('cache.driver'),
            'Modules.mode'              => Config::get('modules::mode'),
            'Modules.debug'             => (int) Config::get('modules::debug'),
            'Mail.pretend'              => (int) Config::get('mail.pretend'),
            'OPcache.installed'         => $opcacheExists,
            'OPcache.enabled'           => $opcacheEnabled,
            'PHP.memory_limit'          => ini_get('memory_limit'),
            'PHP.max_execution_time'    => ini_get('max_execution_time'),
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