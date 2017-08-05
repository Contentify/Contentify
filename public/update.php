<?php

// Includes the class + Composer autoloader + Laravel helper functions
// Note that this means that we have access to Laravel and Symfony components!
require __DIR__.'/../AppBridge.php';

class Updater {

    /**
     * Welcome text, HTML code. Will be displayed without HTML tags
     * when on console.
     * 
     * @var string
     */
    protected $welcomeText = <<<EOD
<h1>Contentify Update</h1>
<p>
Welcome to the new version %version% of Contentify. <br>
We recommend to make a database update before you continue.
</p>
<p>
Update now?
</p>
EOD;

    /**
     * Goodbye text, HTML code. Will be displayed without HTML tags
     * when on console.
     * 
     * @var string
     */
    protected $goodbyeText = <<<EOD
<h1>Contentify updated</h1>
<p>
Done! Welcome to version %version% of Contentify! <br>
For security reasons, delete this file: <br>
%file%
</p>
EOD;

    /**
     * The app bridge object (we just call it "app" as a shortcut)
     * 
     * @var AppBridge
     */
    protected $app = null;

    public function __construct()
    {
        $this->app = new AppBridge(); 
    }

    /**
     * Main method of this class, calls other method for the different
     * steps / pages of the update process.
     * 
     * @return int Returns the error code or 0
     */
    public function run()
    {
        $this->printPageStart();

        $method = isset($_GET['step']) ? $_GET['step'] : null;
        if ($method === null) {
            $method = 'welcome';
        }

        $reflectionClass = new \ReflectionClass($this);

        // Check if method exists and calling is allowed.
        // (We use the "public" visibility to make a method callable.)
        if ($reflectionClass->hasMethod($method)) {
            $reflectionMethod = $reflectionClass->getMethod($method);
            if (! $reflectionMethod->isPublic()) {
                throw new \Exception('Given method is not accessible.');
            }
        } else {
            throw new \Exception('Given method does not exist.');
        }

        try {
            $this->$method();
            $this->printPageEnd();
        } catch (\Exception $exception) {
            return $exception->getCode();
        }

        return 0;
    }

    /**
     * Show a page with infos and ask the user to confirm the update
     * 
     * @return bool
     */
    public function welcome()
    {
        $appConfig = $this->app->loadConfig('app');
        $newVersion = $appConfig['version'];

        $welcomeText = $this->welcomeText;
        $welcomeText = str_replace('%version%', $newVersion, $welcomeText);

        $this->print($welcomeText);
        return $this->printConfirm('Update', 'perform');
    }

    /**
     * Actually performs the update
     * 
     * @return bool
     */
    public function perform()
    {
        $pdo = $this->app->createDatabaseConnection();

        // TODO implement
        
        return $this->goodbye();
    }

    /**
     * Show the success message
     * 
     * @return bool
     */
    public function goodbye()
    {
        // Note: We have to reload the config since we do not know fore sure if it has been loaded already
        $appConfig = $this->app->loadConfig('app');
        $newVersion = $appConfig['version'];

        $goodbyeText = $this->goodbyeText;
        $goodbyeText = str_replace('%version%', $newVersion, $goodbyeText);
        $goodbyeText = str_replace('%file%', __FILE__, $goodbyeText);

        $this->print($goodbyeText);

        return true;
    }

    /**
     * Prints text to a HTML document or the console
     * 
     * @param string $text HTML code or plain text
     * @return void
     */
    protected function print($text)
    {
        if ($this->app->isCli()) {
            $text = strip_tags($text);
        } 

        echo $text;
    }

    /**
     * Prints a HTML link on a HTML coument or 
     * directly asks for confirmation when on console
     * 
     * @param string $title The title; will be escaped
     * @param string $step The name of the next step / method
     * @return bool|null
     */
    protected function printConfirm($title, $step)
    {
        if ($this->app->isCli()) {
            // We use Symfony's console component to ask for user input
            $input = new \Symfony\Component\Console\Input\StringInput('');
            $output = new \Symfony\Component\Console\Output\ConsoleOutput();

            $questionHelper = new \Symfony\Component\Console\Helper\QuestionHelper();
            $question = new \Symfony\Component\Console\Question\ConfirmationQuestion(
                'Pleae confirm: '.$title.' [y/n]', true
            );

            $confirmed = $questionHelper->ask($input, $output, $question);

            if ($confirmed) {
                $this->$step();
            } else {
                return -1;
            }
        } else {
            echo '<div><a class="btn" href="?step='.$step.'">'.htmlentities($title).'</a></div>';

            return null;
        }
    }

    /**
     * Prints the start of a "page".
     * ATTENTION: Do not forget to also call printPageEnd()!
     * 
     * @param $title Title of the page (only relevant for HTML documents)
     * @return void
     */
    protected function printPageStart($title = __CLASS__)
    {
        if ($this->app->isCli()) {
            echo "\n\n";
        } else {
            echo '<!DOCTYPE html><html><head>'.
                '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">'.
                '<style>body { margin: 20px; font-family: "Open Sans", arial; color: #666 } '.
                '.btn { display: inline-block; padding: 20px; text-decoration: none; '.
                'background-color: #00afff; color: white; font-size: 18px; border-radius: 5px }</style>'.
                '<title>'.$title.'</title></head><body>';
        }
    }

    /**
     * Prints the end of a "page".
     * 
     * @return void
     */
    protected function printPageEnd()
    {
        if ($this->app->isCli()) {
            echo "\n";
        } else {
            echo '</body></html>';
        }
    }

}

/*
|--------------------------------------------------------------------------
| Contentify Updater
|--------------------------------------------------------------------------
*/

$updater = new Updater;

$updater->run();