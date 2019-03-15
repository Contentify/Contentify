<?php

namespace Contentify\Controllers;

use Artisan;
use Closure;
use Config;
use Controller;
use DB;
use File;
use Form;
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
     * URL of the Contentify.org API call after a successful installation.
     */
    const API_URL = 'http://www.contentify.org/api.php?action=send-statistics';

    /**
     * The installer will try to increase the time limit to this value (in minutes) if possible
     */
    const MAX_TIME_LIMIT = 5 * 60;

    /**
     * Index action method
     * 
     * @param  int                                 $step   Step number
     * @param  null|\Illuminate\Support\MessageBag $errors Validation errors
     * @return \Illuminate\Contracts\View\View
     */
    public function index($step = -1, $errors = null) 
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
                $user = Sentinel::register(array(
                    'email'     => $email,
                    'password'  => $password,
                    'username'  => $username,
                ), true);

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

                $this->sendStatistics();

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
                $this->createDatabase();
                $this->createUserRoles();

                /*
                 * Create the daemon user (with ID = 1)
                 */
                $user = Sentinel::register(array(
                    'email'     => 'daemon@contentify.org',
                    'username'  => 'Daemon',
                    'password'  => Str::random(),
                    'activated' => false,
                ));

                $this->createSeed();

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

    /**
     * Creates the database tables
     * 
     * @return void
     */
    public function createDatabase()
    {
        /*
         * Notice: 
         * - The default length of strings is 255 chars.
         * - We recommend to use timestamp($name)->nullable() to create a datetime attribute.
         */

        /*
         * If possible (safe mode not enabled and use of set_time_limit not forbidden), 
         * set the execution time limit to more than just the default 30 seconds.
         */
        if (! ini_get('safe_mode') and function_exists('set_time_limit') and ini_get('max_execution_time') < self::MAX_TIME_LIMIT) {
            set_time_limit(self::MAX_TIME_LIMIT);
        }

        /*
         * Run Sentinel migrations trough Artisan.
         * Unfortunately it's not the simple Artisan::call('migrate') that it should be.
         * Note that Sentinel tables do not establish any foreign constraints.
         */
        define('STDIN', fopen('php://stdin', 'r'));
        $table = Config::get('database.migrations', null, false);
        $result = DB::select('SHOW TABLES LIKE "'.$table.'"');
        if (sizeof($result) > 0) { // Check if migrations table exists
            Artisan::call('migrate:reset', ['--quiet' => true, '--force' => true]); // Delete old tables
        }
        Artisan::call('migrate', 
            ['--path' => 'vendor/cartalyst/sentinel/src/migrations', '--quiet' => true, '--force' => true]
        );

        /*
         * Deactivate foreign key checks. This is one way to delete table with foreign constraints.
         * Usually it's not possible to delete a table that has an attribute
         * which is a foreign key of another table.
         * Note that this is session-based, there is also a global way.
         */
        DB::statement('SET foreign_key_checks = 0');

        Schema::dropIfExists('config');
        Schema::create('config', function(Blueprint $table)
        {
            $table->string('name')->primary(); // We can't name it "key" - that's a keyword in SQL - Eloquent bug?
            $table->text('value')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
       
        $this->create('visits', function(Blueprint $table)
        {
            $table->string('ip');
            $table->integer('user_agents');
            $table->date('visited_at');
        }, [], false);

        $this->create('languages', function(Blueprint $table)
        { 
            $table->string('title');
            $table->string('code', 2);
        }, [], false);

        $this->create('countries', function(Blueprint $table)
        { 
            $table->string('code', 3);
            $table->string('icon')->nullable();
        }, [], ['slug']);
       
        $this->create('comments', function(Blueprint $table)
        {
            $table->text('text')->nullable();
            $table->string('foreign_type', 30);
            $table->integer('foreign_id', false, true)->nullable();
        }, [], ['title', 'slug', 'access_counter']);

        $this->create('contact_messages', function(Blueprint $table)
        {
            $table->string('username', 30);
            $table->string('email');
            $table->text('text');
            $table->string('ip');
            $table->boolean('new')->default(true);
        }, [], ['slug', 'creator_id', 'updater_id']);

        $this->create('games', function(Blueprint $table)
        {
            $table->string('short', 6)->nullable();
            $table->string('icon')->nullable();
        }, [], ['slug']);

        $this->create('page_cats', function(Blueprint $table) { }, [], ['slug']);

        $this->create('pages', function(Blueprint $table)
        {
            $table->text('text')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->boolean('internal')->default(false);
            $table->boolean('enable_comments')->default(false);
        }, ['page_cat_id']);

        $this->create('news_cats', function(Blueprint $table)
        {
            $table->string('image')->nullable();
        }, [], ['slug']);

        $this->create('news', function(Blueprint $table)
        {
            $table->text('summary')->nullable();
            $table->text('text')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->boolean('internal')->default(false);
            $table->boolean('enable_comments')->default(false);
        }, ['news_cat_id']);

        $this->create('galleries', function(Blueprint $table)
        {
            $table->boolean('published')->default(false);
        });
        
        $this->create('images', function(Blueprint $table)
        {
            $table->string('tags')->nullable();
            $table->string('image')->nullable();
            $table->integer('width')->default(0);
            $table->integer('height')->default(0);
        }, ['gallery_id'], ['slug']);

        $this->createPivot('team_user', function(Blueprint $table)
        {
            $table->string('task')->nullable();
            $table->text('description')->nullable();
            $table->integer('position')->default(0);
        }, ['user_id', 'team_id']);

        $this->create('team_cats', function(Blueprint $table) { }, [], ['slug']);

        $this->create('teams', function(Blueprint $table)
        { 
            $table->text('text')->nullable();
            $table->string('image')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('published')->default(false);
        }, ['team_cat_id', 'country_id']);

        $this->create('advert_cats', function(Blueprint $table) { }, [], ['slug']);

        $this->create('adverts', function(Blueprint $table)
        {
            $table->text('code')->nullable();
            $table->string('url')->nullable();
            $table->boolean('published')->default(false);
            $table->string('image')->nullable();
        }, ['advert_cat_id'], ['slug']);

        $this->create('partner_cats', function(Blueprint $table) { }, [], ['slug']);

        $this->create('partners', function(Blueprint $table)
        {
            $table->text('text')->nullable();
            $table->string('url')->nullable();
            $table->text('facebook')->nullable();
            $table->text('twitter')->nullable();
            $table->text('youtube')->nullable();
            $table->text('discord')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('published')->default(false);
            $table->string('image')->nullable();
        }, ['partner_cat_id']);

        $this->create('videos', function(Blueprint $table)
        {
            $table->string('url')->nullable();
            $table->string('permanent_id')->nullable();
            $table->string('provider');
        });

        $this->create('download_cats', function(Blueprint $table) { }); // Supports slugs

        $this->create('downloads', function(Blueprint $table)
        { 
            $table->text('description')->nullable();
            $table->string('file')->nullable();
            $table->integer('file_size')->default(0);
            $table->boolean('is_image')->default(false);
            $table->boolean('internal')->default(false);
            $table->boolean('published')->default(false);
        }, ['download_cat_id']);
        
        $this->create('slide_cats', function(Blueprint $table) { }, [], ['slug']);

        $this->create('slides', function(Blueprint $table)
        {
            $table->text('text')->nullable();
            $table->string('url')->nullable();
            $table->string('image')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('published')->default(false);
        }, ['slide_cat_id'], ['slug']);
               
        $this->create('tournaments', function(Blueprint $table)
        {
            $table->string('short', 6)->nullable();
            $table->string('url')->nullable();
            $table->string('icon')->nullable();
        },  [], ['slug']);
        
        $this->create('awards', function(Blueprint $table)
        {
            $table->string('url')->nullable();
            $table->integer('position')->default(0);
            $table->timestamp('achieved_at')->nullable();
        }, ['game_id', 'tournament_id', 'team_id'], ['slug']);

        $this->create('opponents', function(Blueprint $table)
        {
            $table->string('short', 6)->nullable();
            $table->string('url')->nullable();
            $table->string('lineup')->nullable();
            $table->string('image')->nullable();
        }, ['country_id']);
        
        $this->create('maps', function(Blueprint $table)
        {
            $table->string('image')->nullable();
        }, ['game_id'], ['slug']);

        $this->create('match_scores', function(Blueprint $table)
        {
            $table->integer('left_score')->default(0);
            $table->integer('right_score')->default(0);
            $table->nullableTimestamps();
        }, ['match_id', 'map_id'], false);
 
        $this->create('matches', function(Blueprint $table)
        {
            $table->integer('state')->default(0);
            $table->boolean('featured')->default(false);
            $table->string('url')->nullable();
            $table->string('broadcast')->nullable();
            $table->string('left_lineup')->nullable();
            $table->string('right_lineup')->nullable();
            $table->text('text')->nullable();
            $table->timestamp('played_at')->nullable();
            $table->integer('left_score')->default(0); // Total score
            $table->integer('right_score')->default(0);
        }, 
        ['left_team_id' => 'team_id', 'right_team_id' => 'opponent_id', 'game_id', 'tournament_id'], 
        ['title', 'slug']);

        $this->create('streams', function(Blueprint $table)
        {
            $table->string('url')->nullable();
            $table->string('permanent_id')->nullable();
            $table->string('provider');
            $table->string('thumbnail')->nullable();
            $table->boolean('online')->default(false);
            $table->integer('viewers')->default(0);
            $table->timestamp('renewed_at')->nullable();
        });

        $this->create('servers', function(Blueprint $table)
        {
            $table->string('ip');
            $table->string('hoster')->nullable();
            $table->integer('slots')->default(0);
            $table->boolean('published')->default(false);
            $table->text('description')->nullable();
        }, ['game_id'], ['slug']);

        $this->create('forums', function(Blueprint $table)
        { 
            $table->text('description')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('internal')->default(false);
            $table->integer('level')->default(0);
            $table->integer('threads_count')->default(0);
            $table->integer('posts_count')->default(0);
        }, ['forum_id', 'latest_thread_id' => 'forum_thread_id', 'team_id']);

        $this->create('forum_threads', function(Blueprint $table)
        { 
            $table->integer('posts_count')->default(1);
            $table->boolean('sticky')->default(false);
            $table->boolean('closed')->default(false);
        }, ['forum_id']);

        $this->create('forum_posts', function(Blueprint $table)
        { 
            $table->text('text')->nullable();
            $table->boolean('root')->default(0);
        }, ['thread_id' => 'forum_threads_id'], ['slug', 'title']);

        $this->create('forum_reports', function(Blueprint $table)
        { 
            $table->text('text')->nullable();
        }, ['post_id' => 'forum_posts_id'], ['title', 'slug']);

        $this->create('messages', function(Blueprint $table)
        {
            $table->text('text');
            $table->boolean('new')->default(true);
            $table->boolean('creator_visible')->default(true);
            $table->boolean('receiver_visible')->default(true);
            $table->boolean('sent_by_system')->default(false);
        }, ['receiver_id' => 'users_id']);

        $this->create('navigations', function(Blueprint $table)
        {
            $table->text('items')->nullable();
            $table->boolean('translate')->default(false);
        }, [], ['slug']);

        $this->create('user_activities', function(Blueprint $table)
        {
            $table->boolean('frontend');
            $table->string('model_class')->nullable();
            $table->text('info')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('activity_id');
        }, ['user_id'], false);

        $this->create('shouts', function(Blueprint $table)
        { 
            $table->text('text')->nullable();
        }, [], ['title', 'slug', 'access_counter', 'updater_id']);

        $this->createPivot('friends', function(Blueprint $table)
        {
            $table->boolean('confirmed')->default(false);
            $table->timestamp('messaged_at')->nullable();
        }, ['sender_id', 'receiver_id']);

        $this->create('events', function(Blueprint $table)
        {
            $table->text('text')->nullable();
            $table->string('url')->nullable();
            $table->string('location')->nullable();
            $table->string('image')->nullable();
            $table->boolean('internal')->default(false);
            $table->timestamp('starts_at')->nullable();
        });

        $this->create('ratings', function(Blueprint $table)
        {
            $table->integer('rating');
            $table->string('foreign_type', 30);
            $table->integer('foreign_id', false, true)->nullable();
        }, ['user_id'], false);

        $this->create('cups_teams', function(Blueprint $table)
        {
            $table->string('image')->nullable();
            $table->string('password')->nullable();
            $table->boolean('invisible')->default(false);
            $table->integer('cup_points')->default(0);
        });

        Schema::dropIfExists('cups_team_members');
        Schema::create('cups_team_members', function(Blueprint $table)
        {
            $table->integer('team_id');
            $table->integer('user_id');
            $table->boolean('organizer')->default(false);
            $table->primary(['team_id', 'user_id']);
        });

        $this->create('cups', function(Blueprint $table)
        {
            $table->text('description')->nullable();
            $table->text('rulebook')->nullable(); // Note: We cannot name this attribute "rules"
            $table->integer('players_per_team');
            $table->integer('slots');
            $table->string('prize');
            $table->string('image')->nullable();
            $table->timestamp('join_at')->nullable();
            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('published')->default(false);
            $table->boolean('closed')->default(false);
        },  ['game_id']);

        Schema::dropIfExists('cups_participants');
        Schema::create('cups_participants', function(Blueprint $table)
        {
            $table->integer('cup_id');
            $table->integer('participant_id');
            $table->boolean('checked_in')->default(false);
            $table->primary(['cup_id', 'participant_id']);
        });

        $this->create('cups_matches', function(Blueprint $table)
        {
            $table->integer('round');
            $table->integer('row');
            $table->boolean('with_teams'); // Helper attribute. Value is determined by the cup.
            $table->integer('left_participant_id');
            $table->integer('right_participant_id');
            $table->integer('winner_id')->default(0);
            $table->integer('next_match_id')->default(0);
            $table->integer('left_score')->default(0); // Total score
            $table->integer('right_score')->default(0);
            $table->boolean('left_confirmed')->default(false);
            $table->boolean('right_confirmed')->default(false);
        }, 
        ['cup_id'], ['title', 'slug']);
        
        Schema::dropIfExists('cups_users');
        Schema::create('cups_users', function(Blueprint $table)
        {
            $table->integer('cup_id');
            $table->integer('user_id');
            $table->boolean('cup_closed')->default(false); // Helper attribute. Value is determined by the cup.
            $table->primary(['cup_id', 'user_id']);
        });

        Schema::dropIfExists('cups_referees');
        Schema::create('cups_referees', function(Blueprint $table)
        {
            $table->integer('cup_id');
            $table->integer('user_id');
            $table->primary(['cup_id', 'user_id']);
        });

        $this->create('cash_flows', function(Blueprint $table)
        {
            $table->text('description')->nullable();
            $table->integer('integer_revenues')->default(0);
            $table->integer('integer_expenses')->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->boolean('paid')->default(true);
        }, ['user_id'], ['slug']);

        $this->create('question_cats', function(Blueprint $table) { }, [], ['slug']);

        $this->create('questions', function(Blueprint $table)
        {
            $table->text('answer')->nullable();
            $table->boolean('published')->default(true);
            $table->integer('position')->default(0);
        }, ['question_cat_id'], ['slug']);

        $this->create('polls', function(Blueprint $table)
        {
            $table->boolean('open')->default(true);
            $table->boolean('internal')->default(false);
            $table->integer('max_votes');
            $table->string('option1')->nullable();
            $table->string('option2')->nullable();
            $table->string('option3')->nullable();
            $table->string('option4')->nullable();
            $table->string('option5')->nullable();
            $table->string('option6')->nullable();
            $table->string('option7')->nullable();
            $table->string('option8')->nullable();
            $table->string('option9')->nullable();
            $table->string('option10')->nullable();
            $table->string('option11')->nullable();
            $table->string('option12')->nullable();
            $table->string('option13')->nullable();
            $table->string('option14')->nullable();
            $table->string('option15')->nullable();
        });

        Schema::dropIfExists('polls_votes');
        Schema::create('polls_votes', function(Blueprint $table)
        {
            $table->integer('poll_id');
            $table->integer('user_id');
            $table->integer('option_id');
            $table->primary(['poll_id', 'user_id', 'option_id']);
        });

        /*
         * (Re)activate foreign key checks
         */
        DB::statement('SET foreign_key_checks = 1');

        /*
         * Run remaining (general) migrations trough Artisan.
         */
        Artisan::call('migrate', ['--quiet' => true, '--force' => true]);
    }

    /**
     * Creates the initial database seed
     * 
     * @return void
     */
    protected function createSeed() 
    {
        $this->createDefaultCategories(['news_cats', 'partner_cats', 'advert_cats', 'slide_cats', 'question_cats']);
        $this->createDefaultCategories(['download_cats'], true);

        // Note: We make insertions for default values, we do not have to create empty records
        DB::table('config')->insert([
            ['name' => 'app.name',                      'value' => Config::get('app.name', null, false)],
            ['name' => 'app.theme',                     'value' => 'MorpheusTheme'],
            ['name' => 'app.theme_christmas',           'value' => 0],
            ['name' => 'app.theme_snow_color',          'value' => '#ffffff'],
            ['name' => 'app.https',                     'value' => 0],
            ['name' => 'app.dbBackup',                  'value' => 0],
            ['name' => 'auth::registration',            'value' => 1],
            ['name' => 'forums::reports',               'value' => 1],
            ['name' => 'cups::cup_points',              'value' => 10],
            ['name' => 'app.twitter',                   'value' => 'ContentifyCMS'],
            ['name' => 'app.facebook',                  'value' => 'contentifycms'],
            ['name' => 'app.youtube',                   'value' => 'UC2gIIZzySdgxrQ3jM4jmoqQ'],
            ['name' => 'app.forbidden_email_domains',   'value' => 'example.com, example.org'],
        ]);

        DB::table('team_cats')->insert([
            ['id' => '1', 'title' => 'Staff', 'creator_id' => 1, 'updater_id' => 1],
            ['id' => '2', 'title' => 'Gaming', 'creator_id' => 1, 'updater_id' => 1],
        ]);

        DB::table('page_cats')->insert([
            ['id' => '1', 'title' => 'Blog Post', 'creator_id' => 1, 'updater_id' => 1],
            ['id' => '2', 'title' => 'Custom Page', 'creator_id' => 1, 'updater_id' => 1],
            ['id' => '3', 'title' => 'Custom Content', 'creator_id' => 1, 'updater_id' => 1],
        ]);

        DB::table('pages')->insert([
            'title'         => 'Impressum', 
            'slug'          => 'impressum',
            'text'          => 
'<h2>Privacy Statement</h2><h3>General</h3>Your personal data (e.g. title, name, house address, e-mail address, phone 
    number, bank details, credit card number) are processed by us only in accordance with the provisions of German data 
privacy laws. The following provisions describe the type, scope and purpose of collecting, processing and utilizing 
personal data. This data privacy policy applies only to our web pages. If links on our pages route you to other pages, 
please inquire there about how your data are handled in such cases.<br><h3>Inventory data</h3>(1) Your personal data, 
insofar as these are necessary for this contractual relationship (inventory data) in terms of its establishment, 
organization of content and modifications, are used exclusively for fulfilling the contract. For goods to be delivered, 
for instance, your name and address must be relayed to the supplier of the goods. <br>(2) Without your explicit 
    consent or a legal basis, your personal data are not passed on to third parties outside the scope of fulfilling 
this contract. After completion of the contract, your data are blocked against further use. After expiry of deadlines 
as per tax-related and commercial regulations, these data are deleted unless you have expressly consented to their 
further use.<br><h3>Disclosure</h3>According to the Federal Data Protection Act, you have a right to free-of-charge 
information about your stored data, and possibly entitlement to correction, blocking or deletion of such data. 
<br><br><i>From: </i><a href="https://www.twigg.de/" target="_blank">http://www.twigg.de/</a>',
            'published'     => true,
            'published_at'  => DB::raw('NOW()'),
            'creator_id'    => 1,
            'updater_id'    => 1,
            'page_cat_id'   => 2,
            'created_at'    => DB::raw('NOW()'),
            'updated_at'    => DB::raw('NOW()'),
        ]);

        DB::table('languages')->insert([
            ['id' => '1', 'title' => 'English', 'code' => 'en'],
            ['id' => '2', 'title' => 'Deutsch', 'code' => 'de'],
            ['id' => '3', 'title' => 'Francais', 'code' => 'fr'],
            ['id' => '4', 'title' => 'Spanish', 'code' => 'es']
        ]);

        DB::insert('INSERT INTO countries(title, code, icon, creator_id, updater_id) VALUES
        ("European Union", "eu", "eu.png", 1, 1),
        ("Afghanistan", "af", "af.png", 1, 1),
        ("Albania", "al", "al.png", 1, 1),
        ("Algeria", "dz", "dz.png", 1, 1),
        ("Andorra", "ad", "ad.png", 1, 1),
        ("Angola", "ao", "ao.png", 1, 1),
        ("Antigua", "ag", "ag.png", 1, 1),
        ("Argentina", "ar", "ar.png", 1, 1),
        ("Armenia", "am", "am.png", 1, 1),
        ("Australia", "au", "au.png", 1, 1),
        ("Austria", "at", "at.png", 1, 1),
        ("Azerbaijan", "az", "az.png", 1, 1),
        ("Bahamas", "bs", "bs.png", 1, 1),
        ("Bahrain", "bh", "bh.png", 1, 1),
        ("Bangladesh", "bd", "bd.png", 1, 1),
        ("Barbados", "bb", "bb.png", 1, 1),
        ("Belarus", "by", "by.png", 1, 1),
        ("Belgium", "be", "be.png", 1, 1),
        ("Bolivia", "bo", "bo.png", 1, 1),
        ("Bosnia Herzegowina", "ba", "ba.png", 1, 1),
        ("Botswana", "bw", "bw.png", 1, 1),
        ("Brazil", "br", "br.png", 1, 1),
        ("Bulgaria", "bg", "bg.png", 1, 1),
        ("Burkina Faso", "bf", "bf.png", 1, 1),
        ("Cambodia", "kh", "kh.png", 1, 1),
        ("Cameroon", "cm", "cm.png", 1, 1),
        ("Cayman Islands", "ky", "ky.png", 1, 1),
        ("Canada", "ca", "ca.png", 1, 1),
        ("Chad", "td", "td.png", 1, 1),
        ("Chile", "cl", "cl.png", 1, 1),
        ("China", "cn", "cn.png", 1, 1),
        ("Colombia", "co", "co.png", 1, 1),
        ("Congo", "cd", "cd.png", 1, 1),
        ("Costa Rica", "cr", "cr.png", 1, 1),
        ("Croatia", "hr", "hr.png", 1, 1),
        ("Cuba", "cu", "cu.png", 1, 1),
        ("Cyprus", "cy", "cy.png", 1, 1),
        ("Czech Republic", "cz", "cz.png", 1, 1),
        ("Denmark", "dk", "dk.png", 1, 1),
        ("Dominican Republic", "do", "do.png", 1, 1),
        ("Ecuador", "ec", "ec.png", 1, 1),
        ("Egypt", "eg", "eg.png", 1, 1),
        ("El Salvador", "sv", "sv.png", 1, 1),
        ("Estonia", "ee", "ee.png", 1, 1),
        ("Eritrea", "er", "er.png", 1, 1),
        ("Ethiopia", "et", "et.png", 1, 1),
        ("Falkland Islands", "fk", "fk.png", 1, 1),
        ("Faroe Islands", "fo", "fo.png", 1, 1),
        ("Fiji", "fj", "fj.png", 1, 1),
        ("Finland", "fi", "fi.png", 1, 1),
        ("France", "fr", "fr.png", 1, 1),
        ("French Polynesia", "pf", "pf.png", 1, 1),
        ("Germany", "de", "de.png", 1, 1),
        ("Georgia", "ge", "ge.png", 1, 1),
        ("Ghana", "gh", "gh.png", 1, 1),
        ("Gibraltar", "gi", "gi.png", 1, 1),
        ("Greece", "gr", "gr.png", 1, 1),
        ("Greenland", "gl", "gl.png", 1, 1),
        ("Guam", "gu", "gu.png", 1, 1),
        ("Guatemala", "gt", "gt.png", 1, 1),
        ("Haiti", "ht", "ht.png", 1, 1),
        ("Honduras", "hn", "hn.png", 1, 1),
        ("Hungary", "hu", "hu.png", 1, 1),
        ("Iceland", "is", "is.png", 1, 1),
        ("India", "in", "in.png", 1, 1),
        ("Indonesia", "id", "id.png", 1, 1),
        ("Iran", "ir", "ir.png", 1, 1),
        ("Iraq", "iq", "iq.png", 1, 1),
        ("Ireland", "ie", "ie.png", 1, 1),
        ("Israel", "il", "il.png", 1, 1),
        ("Italy", "it", "it.png", 1, 1),
        ("Jamaica", "jm", "jm.png", 1, 1),
        ("Japan", "jp", "jp.png", 1, 1),
        ("Jordan", "jo", "jo.png", 1, 1),
        ("Kazakhstan", "kz", "kz.png", 1, 1),
        ("Kenya", "ke", "ke.png", 1, 1),
        ("Kyrgyzstan", "kg", "kg.png", 1, 1),
        ("Kuwait", "kw", "kw.png", 1, 1),
        ("Latvia", "lv", "lv.png", 1, 1),
        ("Laos", "la", "la.png", 1, 1),
        ("Lebanon", "lb", "lb.png", 1, 1),
        ("Liberia", "lr", "lr.png", 1, 1),
        ("Libya", "ly", "ly.png", 1, 1),
        ("Liechtenstein", "li", "li.png", 1, 1),
        ("Lithuania", "lt", "lt.png", 1, 1),
        ("Luxemburg", "lu", "lu.png", 1, 1),
        ("Macau", "mo", "mo.png", 1, 1),
        ("Macedonia", "mk", "mk.png", 1, 1),
        ("Madagascar", "mg", "mg.png", 1, 1),
        ("Malawi", "mw", "mw.png", 1, 1),
        ("Malaysia", "my", "my.png", 1, 1),
        ("Maldives", "mv", "mv.png", 1, 1),
        ("Mali", "ml", "ml.png", 1, 1),
        ("Marshall Islands", "mh", "mh.png", 1, 1),
        ("Mauritania", "mr", "mr.png", 1, 1),
        ("Malta", "mt", "mt.png", 1, 1),
        ("Mexico", "mx", "mx.png", 1, 1),
        ("Micronesia", "fm", "fm.png", 1, 1),
        ("Moldova", "md", "md.png", 1, 1),
        ("Monaco", "mc", "mc.png", 1, 1),
        ("Mongolia", "mn", "mn.png", 1, 1),
        ("Montenegro", "me", "me.png", 1, 1),
        ("Morocco", "ma", "ma.png", 1, 1),
        ("Mozambique", "mz", "mz.png", 1, 1),
        ("Myanmar", "mm", "mm.png", 1, 1),
        ("Namibia", "na", "na.png", 1, 1),
        ("Nepal", "np", "np.png", 1, 1),
        ("Netherlands", "nl", "nl.png", 1, 1),
        ("New Guinea", "pg", "pg.png", 1, 1),
        ("New Zealand", "nz", "nz.png", 1, 1),
        ("Nicaragua", "ni", "ni.png", 1, 1),
        ("Niger", "ne", "ne.png", 1, 1),
        ("Nigeria", "ng", "ng.png", 1, 1),
        ("Norway", "no", "no.png", 1, 1),
        ("North Korea", "kp", "kp.png", 1, 1),
        ("Norfolk Island", "nf", "nf.png", 1, 1),
        ("Oman", "om", "om.png", 1, 1),
        ("Pakistan", "pk", "pk.png", 1, 1),
        ("Palestine", "ps", "ps.png", 1, 1),
        ("Panama", "pa", "pa.png", 1, 1),
        ("Paraguay", "py", "py.png", 1, 1),
        ("Peru", "pe", "pe.png", 1, 1),
        ("Philippines", "ph", "ph.png", 1, 1),
        ("Poland", "pl", "pl.png", 1, 1),
        ("Portugal", "pt", "pt.png", 1, 1),
        ("Puerto Rico", "pr", "pr.png", 1, 1),
        ("Qatar", "qa", "qa.png", 1, 1),
        ("Romania", "ro", "ro.png", 1, 1),
        ("Russia", "ru", "ru.png", 1, 1),
        ("Rwanda", "rw", "rw.png", 1, 1),
        ("Samoa", "ws", "ws.png", 1, 1),
        ("San Marino", "sm", "sm.png", 1, 1),
        ("Saudi Arabia", "sa", "sa.png", 1, 1),
        ("Senegal", "sn", "sn.png", 1, 1),
        ("Seychelles", "sc", "sc.png", 1, 1),
        ("Sierra Leone", "sl", "sl.png", 1, 1),
        ("Singapore", "sg", "sg.png", 1, 1),
        ("Slovakia", "sk", "sk.png", 1, 1),
        ("Slovenia", "si", "si.png", 1, 1),
        ("Somalia", "so", "so.png", 1, 1),
        ("South Africa", "za", "za.png", 1, 1),
        ("South Korea", "kr", "kr.png", 1, 1),
        ("Spain", "es", "es.png", 1, 1),
        ("Sri Lanka", "lk", "lk.png", 1, 1),
        ("Sudan", "sd", "sd.png", 1, 1),
        ("Swaziland", "sz", "sz.png", 1, 1),
        ("Sweden", "se", "se.png", 1, 1),
        ("Switzerland", "ch", "ch.png", 1, 1),
        ("Syria", "sy", "sy.png", 1, 1),        
        ("Taiwan", "tw", "tw.png", 1, 1),
        ("Thailand", "th", "th.png", 1, 1),
        ("Tajikistan", "tj", "tj.png", 1, 1),
        ("Tonga", "to", "to.png", 1, 1),
        ("Trinidad", "tt", "tt.png", 1, 1),
        ("Tunisia", "tn", "tn.png", 1, 1),
        ("Turkey", "tr", "tr.png", 1, 1),
        ("Turkmenistan", "tm", "tm.png", 1, 1),
        ("Uganda", "ug", "ug.png", 1, 1),
        ("Ukraine", "ua", "ua.png", 1, 1),
        ("United Arab Emirates", "ae", "ae.png", 1, 1),
        ("United Kingdom", "uk", "uk.png", 1, 1),
        ("Uruguay", "uy", "uy.png", 1, 1),
        ("USA", "us", "us.png", 1, 1),
        ("Uzbekistan", "uz", "uz.png", 1, 1),
        ("Venezuela", "ve", "ve.png", 1, 1),
        ("Vietnam", "vn", "vn.png", 1, 1),
        ("Yemen", "ye", "ye.png", 1, 1),
        ("Zambia", "zm", "zm.png", 1, 1),
        ("Zimbabwe", "zw", "zw.png", 1, 1)');

        DB::insert('INSERT INTO games(title, short, icon, creator_id, updater_id) VALUES
        ("Counter-Strike: Global Offensive", "CS:GO", "default/csgo.png", 1, 1),
        ("Counter-Strike: Source", "CS:S", "default/css.gif", 1, 1),
        ("Counter-Strike 1.6", "CS", "default/cs.gif", 1, 1),
        ("Call of Duty", "CoD", "default/cod.png", 1, 1),
        ("Battlefield", "BF", "default/bf.png", 1, 1),
        ("Unreal Tournament 3", "UT3", "default/ut3.png", 1, 1),
        ("Left 4 Dead", "L4D", "default/l4d.png", 1, 1),
        ("Crysis", "Crysis", "default/crysis.png", 1, 1),
        ("Quake", "Quake", "default/quake.gif", 1, 1),
        ("StarCraft II", "SC2", "default/sc2.png", 1, 1),
        ("Warcraft III", "WC3", "default/wc3.gif", 1, 1),
        ("Diablo III", "D3", "default/d3.png", 1, 1),
        ("DotA 2", "DotA", "default/dota2.png", 1, 1),
        ("League of Legends", "LoL", "default/lol.png", 1, 1),
        ("Heroes of the Storm", "HotS", "default/hots.png", 1, 1),
        ("World of Warcraft", "WoW", "default/wow.png", 1, 1),
        ("World of Tanks", "WoT", "default/wot.png", 1, 1),
        ("Trackmania", "TM", "default/tm.gif", 1, 1),
        ("FIFA", "FIFA", "default/fifa.gif", 1, 1),
        ("Fortnite", "FN", "default/fn.png", 1, 1),
        ("PUBG", "PUBG", "default/pubg.png", 1, 1),
        ("Overwatch", "OW", "default/ow.png", 1, 1),
        ("Minecraft", "MC", "default/mc.png", 1, 1)');

        DB::insert('INSERT INTO maps(title, image, game_id, creator_id, updater_id) VALUES
        ("Unknown", "unknown.jpg", NULL, 1, 1),
        ("de_dust", "cs_dust.jpg", 1, 1, 1),
        ("de_dust2", "cs_dust2.jpg", 1, 1, 1),
        ("de_inferno", "cs_inferno.jpg", 1, 1, 1),
        ("de_train", "cs_train.jpg", 1, 1, 1),
        ("de_cbble", "cs_cbble.jpg",1, 1, 1),
        ("de_nuke", "cs_nuke.jpg",1, 1, 1),
        ("de_cache", "cs_cache.jpg", 1, 1, 1),
        ("de_mirage", "cs_mirage.jpg", 1, 1, 1),
        ("de_season", "cs_season.jpg", 1, 1, 1),
        ("de_overpass", "cs_overpass.jpg", 1, 1, 1)');

        DB::insert('INSERT INTO tournaments(title, short, creator_id, updater_id) VALUES
        ("Electronic Sports League", "ESL", 1, 1),
        ("E-Sports Entertainment Association", "ESEA", 1, 1),
        ("Major League Gaming", "MLG", 1, 1),
        ("Electronic Sports World Cup", "ESWC", 1, 1),
        ("Dreamhack", "DH", 1, 1)');

        DB::table('opponents')->insert([
            'title'         => 'To Be Announced',
            'slug'          => 'tba',
            'short'         => 'TBA',
            'country_id'    => 1,
            'creator_id'    => 1,
            'updater_id'    => 1,
            'created_at'    => DB::raw('NOW()'),
            'updated_at'    => DB::raw('NOW()'),
        ]);
    }

    /**
     * Create user permission roles.
     * The convention is that the name of permissions are in lowercase and only use the letter a-z.
     * 
     * @return void
     */
    protected function createUserRoles()
    {
        $repo = Sentinel::getRoleRepository();

        $repo->createModel()->create([
            'name'        => 'Visitors',
            'slug'        => 'visitors',
            'permissions' => []
        ]);

        $repo->createModel()->create([
            'name'        => 'Users',
            'slug'        => 'users',
            'permissions' => [
                'frontend'  => true,
                'comments'  => PERM_CREATE, // Users can also update and delete their own comments
                'ratings'   => PERM_CREATE,
            ]
        ]);

        $repo->createModel()->create([
            'name'        => 'Members',
            'slug'        => 'members',
            'permissions' => [
                'frontend'  => true,
                'internal'  => true,
                'comments'  => PERM_CREATE,
                'ratings'   => PERM_CREATE,
            ]
        ]);

        $repo->createModel()->create([
            'name'        => 'Admins',
            'slug'        => 'admins',
            'permissions' => [
                'frontend'      => true,
                'internal'      => true,
                'backend'       => true,
                'adverts'       => PERM_DELETE,
                'auth'          => PERM_DELETE,
                'awards'        => PERM_DELETE,
                'cashflows'     => PERM_DELETE,
                'comments'      => PERM_DELETE,
                'config'        => PERM_DELETE,
                'contact'       => PERM_DELETE,
                'countries'     => PERM_DELETE,
                'cups'          => PERM_DELETE,
                'diag'          => PERM_DELETE,
                'downloads'     => PERM_DELETE,
                'events'        => PERM_DELETE,
                'forums'        => PERM_DELETE,
                'galleries'     => PERM_DELETE,
                'games'         => PERM_DELETE,
                'roles'         => PERM_DELETE,
                'help'          => PERM_DELETE,
                'images'        => PERM_DELETE,
                'maps'          => PERM_DELETE,
                'matches'       => PERM_DELETE,
                'modules'       => PERM_READ, // So ordinary admins can't add modules that make them super admins
                'navigations'   => PERM_DELETE,
                'news'          => PERM_DELETE,
                'opponents'     => PERM_DELETE,
                'pages'         => PERM_DELETE,
                'partners'      => PERM_DELETE,
                'questions'     => PERM_DELETE,
                'ratings'       => PERM_DELETE,
                'servers'       => PERM_DELETE,
                'slides'        => PERM_DELETE,
                'streams'       => PERM_DELETE,
                'teams'         => PERM_DELETE,
                'tournaments'   => PERM_DELETE,
                'users'         => PERM_DELETE,
                'videos'        => PERM_DELETE,
            ]
        ]);

        $repo->createModel()->create([
            'name'        => 'Super-Admins',
            'slug'        => 'super-admins',
            'permissions' => [
                'frontend'      => true,
                'internal'      => true,
                'backend'       => true,
                'superadmin'    => true,
                'adverts'       => PERM_DELETE,
                'auth'          => PERM_DELETE,
                'awards'        => PERM_DELETE,
                'cashflows'     => PERM_DELETE,
                'comments'      => PERM_DELETE,
                'config'        => PERM_DELETE,
                'contact'       => PERM_DELETE,
                'countries'     => PERM_DELETE,
                'cups'          => PERM_DELETE,
                'diag'          => PERM_DELETE,
                'downloads'     => PERM_DELETE,
                'events'        => PERM_DELETE,
                'forums'        => PERM_DELETE,
                'galleries'     => PERM_DELETE,
                'games'         => PERM_DELETE,
                'roles'         => PERM_DELETE,
                'help'          => PERM_DELETE,
                'images'        => PERM_DELETE,
                'maps'          => PERM_DELETE,
                'matches'       => PERM_DELETE,
                'modules'       => PERM_DELETE,
                'navigations'   => PERM_DELETE,
                'news'          => PERM_DELETE,
                'opponents'     => PERM_DELETE,
                'pages'         => PERM_DELETE,
                'partners'      => PERM_DELETE,
                'questions'     => PERM_DELETE,
                'ratings'       => PERM_DELETE,
                'servers'       => PERM_DELETE,
                'slides'        => PERM_DELETE,
                'streams'       => PERM_DELETE,
                'teams'         => PERM_DELETE,
                'tournaments'   => PERM_DELETE,
                'users'         => PERM_DELETE,
                'videos'        => PERM_DELETE,
            ]
        ]);
    }

    /**
     * Helper function. Creates a database table.
     * 
     * @param string        $tableName     The name of the table
     * @param Closure       $tableRows     A closure defining the table rows
     * @param string[]      $foreignKeys   An array with names of foreign keys
     * @param boolean|array $contentObject Bool/array of attributes that won't be added (the array indicates true)
     * @return void
     */
    public function create($tableName, Closure $tableRows, $foreignKeys = array(), $contentObject = true)
    {
        /*
         * Delete existing table:
         */
        Schema::dropIfExists($tableName);

        /*
         * Add ID:
         */
        Schema::create($tableName, function(Blueprint $table) use ($tableRows, $foreignKeys, $contentObject)
        {
            $table->engine = 'InnoDB'; // Since we create the table here we ensure InnoDB is used as storage engine

            $table->increments('id'); // Primary key (unique, auto-increment)

            /*
             * Add content object attributes:
             */
            if ($contentObject) {
                if ($contentObject === true or ! in_array('title', $contentObject)) {
                    $table->string('title', 70);
                }
                if ($contentObject === true or ! in_array('slug', $contentObject)) {
                    $table->string('slug')->unique();
                }
            }

            /*
             * Add the table rows:
             */
            if ($tableRows) {
                $tableRows($table);
            }

            /*
             * Generate foreign keys
             */
            foreach ($foreignKeys as $key => $value) {
                if (is_string($key)) {
                    $localKey = $key;
                    $remoteKey = $value;
                } else {
                    $localKey = $remoteKey = $value;
                }

                $table->integer($localKey)->unsigned()->nullable();
                $foreignTable = str_plural(substr($remoteKey, 0, -3));
                $table->foreign($localKey)->references('id')->on($foreignTable);
            }

            /*
             * Add content object attributes:
             */
            if ($contentObject) {
                if ($contentObject === true or ! in_array('creator_id', $contentObject)) {
                    $table->integer('creator_id')->unsigned()->nullable();
                    $table->foreign('creator_id')->references('id')->on('users');
                }
                if ($contentObject === true or ! in_array('updater_id', $contentObject)) {
                    $table->integer('updater_id')->unsigned()->nullable();
                    $table->foreign('updater_id')->references('id')->on('users');
                }
                if ($contentObject === true or ! in_array('access_counter', $contentObject)) {
                    $table->integer('access_counter')->default(0);
                }

                $table->nullableTimestamps(); // Add timestamps (columns created_at, updated_at)

                if ($contentObject === true or ! in_array('deleted_at', $contentObject)) {
                    $table->softDeletes(); // Add soft deletes (column deleted_at)
                }
            }
        });
    }

    /**
     * Helper functions. Creates a database pivot table.
     * 
     * @param string   $tableName   The name of the table
     * @param Closure  $tableRows   A closure defining the table rows
     * @param string[] $primaryKeys An array with the names of both primary keys
     * @return void
     */
    protected function createPivot($tableName, Closure $tableRows, $primaryKeys = array())
    {
        /*
         * Delete existing table:
         */
        Schema::dropIfExists($tableName);

        /*
         * Add primary keys:
         */
        Schema::create($tableName, function(Blueprint $table) use ($primaryKeys)
        {
            $table->engine = 'InnoDB'; // Since we create the table here we ensure InnoDB is the storage engine

            /*
             * Add the primary keys:
             */
            foreach ($primaryKeys as $primaryKey) {
                $table->integer($primaryKey)->unsigned();
            }
            $table->primary($primaryKeys);

        });

        /*
         * Add the table rows:
         */
        if ($tableRows) {
            Schema::table($tableName, $tableRows);
        }
    }

    /**
     * Creates one or more default categories
     * 
     * @param string[] $tables   Array of table names
     * @param bool     $withSlug If true, also fill the slug attribute
     * @return void
     */
    protected function createDefaultCategories($tables, $withSlug = false)
    {
        foreach ($tables as $table) {
            $values = [
                'id'            => '1',
                'title'         => 'Default',
                'creator_id'    => 1,
                'updater_id'    => 1,
                'created_at'    => DB::raw('NOW()'),
                'updated_at'    => DB::raw('NOW()'),
            ];

            if ($withSlug) {
                $values['slug'] = 'default';
            }

            DB::table($table)->insert([
                $values
            ]);
        }
    }

    /**
     * The installer will send some info that are supposed to help understanding
     * the usage of the CMS. Of course no sensible information will be sent!
     * Info sent: Time and version of the CMS and of PHP. That's all.
     * Check the code if you do not trust this statement.
     * 
     * @return void
     */
    protected function sendStatistics()
    {
        $url = self::API_URL;
        $url .= '&type=installation';
        $url .= '&cms_version='.Config::get('app.version');
        $url .= '&php_version='.PHP_VERSION;

        // Use file_get_contents() to make the request,
        // so it will also work if CURL is not installed.
        file_get_contents($url);
    }
       
}
