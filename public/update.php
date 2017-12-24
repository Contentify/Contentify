<?php

// Includes the class + Composer autoloader + Laravel helper functions
// Note that this means that we have access to Laravel and Symfony components.
require __DIR__.'/../AppBridge.php';

class Updater {

    /**
     * The number of the version this this updater can update
     */
    const SUPPORTED_VERSIONS = ['2.1'];

    /**
     * Error code that will be returned if there was no error
     */
    const CODE_OKAY = 0;

    /**
     * Error code that will be returned if the user aborted the update process
     */
    const CODE_ABORTED = -1;

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
     * @return int Returns the error code or self::CODE_OKAY
     * @throws Exception
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

        if ($errorCode != self::CODE_OKAY) {
            $this->printText($this->createErrorText('Error: Code '.$errorCode));
        }
            
        $this->printPageEnd();

        return self::CODE_OKAY;
    }

    /**
     * Show a page with infos and ask the user to confirm the update
     * 
     * @return int Error code
     */
    public function welcome()
    {
        $appConfig = $this->app->getConfig('app');
        $newVersion = $appConfig['version'];

        $welcomeText = $this->welcomeText;
        $welcomeText = str_replace('%version%', $newVersion, $welcomeText);

        $this->printText($welcomeText);
        return $this->printConfirm('Update', 'perform');
    }

    /**
     * Actually performs the update
     * 
     * @return int Error code
     */
    public function perform()
    {
        $this->printText('Updating the database...');

        $appConfig = $this->app->getConfig('app');
        $newVersion = $appConfig['version'];

        $connectionDetails = $this->app->getDatabaseConnectionDetails();
        $prefix = isset($connectionDetails['prefix']) ? $connectionDetails['prefix'] : '';

        $pdo = $this->app->createDatabaseConnection();

        $pdoStatement = $pdo->query('SELECT `value` FROM `'.$prefix.'config` WHERE `name` = "app.version"');

        if ($pdoStatement === false) {
            $this->printText('Could not execute the database query: '.$pdo->errorInfo()[2]);
            return $pdo->errorInfo()[0];
        }

        $currentVersion = $pdoStatement->fetchColumn();

        $versionsText = implode(', ', self::SUPPORTED_VERSIONS) ;
        if (! ($currentVersion === false or (version_compare($currentVersion, $newVersion) < 0))) {
            $this->printText($this->createErrorText(
                'Error: Cannot update from version '.$currentVersion.', only from one of these: '.$versionsText)
            );
            return self::CODE_ABORTED;
        }
        if ($currentVersion !== false and ! in_array($currentVersion, self::SUPPORTED_VERSIONS)) {
            $this->printText($this->createErrorText(
                'Error: Cannot update from version '.$currentVersion.', only from one of these: '.$versionsText)
            );
            return self::CODE_ABORTED;
        }

        $result = $this->updateDatabase($pdo, $prefix);

        if ($result === false) {
            $this->printText('Could not execute the database query: '.$pdo->errorInfo()[2]);
            return $pdo->errorInfo()[0];
        }

        $result = $pdo->query("REPLACE `{$prefix}config` (`name`, `value`, `updated_at`) VALUES
            ('app.version', '$newVersion', NOW())");

        if ($result === false) {
            $this->printText('Could not execute the database query: '.$pdo->errorInfo()[2]);
            return $pdo->errorInfo()[0];
        }
        
        return $this->goodbye();
    }

    /**
     * This function performs all the database changes
     * 
     * @param \PDO $pdo The connection object
     * @param string $prefix The database table prefix (can be an empty string)
     * @return bool|\PDOStatement Return false if a query failed
     */
    public function updateDatabase(\PDO $pdo, $prefix)
    {
        // HOW TO: Export the new database - for example via phpMyAdmin -
        // and then copy the relevant statements from the .sql file to this place
        $updateQuery = "ALTER TABLE {$prefix}cups_teams ADD cup_points INT DEFAULT 0;
            ALTER TABLE {$prefix}users ADD cup_points INT DEFAULT 0;
            INSERT INTO `{$prefix}config` (`name`, `value`, `updated_at`) VALUES
            ('cups::cup_points', 10, null);
            ALTER TABLE {$prefix}servers ADD description TEXT NULL;";

        return $pdo->query($updateQuery);
    }

    /**
     * Show the success message
     * 
     * @return int Error code
     */
    public function goodbye()
    {
        $appConfig = $this->app->getConfig('app');
        $newVersion = $appConfig['version'];

        $goodbyeText = $this->goodbyeText;
        $goodbyeText = str_replace('%version%', $newVersion, $goodbyeText);
        $goodbyeText = str_replace('%file%', __FILE__, $goodbyeText);

        $this->printText($goodbyeText);

        return self::CODE_OKAY;
    }

    /**
     * Creates a text snippet that will be styled as a error
     * when displayed on a HTML document. (You can also
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
    protected function printText($text)
    {
        if ($this->app->isCli()) {
            $text = strip_tags($text);
        } 

        echo $text;
    }

    /**
     * Prints a HTML link on a HTML document or
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
                return self::CODE_ABORTED;
            }
        } else {
            echo '<div><a class="btn" href="?step='.$step.'">'.htmlentities($title).'</a></div>';

            return self::CODE_OKAY;
        }
    }

    /**
     * Prints the start of a "page".
     * ATTENTION: Do not forget to also call printPageEnd()!
     * 
     * @param string $title Title of the page (only relevant for HTML documents)
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
