<?php

namespace App\Modules\Config\Http\Controllers;

use App\Modules\Config\SettingsBag;
use Artisan;
use BackController;
use Config;
use Contentify\Vendor\MySqlDump;
use DB;
use File;
use HTML;
use Input;
use Redirect;
use Str;

class AdminConfigController extends BackController
{

    /**
     * Path and file name of the log file.
     * Note that changing this constant won't
     * change where Monolog creates the file.
     */
    const LOG_FILE = '/logs/laravel.log';

    protected $icon = 'cog';
    
    public function getIndex()
    {
        if (! $this->checkAccessRead()) {
            return;
        }

        $settings = DB::table('config')->get();

        $settingsBag = new SettingsBag; // This is some kind of a helper model to store settings

        // We have to loop over all settings and check if they are listed as fillable:
        foreach ($settings as $setting) {
            $settingName = str_replace('app.', 'app::', $setting->name);
            if (in_array($settingName, $settingsBag->getFillable())) {
                $settingsBag->$settingName = $setting->value;
            }
        }

        /** @var \Caffeinated\Modules\Contracts\Repository $moduleRepo */
        $moduleRepo = app()['modules'];
        $modules = $moduleRepo->all(); // Retrieve all module info objects

        $themes = [];
        foreach ($modules as $name => $module) {
            if (! $module['enabled']) {
                continue;
            }

            if (isset($module['theme']) and $module['theme']) {
                $module['activeTheme'] = (Config::get('app.theme') === $name);
                $module['preview'] = load_image_encoded(Config::get('modules.path').'/'.$name.'/preview.png');
                $themes[$name] = $module;
            }
        }

        $this->pageView('config::admin_index', compact('settingsBag', 'themes'));
    }

    /**
     * Updates the settings.
     * Note that we have to create the parameter $id even though we won't use it:
     * The update method inherits from BackController->update($id).
     * We allow $id to be null so we do not have to pass an argument.
     *
     * @param mixed $id Unused parameter
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id = null)
    {
        if (! $this->checkAccessUpdate()) {
            return null;
        }

        $settingsBag = new SettingsBag;
        $settingsBag->fill(Input::all());

        if (! $settingsBag->isValid()) {
            return Redirect::to('admin/config')
                ->withInput()->withErrors($settingsBag->getErrors());
        }

        $oldTheme = Config::get('app.theme');
        if ($oldTheme !== $settingsBag['app::theme']) {
            Artisan::call('vendor:publish', ['--tag' => [lcfirst($settingsBag['app::theme'])], '--force' => true]);
            HTML::refreshAssetPaths();
        }

        $oldFrontendCssCode = Config::get('app.frontend_less_code');
        if ($oldFrontendCssCode !== $settingsBag['app::frontend_less_code']) {
            File::put(storage_path('app/frontend_less_code.less'), $settingsBag['app::frontend_less_code']);
        }

        $oldBackendLessCode = Config::get('app.backend_less_code');
        if ($oldBackendLessCode !== $settingsBag['app::backend_less_code']) {
            File::put(storage_path('app/backend_less_code.less'), $settingsBag['app::backend_less_code']);
        }

        // Save the settings one by one:
        foreach ($settingsBag->getFillable() as $settingName) {
            $settingRealName = str_replace('app::', 'app.', $settingName);
            
            $result = DB::table('config')->whereName($settingRealName)
                ->update(['value' => $settingsBag->$settingName, 'updated_at' => DB::raw('NOW()')]);

            /*
             * If the key does not exist we need to create it.
             * $result contains the number of affected rows.
             * With using a timestamp we ensure that when updating a value
             * the row is always affected, even though if the value does not change.
             */
            if ($result == 0) {
                DB::table('config')->insert(array(
                    'name'       => $settingRealName,
                    'value'      => $settingsBag->$settingName,
                    'updated_at' => DB::raw('NOW()'))
                );
            }

