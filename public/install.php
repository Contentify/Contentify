<?php

class Tester {

    /**
     * Run the tester
     * 
     * @return void
     */
    public function run()
    {
        if ($this->checkPhp()) {
            $version = 'Yes, '.phpversion();
        } else {
            $version = $this->red('No, '.phpversion());
        }
        $this->line('PHP-Version: '.$version);

        $results = $this->checkExtensions();

        foreach ($results as $extension => $okay) { 
            if ($okay) {
                $info = 'Yes';
            } else {
                $info = $this->red('No');
            }
            $this->line('PHP '.ucfirst($extension).' Extension: '.$info);
        }

        $this->line();

        $results = $this->checkDirs();     

        foreach ($results as $dir => $okay) {
            if ($okay) {
                $this->line("Directory '$dir' is writable");
            } else {
                $this->line($this->red("Directory '$dir' is writable"));
            }
        }
        $this->line();
    }

    /**
     * Checks if the PHP version is correct
     * 
     * @return boolean
     */
    public function checkPhp()
    {
        return (version_compare(PHP_VERSION, '5.5.9') >= 0);
    }

    /**
     * Returns a list of PHP extensions names and their state
     * 
     * @return array
     */
    public function checkExtensions()
    {
        $extensions = [
            'mcrypt',
            'fileinfo',
            'pdo',
            'mbstring',
            'tokenisszer',
            'openssl',
            'json,'
        ];

        $results = [];

        foreach ($extensions as $extension) {
            $results[$extension] = extension_loaded($extension);
        }

        return $results;
    }

    /**
     * Returns a list of dir names and their state
     * 
     * @return array
     */
    public function checkDirs()
    {
        $dirs = [
            'storage',
            'bootstrap/cache',
            'public/uploads',
            'public/rss',
            'public/share',
        ];

        $results = [];

        foreach ($dirs as $dir) {
            $results[$dir] = is_writable(__DIR__.'/../'.$dir);
        }

        return $results;
    }

    /**
     * Color the passed text for CLI output (only in bash shells)
     * 
     * @param  string $text The text
     * @return string
     */
    protected function red($text)
    {
        if ($this->isCli()) {
            return "\033[0;31m".$text."\033[0m";
        }
        return '<span style="color: red">'.$text.'</span>';
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
    public function isCli()
    {
        return (php_sapi_name() === 'cli');
    }

}

/*
|--------------------------------------------------------------------------
| Contentify Installation Launcher
|--------------------------------------------------------------------------
*/

$tester = new Tester;

if (! file_exists(__DIR__.'/../storage/app/.install') and ! $tester->isCli()) {
    die('Contentify already has been installed.');  
}

$tester->run();

if ($tester->isCli()) {
    die('The installer cannot be launched from a console. Please navigate to the website with a browser to install Contentify.');
} else {
    echo '<a href="./install" style="display: block; text-decoration: none; color: #00afff">Launch Installer</a>';
}