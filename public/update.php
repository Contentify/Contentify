<?php

// Includes the class + Composer autoloader + Laravel helper functions
// Note that this means that we have access to Laravel and Symfony components.
require __DIR__.'/../AppBridge.php';

class Updater {

    /**
     * The number of the version this updater can update
     */
    const SUPPORTED_VERSIONS = ['2.5'];

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
Welcome to the update to version %version% of Contentify.
</p>
<p>
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
Done! Welcome to version %version% of Contentify!
</p>
<p>
For security reasons, delete this file: <br>
<code>%file%</code>
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
     * This method has to return a string array with queries that perform all the database changes
     *
     * @param string $prefix The database table prefix (can be an empty string)
     * @return string[]
     */
    public function updateDatabase($prefix = '')
    {
        $forbiddenEmailDomains = 'example.com, example.org';

        // How to create these statements: Export the new database - for example via phpMyAdmin -
        // and then copy the relevant statements from the .sql file to this place.
        // ATTENTION: Afterwards add {$prefix} before all table names!!
        $updateQueries = [
            "INSERT INTO `{$prefix}languages`(`title`, `code`) VALUES ('Spanish','es');",

            "INSERT INTO `{$prefix}config` 
                (`name`, `value`) 
                VALUES ('app.forbidden_email_domains', '$forbiddenEmailDomains');",

            "CREATE TABLE `{$prefix}polls` (
                `id` int(10) UNSIGNED NOT NULL,
                `title` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
                `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                `open` tinyint(1) NOT NULL DEFAULT '1',
                `internal` tinyint(1) NOT NULL DEFAULT '0',
                `max_votes` int(11) NOT NULL,
                `option1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `option2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `option3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `option4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `option5` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `option6` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `option7` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `option8` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `option9` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `option10` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `option11` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `option12` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `option13` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `option14` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `option15` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `creator_id` int(10) UNSIGNED DEFAULT NULL,
                `updater_id` int(10) UNSIGNED DEFAULT NULL,
                `access_counter` int(11) NOT NULL DEFAULT '0',
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                `deleted_at` timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            "CREATE TABLE `{$prefix}polls_votes` (
                  `poll_id` int(11) NOT NULL,
                `user_id` int(11) NOT NULL,
                `option_id` int(11) NOT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            "ALTER TABLE `{$prefix}polls`
                ADD PRIMARY KEY (`id`),
                ADD UNIQUE KEY `polls_slug_unique` (`slug`),
                ADD KEY `polls_creator_id_foreign` (`creator_id`),
                ADD KEY `polls_updater_id_foreign` (`updater_id`);",
            "ALTER TABLE `{$prefix}polls_votes`
                ADD PRIMARY KEY (`poll_id`,`user_id`,`option_id`);",
            "ALTER TABLE `{$prefix}polls`
                MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;",

            "ALTER TABLE {$prefix}events ADD `internal` TINYINT UNSIGNED DEFAULT 0;",

            "ALTER TABLE {$prefix}downloads ADD `internal` TINYINT UNSIGNED DEFAULT 0;",
            "ALTER TABLE {$prefix}downloads ADD `published` TINYINT UNSIGNED DEFAULT 1;",

            "ALTER TABLE {$prefix}galleries ADD `published` TINYINT UNSIGNED DEFAULT 1;",

            "ALTER TABLE `{$prefix}users` ADD `discord` VARCHAR(255) DEFAULT NULL;",
            "ALTER TABLE `{$prefix}users` CHANGE `mousepad` `mouse_pad` VARCHAR(255);",

            "ALTER TABLE `{$prefix}partners` ADD `facebook` VARCHAR(255) DEFAULT NULL;",
            "ALTER TABLE `{$prefix}partners` ADD `twitter` VARCHAR(255) DEFAULT NULL;",
            "ALTER TABLE `{$prefix}partners` ADD `youtube` VARCHAR(255) DEFAULT NULL;",
            "ALTER TABLE `{$prefix}partners` ADD `discord` VARCHAR(255) DEFAULT NULL;",
         ];

        return $updateQueries;
    }

    /**
     * This method may update (module) permissions. It gets an array with the current permissions
     * as a reference so it may change it. The modified permissions will be written into the database.
     *
     * @param stdClass[] $permissions Array key = ID of the permissions record set, array value = permissions object
     * @return void
     */
    public function updatePermissions(array &$permissions)
    {
        $permissions[4]->polls = 4;
        $permissions[5]->polls = 4;
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
     * Show a page with info and ask the user to confirm the update
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
        /*
         * Initialize
         */
        $this->printText('Updating the database...');

        $appConfig = $this->app->getConfig('app');
        $newVersion = $appConfig['version'];

        $connectionDetails = $this->app->getDatabaseConnectionDetails();
        $prefix = isset($connectionDetails['prefix']) ? $connectionDetails['prefix'] : '';

        $pdo = $this->app->createDatabaseConnection();

        /*
         * Version check
         */
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

        /*
         * Database updates
         */
        $queries = $this->updateDatabase($prefix);

        foreach ($queries as $query) {
            $result = $pdo->query($query);

            if ($result === false) {
                $this->printText('Could not execute the database query: '.$pdo->errorInfo()[2]);
                return $pdo->errorInfo()[0];
            }
        }

        $result = $pdo->query("REPLACE `{$prefix}config` (`name`, `value`, `updated_at`) VALUES
            ('app.version', '$newVersion', NOW())");

        if ($result === false) {
            $this->printText('Could not execute the database query: '.$pdo->errorInfo()[2]);
            return $pdo->errorInfo()[0];
        }

        /*
         * Permissions
         */
        $results = $pdo->query('SELECT `id`, `permissions` FROM `'.$prefix.'roles` WHERE `id` <= 5');

        if ($results === false) {
            $this->printText('Could not execute the database query: '.$pdo->errorInfo()[2]);
            return $pdo->errorInfo()[0];
        }

        $permissionObjects = [];
        foreach ($results as $result) {
            $permissionObject = json_decode($result['permissions']);
            if (! is_object($permissionObject)) {
                $permissionObject = new stdClass();
            }
            $permissionObjects[(int) $result['id']] = $permissionObject;
        }

        $this->updatePermissions($permissionObjects);

        foreach ($permissionObjects as $id => $permissionObject) {
            $stringifiedPermissions = $pdo->quote(json_encode($permissionObject));
            $result = $pdo->query(
                'UPDATE `'.$prefix.'roles` SET `permissions` = '.$stringifiedPermissions.' WHERE `id` = '.$id
            );

            if ($result === false) {
                $this->printText('Could not execute the database query: '.$pdo->errorInfo()[2]);
                return $pdo->errorInfo()[0];
            }
        }

        /*
         * Delete modules.json to enforce its automatically recreation so new modules will be detected
         */
        unlink($this->app->getStorageDir().'app/modules.json');

        /*
         * Say goodbye
         */
        return $this->goodbye();
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
