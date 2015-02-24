<?php

class Tester {

    /**
     * Array with paths
     * @var array
     */
    protected $paths = array();

    public function __construct()
    {
        $this->paths = require __DIR__.'/bootstrap/paths.php';
    }

    /**
     * Run the tester
     * @return void
     */
    public function run()
    {
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            $version = 'Yes, '.phpversion();
        } else {
            $version = 'No, '.phpversion();
        }

        if (extension_loaded('mcrypt')) {
            $mCrypt = 'Yes';
        } else {
            $mCrypt = 'No';
        }

        if (extension_loaded('fileinfo')) {
            $fileInfo = 'Yes';
        } else {
            $fileInfo = 'No';
        }

        $this->line('PHP-Version: '.$version);
        $this->line('PHP MCrypt Extension: '.$mCrypt);
        $this->line('PHP FileInfo Extension: '.$fileInfo);
        $this->line();

        $paths = $this->paths;
        $writableDirs = [
            $paths['app'].'/storage', 
            $paths['public'].'/uploads', 
            $paths['public'].'/rss',
            $paths['public'].'/share',
        ];

        foreach ($writableDirs as $dir) {
            if (is_writable($dir)) {
                $this->line($dir.' is writable');
            } else {
                $this->line($dir.' is not writable');
            }
        }
        $this->line();
    }

    /**
     * Echo this line
     * 
     * @param  string $text The line of text
     * @return void
     */
    protected function line($text = null)
    {
        if ($this->isCli()) {
            echo $text." \n";    
        } else {
            echo $text.'<br>';
        }        
    }

    /**
     * Determine if PHP is being run from the CLI
     * 
     * @return boolean
     */
    protected function isCli()
    {
        return (php_sapi_name() === 'cli');
    }

}

(new Tester)->run();