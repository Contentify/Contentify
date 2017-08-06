<?php

// Includes the class + Composer autoloader + Laravel helper functions
// Note that this means that we have access to Laravel and Symfony components!
require __DIR__.'/../AppBridge.php';

class Updater {

    /**
     * The number of the version this this updater can update
     */
    const SUPPORTED_VERSIONS = ['2.0'];

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
            $errorCode = $this->$method();
        } catch (\Exception $exception) {
            $errorCode = $exception->getCode();
        }

        if ($errorCode != 0) {
            $this->print($this->createErrorText('Error: Code '.$errorCode));
        }
            
        $this->printPageEnd();

        return 0;
    }

    /**
     * Show a page with infos and ask the user to confirm the update
     * 
     * @return int Error code
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
     * @return int Error code
     */
    public function perform()
    {
        $this->print('Updating the database...');

        $appConfig = $this->app->loadConfig('app');
        $newVersion = $appConfig['version'];

        $pdo = $this->app->createDatabaseConnection();

        // TODO Use table name prefix
        $pdoStatement = $pdo->query('SELECT `value` FROM `config` WHERE `name` = "app.version"');

        if ($result === false) {
            $this->print('Could not execute the database query: '.$pdo->errorInfo()[2]);
            return $pdo->errorInfo()[0];
        }

        $currentVersion = $pdoStatement->fetchColumn();

        if (! ($currentVersion === false or (version_compare($currentVersion, $newVersion) < 0))) {
            $this->print($this->createErrorText('Error: Cannot update from version '.$currentVersion.'!'));
            return -1;
        }

        $result = $this->updateDatabase($pdo);

        if ($result === false) {            
            $this->print('Could not execute the database query: '.$pdo->errorInfo()[2]);
            return $pdo->errorInfo()[0];
        }

        $result = $pdo->query("REPLACE `config` (`name`, `value`, `updated_at`) VALUES
             ('app.version', '$newVersion', NOW())");

        if ($result === false) {
            $this->print('Could not execute the database query: '.$pdo->errorInfo()[2]);
            return $pdo->errorInfo()[0];
        }
        
        return $this->goodbye();
    }

    /**
     * This function performs all the database changes
     * 
     * @param \PDO $pdo The connection object
     * @return bool|\PDOStatement Return false if a query failed
     */
    public function updateDatabase(\PDO $pdo)
    {
        $updateQuery = "INSERT INTO `config` (`name`, `value`, `updated_at`) VALUES
        ('app.theme_christmas', '', '2017-03-25 12:28:29'),
        ('app.theme_snow_color', 'white', '2017-03-25 12:28:29');";

        return $pdo->query($updateQuery);
    }

    /**
     * Show the success message
     * 
     * @return int Error code
     */
    public function goodbye()
    {
        $appConfig = $this->app->loadConfig('app');
        $newVersion = $appConfig['version'];

        $goodbyeText = $this->goodbyeText;
        $goodbyeText = str_replace('%version%', $newVersion, $goodbyeText);
        $goodbyeText = str_replace('%file%', __FILE__, $goodbyeText);

        $this->print($goodbyeText);

        return 0;
    }

    /**
     * Creates a text snippet that will be styled as a error
     * when dsisplayed on a HTML document. (You can also
     * display it on console without styling problems.)
     * 
     * @param string $text The text of the error
     * @return string
     */
    protected function createErrorText($text)
    {
        return '<div class="error">'.$text.'</div>';
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
     * Note: Call the updater with the -quiet option
     * from the console to suppress questions.
     * 
     * @param string $title The title; will be escaped
     * @param string $step The name of the next step / method
     * @return int Error code
     */
    protected function printConfirm($title, $step)
    {
        if ($this->app->isCli()) {
            // We use Symfony's console component to ask for user input
            $input = new \Symfony\Component\Console\Input\ArgvInput();
            $output = new \Symfony\Component\Console\Output\ConsoleOutput();

            $questionHelper = new \Symfony\Component\Console\Helper\QuestionHelper();
            $question = new \Symfony\Component\Console\Question\ConfirmationQuestion(
                'Please confirm: '.$title.' [y/n]', true
            );

            $confirmed = true;
            if (! $input->hasParameterOption('-quiet')) {
                $confirmed = $questionHelper->ask($input, $output, $question);
            }

            if ($confirmed) {
                return $this->$step();
            } else {
                return -1;
            }
        } else {
            echo '<div><a class="btn" href="?step='.$step.'">'.htmlentities($title).'</a></div>';

            return 0;
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
            echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>'.$title.'</title>'.
                '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">'.
                '<style>body { margin: 20px; font-family: "Open Sans", arial; color: #666 } '.
                '.btn { display: inline-block; padding: 20px; text-decoration: none; '.
                'background-color: #00afff; color: white; font-size: 18px; border-radius: 5px } '.
                '.error { padding: 20px 0; color: #e74c3c; font-weight: bold; } </style>'.
                '</head><body>';
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