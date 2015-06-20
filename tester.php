<?php

class Tester {

    /**
     * Run the tester
     * @return void
     */
    public function run()
    {
        if (version_compare(PHP_VERSION, '5.5.9') >= 0) {
            $version = 'Yes, '.phpversion();
        } else {
            $version = $this->red('No, '.phpversion());
        }

        if (extension_loaded('mcrypt')) {
            $mCrypt = 'Yes';
        } else {
            $mCrypt = $this->red('No');
        }

        if (extension_loaded('fileinfo')) {
            $fileInfo = 'Yes';
        } else {
            $fileInfo = $this->red('No');
        }

        if (extension_loaded('pdo')) {
            $pdo = 'Yes';
        } else {
            $pdo = $this->red('No');
        }

        if (extension_loaded('mbstring')) {
            $mbstring = 'Yes';
        } else {
            $mbstring = $this->red('No');
        }

        if (extension_loaded('tokenizer')) {
            $tokenizer = 'Yes';
        } else {
            $tokenizer = $this->red('No');
        }

        if (extension_loaded('openssl')) {
            $openssl = 'Yes';
        } else {
            $openssl = $this->red('No');
        }

        if (extension_loaded('json')) {
            $json = 'Yes';
        } else {
            $json = $this->red('No');
        }

        $this->line('PHP-Version: '.$version);
        $this->line('PHP MCrypt Extension: '.$mCrypt);
        $this->line('PHP FileInfo Extension: '.$fileInfo);
        $this->line('PHP PDO Extension: '.$pdo);
        $this->line('PHP MBString Extension: '.$mbstring);
        $this->line('PHP Tokenizer Extension: '.$tokenizer);
        $this->line('PHP OpenSSL Extension: '.$openssl);
        $this->line('PHP JSON Extension Extension: '.$json);
        $this->line();

        $writableDirs = [
            'storage',
            'bootstrap/cache',
            'public/uploads',
            'public/rss',
            'public/share',
        ];

        foreach ($writableDirs as $dir) {
            if (is_writable($dir)) {
                $this->line("Directory '$dir' is writable");
            } else {
                $this->line($this->red("Directory '$dir' is writable"));
            }
        }
        $this->line();
    }

    /**
     * Color the passed text for CLI output (only in bash shells)
     * 
     * @param  string $text The text
     * @return string
     */
    protected function red($text)
    {
        return "\033[0;31m".$text."\033[0m";
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