            Config::clearCache($settingRealName);
        }

        $this->alertFlash(trans('app.updated', [$this->controllerName]));
        return Redirect::to('admin/config');
    }

    /**
     * This action method executes the phpinfo() command.
     * It uses a dirty hack to override the CSS classes phpinfo() uses.
     *
     * @return void
     */
    public function getInfo()
    {
        if (! $this->checkAccessRead()) {
            return;
        }

        ob_start();

        // We tell phpinfo() what info to show, so we avoid to show the PHP credits:
        phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES | INFO_ENVIRONMENT | INFO_VARIABLES); 
        
        preg_match('%<style type="text/css">(.*?)</style>.*?(<body>.*</body>)%s', ob_get_clean(), $matches);
        
        $this->pageOutput('<div class="phpinfodisplay"><style type="text/css">'."\n".
             implode("\n",
                 array_map(
                     function($item) {
                         return ".phpinfodisplay " . preg_replace("/,/", ",.phpinfodisplay ", $item);
                     },
                     preg_split('/\n/', $matches[1]) // $matches[1] = style information
                     )
                 ).
             "{}\n
             .phpinfodisplay { overflow-x: scroll }\n
             .phpinfodisplay td,.phpinfodisplay  th { border: 1px solid silver; overflow-wrap: break-word; }\n
             .phpinfodisplay .h { background-color: #DDD }\n
             .phpinfodisplay .e { background-color: #EEE }\n
             .phpinfodisplay .v { background-color: white }\n
             .phpinfodisplay table { width: 100%; box-shadow: none; }</style>\n". // Override the classes
             $matches[2]. // $matches[2] = body information
             "\n</div>\n");
    }

    /**
     * Optimize Database
     * 
     * @return void
     */
    public function getOptimize()
    {
        if (! $this->checkAccessUpdate()) {
            return;
        }

        switch (Config::get('database.default')) { // Retrieve the default database type from the config
            case 'mysql':
                $tables = DB::select('SHOW TABLES');
                foreach ($tables as $table) {
                    DB::select('OPTIMIZE TABLE '.current($table));
                }
                break;
            default:
                $this->alertError(trans('config::not_supported', [Config::get('database.default')]));
                return;
        }

        $this->alertSuccess(trans('app.successful'));
    }

    /**
     * Create MySQL dump
     * 
     * @return void
     */
    public function getExport()
    {
        if (! $this->checkAccessRead()) {
            return;
        }

        switch (Config::get('database.default')) { // retrieve the default database type from the config
            case 'mysql':
                $dump = new MySqlDump();

                $con        = Config::get('database.connections.mysql');
                $dateTime   = date('M-d-Y_H-i');
                $filename   = storage_path().'/database/'.$dateTime.'.sql';

                $dump->host     = $con['host'];
                $dump->user     = $con['username'];
                $dump->pass     = $con['password'];
                $dump->db       = $con['database'];
                $dump->filename = $filename;
                $dump->start();

                $this->alertSuccess(
                    trans('config::db_export'), 
                    trans('config::db_file', [$filename]));
                break;
            default:
                $this->alertError(trans('config::not_supported', [Config::get('database.default')]));
                return;
        }
    }

    /**
     * Compiles the LESS files to CSS files
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getCompileLess()
    {
        if (! $this->checkAccessUpdate()) {
            return;
        }

        try {
            Artisan::call('less:compile');
        } catch (\Exception $exception) {
            $this->alertError($exception->getMessage());
            return;
        }

        $this->alertFlash(trans('app.successful'));
        return Redirect::to('admin/config');
    }

    /**
     * Clear the cache
     *
     * @return void
     */
    public function getClearCache()
    {
        if (! $this->checkAccessUpdate()) {
            return;
        }

        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        $this->alertSuccess(trans('app.successful'));
    }

    /**
     * Show the log file content
     * 
     * @return void
     */
    public function getLog()
    {
        if (! $this->checkAccessRead()) {
            return;
        }

        $filename = storage_path().self::LOG_FILE;
        if (File::exists($filename)) {
            $lines    = file($filename);
            $content  = '';

            foreach ($lines as $line) {
                // Escape the whole line, because it might contain HTML tags
                $line = e($line);

                // Wrap the environment + error info in a HTML element
                $line = preg_replace('/\] (\w+)\.(\w+):/', '] <strong class="level-$2">$1.$2</strong>:', $line);

                // Wrap stack counters in labels
                $line = preg_replace('/^#\d+/', '<span class="label label-default">$0</span>', $line);

                // Wrap stack beginning in a HTML tag
                if ($line === "Stack trace:\n") {
                    $line = '<em>'.$line.'</em>';
                }

                // New log entries start with a [...
                if (Str::startsWith($line, '[')) {
                    if ($content) {
                        $content .= '</div></div>';
                    }
                    $line = substr($line, 0, 21).'</span>'.substr($line, 21);
                    $content .= '<div class="item"><span class="date">'.$line.'<div class="stack">';
                } else { // ...the "Stack trace:" entry and the stack entries do not
                    $content .= '<div class="stack-line">'.$line.'</div>';
                }
            }

            if ($content) {
                $content .= '</div></div>';
            }

            $size = File::size($filename);

            $this->pageView('config::show_log', compact('content', 'size'));
        } else {
            $this->alertInfo(trans('config::log_empty'));
        }
    }

    /**
     * Show the log file content
     *
     * @return null|string
     */
    public function getPlainLog()
    {
        if (! $this->checkAccessRead()) {
            return;
        }

        $filename = storage_path().self::LOG_FILE;
        if (File::exists($filename)) {
            return response()->file($filename);
        } else {
            $this->alertInfo(trans('config::log_empty'));
        }
    }

    /**
     * Delete the log file
     * 
     * @return void
     */
    public function clearLog()
    {
        if (! $this->checkAccessUpdate()) {
            return;
        }

        $filename = storage_path().self::LOG_FILE;

        if (File::exists($filename)) {
            File::delete($filename);
        }

        $this->alertSuccess(trans('app.deleted', [$filename]));
    }

}
