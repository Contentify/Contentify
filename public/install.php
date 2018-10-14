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

        $results = $this->checkFunctions();

        foreach ($results as $function => $okay) {
            if ($okay) {
                $info = $this->green('Yes');
            } else {
                $info = $this->red('No');
            }
            $this->line('PHP '.ucfirst($function).' Function: '.$info);
        }

        $this->line();

        if (is_dir(__DIR__.'/../vendor')) {
            $this->line('Vendor libraries installed: '.$this->green('Yes'));
        } else {
            $this->line('Vendor libraries installed: '.$this->red('No - please run "composer install"!'));
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

        $apacheSig = 'Apache/';
        if (substr($_SERVER['SERVER_SOFTWARE'], 0, strlen($apacheSig)) === $apacheSig) {
            $this->line('Webserver: '.$this->green('Apache'));

            $modRewrite = in_array('mod_rewrite', apache_get_modules());
            if ($modRewrite) {
                $this->line('mod_rewrite enabled: '.$this->green('Yes'));
            } else {
                $this->line('mod_rewrite enabled: '.$this->red('No - please enable it via "sudo a2enmod rewrite"!'));
            }

            $this->line();
        }
    }

    /**
     * Checks if the PHP version is correct
     *
     * @return boolean
     */
    public function checkPhp()
    {
        return (version_compare(PHP_VERSION, '5.6.4') >= 0);
    }

    /**
     * Returns a list of PHP extensions names and their state
     *
     * @return bool[]
     */
    public function checkExtensions()
    {
        $extensions = array(
            'fileinfo',
            'pdo',
            'mbstring',
            'tokenizer',
            'openssl',
            'json',
            'curl',
            'xml'
        );

        $results = array();

        foreach ($extensions as $extension) {
            $results[$extension] = extension_loaded($extension);
        }

        return $results;
    }

    /**
     * Returns a list of PHP function names and their state
     *
     * @return bool[]
     */
    public function checkFunctions()
    {
        $functions = array(
            'escapeshellarg',
        );

        $results = array();

        foreach ($functions as $function) {
            $results[$function] = function_exists($function);
        }

        return $results;
    }

    /**
     * Returns a list of dir names and their state
     *
     * @return bool[]
     */
    public function checkDirs()
    {
        $dirs = array(
            'storage',
            'bootstrap/cache',
            'public',
        );

        $results = array();

        foreach ($dirs as $dir) {
            $results[$dir] = is_writable(__DIR__.'/../'.$dir);
        }

        return $results;
    }

    /**
     * Color the passed text for output
     *
     * @param string $text The text
     * @return string
     */
    protected function red($text)
    {
        if ($this->isCli()) {
            return "\033[0;31m".$text."\033[0m";
        }
        return '</td><td><span style="color: #e74c3c">'.$text.'</span>';
    }

    /**
     * Color the passed text for output
     *
     * @param string $text The text
     * @return string
     */
    protected function green($text)
    {
        if ($this->isCli()) {
            return "\033[0;32m".$text."\033[0m";
        }
        return '</td><td><span style="color: #2ecc71">'.$text.'</span>';
    }

    /**
     * Echo this line
     *
     * @param string $text The line of text
     * @return void
     */
    protected function line($text = null)
    {
        if ($this->isCli()) {
            echo $text." \n";
        } else {
            echo '<tr><td>'.$text.'&nbsp;</td></tr>';
        }
    }

    /**
     * Determine if PHP is being run from the CLI
     * 
     * @return bool
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
    $filename = 'storage/app/.installed';
    if (file_exists(__DIR__.'/../'.$filename)) {
        die('Contentify has already been installed. Remove this file if you want to reinstall it: '.$filename);  
    }

    echo '<html><head><meta charset="utf-8"><title>Installer</title>
        <link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
        <style>body { margin: 20px; font-family: "Open Sans", arial; color: #666 }</style></head><body><table>';
    $tester->run();
    echo '</table><a href="./install" style="display: inline-block; padding: 20px; text-decoration: none; 
        background-color: #00afff; color: white; font-size: 18px; border-radius: 5px">Launch Installer</a>';
    echo '</body></html>';
}
