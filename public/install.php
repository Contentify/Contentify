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
            $version = $this->green('Yes, '.phpversion());
        } else {
            $version = $this->red('No, '.phpversion());
        }
        $this->line('PHP-Version: '.$version);
        $this->line();

        $results = $this->checkExtensions();

        foreach ($results as $extension => $okay) { 
            if ($okay) {
                $info = $this->green('Yes');
            } else {
                $info = $this->red('No');
            }
            $this->line('PHP '.ucfirst($extension).' Extension: '.$info);
        }

        $this->line();

        $results = $this->checkDirs();     

        foreach ($results as $dir => $okay) {
            if ($okay) {
                $this->line("Directory '$dir': ".$this->green('Writable'));
            } else {
                $this->line("Directory '$dir': ".$this->red('Not writable'));
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
            'tokenizer',
            'openssl',
            'json',
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
     * Color the passed text for output
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
     * Color the passed text for output
     * 
     * @param  string $text The text
     * @return string
     */
    protected function green($text)
    {
        if ($this->isCli()) {
            return "\033[0;32m".$text."\033[0m";
        }
        return '<span style="color: green">'.$text.'</span>';
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

if ($tester->isCli()) {
    $tester->run();

    echo "The installer cannot be launched from a console.\n\r";
    echo 'Please navigate to the website with a browser to install Contentify.';
} else {
    if (! file_exists(__DIR__.'/../storage/app/.install')) {
        die('Contentify already has been installed.');  
    }

    echo '<html><head><style>body { margin: 20px; font-family: arial }</style></head><body>';
    $tester->run();
    echo '<a href="./install" style="display: block; text-decoration: none; color: #00afff">Launch Installer</a>';
    echo '</body></html>';
}