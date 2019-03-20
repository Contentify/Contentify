<?php

namespace Contentify\Controllers;

use Artisan;
use Closure;
use Config;
use Contentify\Installer;
use Controller;
use DB;
use File;
use Form;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Database\Schema\Blueprint;
use Input;
use Schema;
use Sentinel;
use Str;
use Validator;
use View;

class InstallController extends Controller
{

    /**
     * (Relative) path to the file that indicates if the app is installed or not
     */
    const INSTALL_FILE = 'app/.installed';

    /**
     * (Relative) path to the database ini file
     */
    const DB_INI_FILE = 'app/database.ini';
    
    /**
     * @var Installer
     */
    protected $installer;
    
    public function __construct()
    {
        $this->installer = new Installer();
    }

    /**
     * Index action method
     * 
     * @param  int             $step   Step number
     * @param  null|MessageBag $errors Validation errors
     * @return \Illuminate\Contracts\View\View
     */
    public function index(int $step = -1, MessageBag $errors = null)
    {
        if (! Config::get('app.debug')) {
            die('Please enable the debug mode to start the installer.');
        }

        $filename = storage_path(self::INSTALL_FILE);
        $filename = str_replace(base_path(), '', $filename); // Make the path relative
        if (file_exists($filename)) {
            die('Contentify has been installed already. Remove this file if you want to reinstall it: ...'.$filename);
        }

        if ($step < 0) {
            $step = (int) Input::get('step', 0);
        }
        $title      = '';
        $content    = '';

        switch ($step) {
            case 6:
                $username               = Input::get('username');
                $email                  = Input::get('email');
                $password               = Input::get('password');
                $password_confirmation  = Input::get('password_confirmation');

                /*
                 * Validation
                 */
                $validator = Validator::make(
                    [
                        'username'              => $username,
                        'email'                 => $email,
                        'password'              => $password,
                        'password_confirmation' => $password_confirmation,
                    ],
                    [
                        'username'  => 'alpha_numeric_spaces|required|min:3|not_in:edit,password,daemon',
                        'email'     => 'email|required|unique:users,email',
                        'password'  => 'required|min:6|confirmed',
                    ]
                );

                if ($validator->fails()) {
                    return $this->index($step - 1, $validator->messages());
                }

                /*
                 * Create the admin user (with ID = 2)
                 */
                $user = Sentinel::register([
                    'email'     => $email,
                    'password'  => $password,
                    'username'  => $username,
                ], true);

                /*
                 * Add user to role "Super-Admins"
                 */
                $adminRole = Sentinel::findRoleBySlug('super-admins'); 
                $adminRole->users()->attach($user);

                /*
                 * Delete the file that indicates if the app is installed or not
                 */
                $filename = storage_path(self::INSTALL_FILE);

                $title      = 'Installation complete';
                $content    = '<p>Congratulations, Contentify is ready to rumble.</p>';

                if (File::isWritable(File::dirname($filename))) {
                  if (! File::exists($filename)) {
                    File::put($filename, time());
                  }
                } else {
                  $content .= '<p><b>Error:</b> Cannot create '.$filename.'! Please create it manually.';
                }

                $this->installer->sendStatistics();

                break;
            case 5:
                $title      = 'Create super-admin user';
                $content    = '<p>Please fill in the details of your user account.</p>'.
                              '<div class="warning">'.Form::errors($errors).'</div>'.
                              Form::open(['url' => 'install?step='.($step + 1)]).
                              Form::smartText('username', 'Username').
                              Form::smartEmail(). // TODO: Title will be translated! Change this?
                              Form::smartPassword().
                              Form::smartPassword('password_confirmation').
                              Form::close();

                break;
            case 4:
                $this->installer->createDatabase();
                $this->installer->createUserRoles();

                /*
                 * Create the daemon user (with ID = 1)
                 */
                $user = Sentinel::register([
                    'email'     => 'daemon@contentify.org',
                    'username'  => 'Daemon',
                    'password'  => Str::random(),
                    'activated' => false,
                ]);

                $this->installer->createSeed();

                $title      = 'Database setup complete';
                $content    = '<p>Database filled with initial seed.</p>';

                break;
            case 3:
                $host       = Input::get('host');
                $database   = Input::get('database');
                $username   = Input::get('username');
                $password   = Input::get('password');

                // If all credential values are null we assume the "previous" button has been pressed.
                // In this case we redirect (internally) to the step with the credential form.
                if ($host === null and $database === null and $username === null and $password === null) {
                    return $this->index($step - 1);
                }

                /*
                 * Validation
                 */
                $validator = Validator::make(
                    [
                        'host'      => $host,
                        'database'  => $database,
                        'username'  => $username,
                        'password'  => $password,
                    ],
                    [
                        'host'      => 'required', // We can't use the IP filter since it does not support ports
                        'database'  => 'alpha_dash|required',
                        'username'  => 'alpha_dash|required',
                    ]
                );

                if ($validator->fails()) {
                    return $this->index($step - 1, $validator->messages());
                }

                File::put(storage_path(self::DB_INI_FILE), 
                    '; Auto-generated file with database connection settings.'.PHP_EOL.
                    '; See config/database.php for more settings.'.PHP_EOL.PHP_EOL.
                    "host = "."\"$host\"".PHP_EOL.
                    "database = "."\"$database\"".PHP_EOL.
                    "username = "."\"$username\"".PHP_EOL.
                    "password = "."\"$password\""
                );

                $title      = 'Storing Database Credentials';
                $content    = '<p>Stored the database credentials.</p>
                               <p>Next the database will be created. This may take some time.</p>';

                break;
            case 2:
                // Define default settings:
                $settings = ['host' => '127.0.0.1', 'database' => 'contentify', 'username' => 'root', 'password' => ''];

                $filename = storage_path(self::DB_INI_FILE);
                if (File::exists($filename)) {
                    $settings = parse_ini_file($filename);
                }

                $title      = 'Database setup';
                $content    = '<p>Fill in the database connection settings.</p>'.
                              '<div class="warning">'.Form::errors($errors).'</div>'.
                              Form::open(['url' => 'install?step='.($step + 1)]).
                              Form::smartText('host', 'Host', $settings['host']).
                              Form::smartText('database', 'Database', $settings['database']).
                              Form::smartText('username', 'Username', $settings['username']).
                              // Note: We can't use smartPassword(), because it cannot set a default value.
                              Form::smartText('password', 'Password', $settings['password']).
                              Form::close().
                              '<p>For more settings, take a look at <code>config/database.php</code>.</p>';
               
                break;
            case 1:
                if (version_compare(PHP_VERSION, '5.6.4') >= 0) {
                    $version = '<span class="state yes">Yes, '.phpversion().'</span>';
                } else {
                    $version = '<span class="state no">No, '.phpversion().'</span>';
                }
                if (extension_loaded('openssl')) {
                    $openSsl = '<span class="state yes">Yes</span>';
                } else {
                    $openSsl = '<span class="state no">No</span>';
                }
                if (extension_loaded('pdo')) {
                    $pdo = '<span class="state yes">Yes</span>';
                } else {
                    $pdo = '<span class="state no">No</span>';
                }
                if (extension_loaded('mbstring')) {
                    $mbString = '<span class="state yes">Yes</span>';
                } else {
                    $mbString = '<span class="state no">No</span>';
                }
                if (extension_loaded('tokenizer')) {
                    $tokenizer = '<span class="state yes">Yes</span>';
                } else {
                    $tokenizer = '<span class="state no">No</span>';
                }
                if (extension_loaded('xml')) {
                    $xml = '<span class="state yes">Yes</span>';
                } else {
                    $xml = '<span class="state no">No</span>';
                }

                $writableDirs = [
                    base_path().'/storage',
                    base_path().'/bootstrap/cache',
                    public_path()
                ];

                $dirUl = '<ul>'; // HTML::ul() will encode HTML entities so we can't use it here
                foreach ($writableDirs as $dir) {
                    if (File::isWritable($dir)) {
                        $dirUl .= '<li>'.$dir.'<span class="state yes">Yes</span></li>';
                    } else {
                        $dirUl .= '<li>'.$dir.'<span class="state no">No</span></li>';
                    }
                }
                $dirUl .= '</ul>';

                $title      = 'Preconditions';
                $content    = "<p>These packages need to be installed:</p>
                              <ul>
                              <li>PHP >= 5.5.9 $version</li>
                              <li>OpenSSL Extension $openSsl</li>
                              <li>PDO Extension $pdo</li>
                              <li>Mbstring Extension $mbString</li>
                              <li>Tokenizer Extension $tokenizer</li>
                              <li>XML Extension $xml</li>
                              </ul>
                              <p>The application needs write access (CHMOD 777) to these directories 
                              and their sub directories:</p>
                              $dirUl
                              <p class=\"warning\">Please do not continue 
                              if your server does not meet these requirements!</p>";
                              
                break;
            default:
                $step       = 0; // Better save than sorry! (E.g. if step was -1)
                $title      = 'Welcome to Contentify '.Config::get('app.version');
                $content    = '<p>Please click on the "Next" button to start the installation.</p>
                              <p><a href="https://github.com/Contentify/Contentify/wiki/Installation" target="_blank">
                              Take a look at our documentation if you need help.</a></p>';
        }
        
        return View::make('installer', compact('title', 'content', 'step'));
    }
       
}
