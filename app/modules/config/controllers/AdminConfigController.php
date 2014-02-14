<?php namespace App\Modules\Config\Controllers;

use Contentify\Vendor\MySqlDump as MySqlDump;
use File, DB, Config, View, BackController;

class AdminConfigController extends BackController {

    protected $icon = 'cog.png';
    
    public function getIndex()
    {
        if (! $this->checkAccessRead()) return;

        $this->pageView('config::admin_index');
    }

    /**
     * This action method shows the phpinfo() command.
     * It uses a dirty hack to override the CSS classes phpinfo() uses.
     */
    public function getInfo()
    {
        if (! $this->checkAccessRead()) return;

        ob_start();

        // We tell phpinfo() what infos to show, so we avoid to show the PHP credits:
        phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES | INFO_ENVIRONMENT | INFO_VARIABLES); 
        
        preg_match('%<style type="text/css">(.*?)</style>.*?(<body>.*</body>)%s', ob_get_clean(), $matches);
        
        $this->pageOutput('<div class="phpinfodisplay"><style type="text/css">'."\n".
             join("\n",
                 array_map(
                     create_function(
                         '$i',
                         'return ".phpinfodisplay " . preg_replace( "/,/", ",.phpinfodisplay ", $i );'
                         ),
                     preg_split('/\n/', $matches[1]) // $matches[1] = style information
                     )
                 ).
             "{}\n
             .phpinfodisplay { overflow-x: scroll }\n
             .phpinfodisplay td,.phpinfodisplay  th { border: 1px solid silver; overflow-wrap: break-word; }\n
             .phpinfodisplay .h { background-color: #DDD }\n
             .phpinfodisplay .e { background-color: #EEE }\n
             .phpinfodisplay .v { background-color: white }</style>\n". // Override the classes
             $matches[2]. // $matches[2] = body information
             "\n</div>\n");
    }

    public function getOptimize()
    {
        if (! $this->checkAccessUpdate()) return;

        switch (Config::get('database.default')) { // Retrieve the default database type from the config
            case 'mysql':
                $tables = DB::select('SHOW TABLES');
                foreach ($tables as $table) {
                    DB::select('OPTIMIZE TABLE '.current($table));
                }
                break;
            default:
                $this->message(t('Sorry, "'.Config::get('database.default').'" does not support this feature.'));
                return;
        }

        $this->message(t('Database optimized.'));
    }

    public function getExport()
    {
        if (! $this->checkAccessRead()) return;

        switch (Config::get('database.default')) { // retrieve the default database type from the config
            case 'mysql':
                $dump = new MySqlDump();

                $con = Config::get('database.connections.mysql');
                $time = time();
                $filename = storage_path().'/database/'.$time.'.sql';

                $dump->host     = $con['host'];
                $dump->user     = $con['username'];
                $dump->pass     = $con['password'];
                $dump->db       = $con['database'];
                $dump->filename = $filename;
                $dump->start();

                $this->message(
                    t('Die Datenbank wurde exportiert.'), 
                    t('Eine .sql-Datei wurde auf dem Webspace angelegt.'));
                break;
            default:
                $this->message(t('Sorry, "'.Config::get('database.default').'" does not support this feature.'));
                return;
        }
    }

    public function getLog()
    {
        if (! $this->checkAccessRead()) return;

        $fileName = storage_path().'/logs/laravel.log';
        if (File::exists($fileName))
        {
            $content = File::get($fileName, t('The log is empty.'));
            $this->pageOutput('<pre style="overflow: scroll">'.$content.'</pre>');
        } else {
            $this->message(t('The log is empty.'));
        }
    }

}