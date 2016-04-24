<?php namespace Contentify\Controllers;

use Str, File, Input, Validator, Sentinel, Form, Config, View, Schema, Artisan, DB, Controller, Closure;

class InstallController extends Controller {

    /**
     * (Relative) path to the file that indicates if the app is installed or not
     */
    const INSTALL_FILE = 'app/.install';

    /**
     * (Relative) path to the database ini file
     */
    const INI_FILE = 'app/database.ini';

    /**
     * Index action method
     * 
     * @param  integer                              $step   Step number
     * @param  null|Illuminate\Support\MessageBag   $errors Validation errors
     * @return View
     */
    public function index($step = -1, $errors = null) 
    {
        if (! Config::get('app.debug')) {
            die('Please enable debug mode to start the installer.');
        }

        if ($step < 0) {
            $step   = (int) Input::get('step', 0);
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

                // NOTE: It's not possible to integrate this into the register method
                $user->slug = Str::slug($user->username);
                $user->save();

                /*
                 * Add user to role "Super-Admins"
                 */
                $adminRole = Sentinel::findRoleBySlug('super-admins'); 
                $adminRole->users()->attach($user);
                
                /*
                 * Delete the file that indicates if the app is installed or not
                 */
                $filename = storage_path(self::INSTALL_FILE);
                if (File::exists($filename)) {
                    File::delete($filename);
                }

                $title      = 'Installation Complete';
                $content    = '<p>Congratulations, Contentify is ready to rumble.</p>';

                break;
            case 5:
                $title      = 'Create Super-Admin User';
                $content    = '<p>Fill in the details of your user account.</p>'.
                              '<div class="warning">'.Form::errors($errors).'</div>'.
                              Form::open(['url' => 'install?step='.($step + 1)]).
                              Form::smartText('username', 'Username').
                              Form::smartEmail().
                              Form::smartPassword().
                              Form::smartPassword('password_confirmation', 'Password').
                              Form::close();

                break;
            case 4:
                $host       = Input::get('host');
                $database   = Input::get('database');
                $username   = Input::get('username');
                $password   = Input::get('password');

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
                        'host'      => 'required', // We can't use the ip filter since it does not support ports
                        'database'  => 'alpha_dash|required',
                        'username'  => 'alpha_dash|required',
                    ]
                );

                if ($validator->fails()) {
                    return $this->index($step - 1, $validator->messages());
                }

                File::put(storage_path(self::INI_FILE), 
                    "; Autogenerated file with database connection settings.\n\r".
                    "; See config/database.php for more settings.\n\r\n\r".
                    "host = $host\n\r".
                    "database = $database\n\r".
                    "username = $username\n\r".
                    "password = $password"
                );

                $this->createDatabase();
                $this->createUserRoles();

                /*
                 * Create the daemon user (with ID = 1)
                 */
                $user = Sentinel::create(array(
                    'email'     => 'daemon@contentify.org',
                    'username'  => 'Daemon',
                    'password'  => Str::random(),
                    'activated' => false,
                ));

                // NOTE: It's not possible to integrate this into the register method
                $user->slug = Str::slug($user->username);
                $user->save();

                $this->createSeed();

                $title      = 'Database Setup Complete';
                $content    = '<p>Database filled with initial seed.</p>';

                break;
            case 3:
                // NOTE: Ignore the password fore security reasons
                $settings = ['host' => 'localhost', 'database' => 'contentify', 'username' => 'root'];

                $filename = storage_path(self::INI_FILE);
                if (File::exists($filename)) {
                    $settings = parse_ini_file($filename);
                }

                $title      = 'Database Setup';
                $content    = '<p>Fill in the database connection settings.</p>'.
                              '<div class="warning">'.Form::errors($errors).'</div>'.
                              Form::open(['url' => 'install?step='.($step + 1)]).
                              Form::smartText('host', 'Host', $settings['host']).
                              Form::smartText('database', 'Database', $settings['database']).
                              Form::smartText('username', 'Username', $settings['username']).
                              Form::smartText('password', 'Password').
                              Form::close().
                              '<p>For more settings, take a look at <code>config/database.php</code>.</p>';
               
                break;
            case 2:
                $writableDirs = [
                    base_path().'/storage', 
                    base_path().'/bootstrap/cache', 
                    public_path().'/uploads', 
                    public_path().'/rss',
                    public_path().'/share'
                ];

                $ul = '<ul>'; // HTML::ul() will encode HTML entities so we can't use it here
                foreach ($writableDirs as $dir) {
                    if (File::isWritable($dir)) {
                        $ul .= '<li>'.$dir.'<span class="state yes">Yes</span></li>';
                    } else {
                        $ul .= '<li>'.$dir.'<span class="state no">No</span></li>';
                    }
                }
                $ul .= '</ul>';

                $title      = 'Writable Directories';
                $content    = "<p>The application needs write access (CHMOD 777) to these directories 
                              and their sub directories:</p>
                              $ul
                              <p class=\"warning\">Please do not continue 
                              until all of these directories are writable!</p>";

                break;
            case 1:
                if (version_compare(PHP_VERSION, '5.5.9') >= 0) {
                    $version = '<span class="state yes">Yes, '.phpversion().'</span>';
                } else {
                    $version = '<span class="state no">No, '.phpversion().'</span>';
                }
                if (extension_loaded('openssl')) {
                    $openSsl = '<span class="state yes">Yes</span>';
                } else {
                    $openSsl = '<span class="state no">No</span>';
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

                $title      = 'Server Requirements';
                $content    = "<ul>
                              <li>PHP >= 5.5.9 $version</li>
                              <li>OpenSSL Extension $openSsl</li>
                              <li>Mbstring Extension $mbString</li>
                              <li>Tokenizer Extension $tokenizer</li>
                              </ul>
                              <p class=\"warning\">Please do not continue 
                              if your server does not meet these requirements!</p>";
                              
                break;
            default:
                $step       = 0; // Better save than sorry! (E.g. if step was -1)
                $title      = 'Welcome To Contentify 2';
                $content    = '<p>Please click on the "Next" button to start the installation.</p>
                              <p><a href="https://github.com/Contentify/Contentify/wiki/Installation" target="_blank">Take a look at our documentation 
                              (chapter "Installation") if you need help.</a></p>';
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
         * If possible (safe mode not enabled), set the execution time limit
         * to more than just the default 30 seconds.
         */
        if (! ini_get('safe_mode') and ini_get('max_execution_time') < 120) {
            set_time_limit(120);
        }

        /*
         * Run Sentinel migrations trough Artisan.
         * Unfortunately it's not the simple Artisan::call('migrate')
         * that it should be.
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
         * Deactivate foreign key checks.
         * This is one way to delete table with foreign constraints.
         * Usually it's not possible to delete a table that has an attribute
         * which is a foreign key of another table.
         * Note that this is session-based, there is also a
         * global way.
         */
        DB::statement('SET foreign_key_checks = 0');

        Schema::dropIfExists('config');
        Schema::create('config', function($table)
        {
            $table->string('name')->primary(); // We can't name it "key" - that's a keyword in SQL - Eloquent bug?
            $table->text('value')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
       
        $this->create('visits', function($table)
        {
            $table->string('ip');
            $table->integer('user_agents');
            $table->date('visited_at');
        }, [], false);

        $this->create('languages', function($table) 
        { 
            $table->string('title');
            $table->string('code', 2);
        }, [], false);

        $this->create('countries', function($table) 
        { 
            $table->string('code', 3);
            $table->string('icon')->nullable();
        }, [], ['slug']);
       
        $this->create('comments', function($table)
        {
            $table->text('text')->nullable();
            $table->string('foreign_type', 30);
            $table->integer('foreign_id', false, true)->nullable();
        }, [], ['title', 'slug', 'access_counter']);  

        $this->create('contact_messages', function($table)
        {
            $table->string('username', 30);
            $table->string('email');
            $table->text('text');
            $table->string('ip');
            $table->boolean('new')->default(true);
        }, [], ['slug', 'creator_id', 'updater_id']);

        $this->create('games', function($table)
        {
            $table->string('short', 6)->nullable();
            $table->string('icon')->nullable();
        }, [], ['slug']);

        $this->create('pagecats', function($table) { }, [], ['slug']); 

        $this->create('pages', function($table)
        {
            $table->text('text')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->boolean('internal')->default(false);
            $table->boolean('enable_comments')->default(false);
        }, ['pagecat_id']);

        $this->create('newscats', function($table)
        {
            $table->string('image')->nullable();
        }, [], ['slug']);

        $this->create('news', function($table)
        {
            $table->text('summary')->nullable();
            $table->text('text')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->boolean('internal')->default(false);
            $table->boolean('enable_comments')->default(false);
        }, ['newscat_id']);

        $this->create('galleries', function($table) { }); 
        
        $this->create('images', function($table)
        {
            $table->string('tags')->nullable();
            $table->string('image')->nullable();
            $table->integer('width')->default(0);
            $table->integer('height')->default(0);
        }, ['gallery_id'], ['slug']);

        $this->createPivot('team_user', function($table)
        {
            $table->string('task')->nullable();
            $table->text('description')->nullable();
            $table->integer('position')->default(0);
        }, ['user_id', 'team_id']);

        $this->create('teamcats', function($table) { }, [], ['slug']);

        $this->create('teams', function($table) 
        { 
            $table->text('text')->nullable();
            $table->string('image')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('published')->default(false);
        }, ['teamcat_id']);

        $this->create('advertcats', function($table) { }, [], ['slug']); 

        $this->create('adverts', function($table)
        {
            $table->text('code')->nullable();
            $table->string('url')->nullable();
            $table->boolean('published')->default(false);
            $table->string('image')->nullable();
        }, ['advertcat_id']);

        $this->create('partnercats', function($table) { }, [], ['slug']);

        $this->create('partners', function($table)
        {
            $table->text('text')->nullable();
            $table->string('url')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('published')->default(false);
            $table->string('image')->nullable();
        }, ['partnercat_id']);

        $this->create('videos', function($table)
        {
            $table->string('url')->nullable();
            $table->string('permanent_id')->nullable();
            $table->string('provider');
        });

        $this->create('downloadcats', function($table) { }); // Supports slugs

        $this->create('downloads', function($table) 
        { 
            $table->text('description')->nullable();
            $table->string('file')->nullable();
            $table->integer('file_size')->default(0);
            $table->boolean('is_image')->default(false);
        }, ['downloadcat_id']);        
        
        $this->create('slidecats', function($table) { }, [], ['slug']); 

        $this->create('slides', function($table)
        {
            $table->string('url')->nullable();
            $table->string('image')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('published')->default(false);
        }, ['slidecat_id'], ['slug']);

               
        $this->create('tournaments', function($table)
        {
            $table->string('short', 6)->nullable();
            $table->string('url')->nullable();
            $table->string('icon')->nullable();
        },  [], ['slug']);
        
        $this->create('awards', function($table)
        {
            $table->string('url')->nullable();
            $table->integer('position')->default(0);
            $table->timestamp('achieved_at')->nullable();
        }, ['game_id', 'tournament_id', 'team_id'], ['slug']);

        $this->create('opponents', function($table)
        {
            $table->string('short', 6)->nullable();
            $table->string('url')->nullable();
            $table->string('lineup')->nullable();
            $table->string('image')->nullable();
        }, ['country_id']);
        
        $this->create('maps', function($table)
        {
            $table->string('image')->nullable();
        }, ['game_id'], ['slug']);

        $this->create('match_scores', function($table)
        {
            $table->integer('left_score')->default(0);
            $table->integer('right_score')->default(0);
            $table->nullableTimestamps();
        }, ['match_id', 'map_id'], false);
 
        $this->create('matches', function($table)
        {
            $table->integer('state')->default(0);
            $table->boolean('featured')->default(false);
            $table->string('url')->nullable();
            $table->string('broadcast')->nullable();
            $table->string('left_lineup')->nullable();
            $table->string('right_lineup')->nullable();
            $table->text('text');
            $table->timestamp('played_at')->nullable();
            $table->integer('left_score')->default(0); // Total score
            $table->integer('right_score')->default(0);
        }, 
        ['left_team_id' => 'team_id', 'right_team_id' => 'opponent_id', 'game_id', 'tournament_id'], 
        ['title', 'slug']);

        $this->create('streams', function($table)
        {
            $table->string('url')->nullable();
            $table->string('permanent_id')->nullable();
            $table->string('provider');
            $table->string('thumbnail')->nullable();
            $table->boolean('online')->default(false);
            $table->integer('viewers')->default(0);
            $table->timestamp('renewed_at')->nullable();
        });

        $this->create('servers', function($table)
        {
            $table->string('ip');
            $table->string('hoster')->nullable();
            $table->integer('slots')->default(0);
            $table->boolean('published')->default(false);
        }, ['game_id'], ['slug']);

        $this->create('forums', function($table) 
        { 
            $table->text('description')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('internal')->default(false);
            $table->integer('level')->default(0);
            $table->integer('threads_count')->default(0);
            $table->integer('posts_count')->default(0);
        }, ['forum_id', 'latest_thread_id' => 'forum_thread_id', 'team_id']);

        $this->create('forum_threads', function($table) 
        { 
            $table->integer('posts_count')->default(1);
            $table->boolean('sticky')->default(false);
            $table->boolean('closed')->default(false);
        }, ['forum_id']);

        $this->create('forum_posts', function($table) 
        { 
            $table->text('text')->nullable();
            $table->boolean('root')->default(0);
        }, ['thread_id' => 'forum_threads_id'], ['slug', 'title']);

        $this->create('forum_reports', function($table) 
        { 
            $table->text('text')->nullable();
        }, ['post_id' => 'forum_posts_id'], ['title', 'slug']);

        $this->create('messages', function($table)
        {
            $table->text('text');
            $table->boolean('new')->default(true);
            $table->boolean('creator_visible')->default(true);
            $table->boolean('receiver_visible')->default(true);
            $table->boolean('sent_by_system')->default(false);
        }, ['receiver_id' => 'users_id']);

        $this->create('navigations', function($table)
        {
            $table->text('items')->nullable();
        }, [], ['slug']);

        $this->create('user_activities', function($table)
        {
            $table->boolean('frontend');
            $table->string('model_class')->nullable();
            $table->text('info')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('activity_id');
        }, ['user_id'], false);

        $this->create('shouts', function($table) 
        { 
            $table->text('text')->nullable();
        }, [], ['title', 'slug', 'access_counter', 'updater_id']);

        $this->createPivot('friends', function($table)
        {
            $table->boolean('confirmed')->default(false);
            $table->timestamp('messaged_at')->nullable();
        }, ['sender_id', 'receiver_id']);

        $this->create('events', function($table)
        {
            $table->text('text')->nullable();
            $table->string('url')->nullable();
            $table->string('location')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('starts_at')->nullable();
        });

        $this->create('ratings', function($table)
        {
            $table->integer('rating');
            $table->string('foreign_type', 30);
            $table->integer('foreign_id', false, true)->nullable();
        }, ['user_id'], false);

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
        $tables = ['newscats', 'partnercats', 'advertcats', 'downloadcats', 'slidecats'];
        $this->createDefaultCategories($tables);
        
        DB::table('config')->insert([
            ['name' => 'app.theme',             'value' => 'DefaultTheme'],
            ['name' => 'app.analytics',         'value' => ''],
            ['name' => 'app.https',             'value' => 0],
            ['name' => 'app.dbBackup',          'value' => 0],
            ['name' => 'auth::registration',    'value' => 1],
            ['name' => 'forums::reports',       'value' => 1],
            ['name' => 'app.twitter',           'value' => 'ContentifyCMS'],
            ['name' => 'app.facebook',          'value' => 'contentifycms'],
            ['name' => 'app.youtube',           'value' => 'UC2gIIZzySdgxrQ3jM4jmoqQ'],
        ]);

        DB::table('teamcats')->insert([
            ['id' => '1', 'title' => 'Staff', 'creator_id' => 1, 'updater_id' => 1],
            ['id' => '2', 'title' => 'Gaming', 'creator_id' => 1, 'updater_id' => 1],
        ]);

        DB::table('pagecats')->insert([
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
<br><br><i>From: </i><a href="http://www.twigg.de/" target="_blank">http://www.twigg.de/</a>',
            'published'     => true,
            'creator_id'    => 1,
            'updater_id'    => 1,
            'pagecat_id'    => 2,
            'created_at'    => DB::raw('NOW()'),
            'updated_at'    => DB::raw('NOW()'),
        ]);


        DB::table('languages')->insert([
            ['id' => '1', 'title' => 'English', 'code' => 'en'],
            ['id' => '2', 'title' => 'Deutsch', 'code' => 'de']
        ]);

        DB::insert('INSERT INTO countries(title, code, icon, creator_id, updater_id) VALUES
        ("European Union", "eu", "eu.png", 1, 1),
        ("Argentina", "ar", "ar.png", 1, 1),
        ("Australia", "au", "au.png", 1, 1),
        ("Austria", "at", "at.png", 1, 1),
        ("Belgium", "be", "be.png", 1, 1),
        ("Bosnia Herzegowina", "ba", "ba.png", 1, 1),
        ("Brazil", "br", "br.png", 1, 1),
        ("Bulgaria", "bg", "bg.png", 1, 1),
        ("Canada", "ca", "ca.png", 1, 1),
        ("Chile", "cl", "cl.png", 1, 1),
        ("China", "cn", "cn.png", 1, 1),
        ("Colombia", "co", "co.png", 1, 1),
        ("Czech Republic", "cz", "cz.png", 1, 1),
        ("Croatia", "hr", "hr.png", 1, 1),
        ("Cyprus", "cy", "cy.png", 1, 1),
        ("Denmark", "dk", "dk.png", 1, 1),
        ("Estonia", "ee", "ee.png", 1, 1),
        ("Finland", "fi", "fi.png", 1, 1),
        ("Faroe Islands", "fo", "fo.png", 1, 1),
        ("France", "fr", "fr.png", 1, 1),
        ("Germany", "de", "de.png", 1, 1),
        ("Greece", "gr", "gr.png", 1, 1),
        ("Hungary", "hu", "hu.png", 1, 1),
        ("Iceland", "is", "is.png", 1, 1),
        ("Ireland", "ie", "ie.png", 1, 1),
        ("Israel", "il", "il.png", 1, 1),
        ("Italy", "it", "it.png", 1, 1),
        ("Japan", "jp", "jp.png", 1, 1),
        ("Korea", "kr", "kr.png", 1, 1),
        ("Latvia", "lv", "lv.png", 1, 1),
        ("Lithuania", "lt", "lt.png", 1, 1),
        ("Luxemburg", "lu", "lu.png", 1, 1),
        ("Malaysia", "my", "my.png", 1, 1),
        ("Malta", "mt", "mt.png", 1, 1),
        ("Netherlands", "nl", "nl.png", 1, 1),
        ("Mexico", "mx", "mx.png", 1, 1),
        ("Mongolia", "mn", "mn.png", 1, 1),
        ("New Zealand", "nz", "nz.png", 1, 1),
        ("Norway", "no", "no.png", 1, 1),
        ("Poland", "pl", "pl.png", 1, 1),
        ("Portugal", "pt", "pt.png", 1, 1),
        ("Romania", "ro", "ro.png", 1, 1),
        ("Russian Federation", "ru", "ru.png", 1, 1),
        ("Singapore", "sg", "sg.png", 1, 1),
        ("Slovak Republic", "sk", "sk.png", 1, 1),
        ("Slovenia", "si", "si.png", 1, 1),
        ("Taiwan", "tw", "tw.png", 1, 1),
        ("South Africa", "za", "za.png", 1, 1),
        ("Spain", "es", "es.png", 1, 1),
        ("Sweden", "se", "se.png", 1, 1),
        ("Syria", "sy", "sy.png", 1, 1),
        ("Switzerland", "ch", "ch.png", 1, 1),
        ("Tunisia", "tn", "tn.png", 1, 1),
        ("Turkey", "tr", "tr.png", 1, 1),
        ("Ukraine", "ua", "ua.png", 1, 1),
        ("United Kingdom", "uk", "uk.png", 1, 1),
        ("USA", "us", "us.png", 1, 1)');

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
        ("Minecraft", "MS", "default/mc.png", 1, 1)');

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
     * Create user permission roles
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
                'comments'      => PERM_DELETE,
                'config'        => PERM_DELETE,
                'contact'       => PERM_DELETE,
                'countries'     => PERM_DELETE,
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
                'modules'       => PERM_READ, // So admins can't create modules that make them super admins
                'news'          => PERM_DELETE,
                'opponents'     => PERM_DELETE,
                'pages'         => PERM_DELETE,
                'partners'      => PERM_DELETE,
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
                'comments'      => PERM_DELETE,
                'config'        => PERM_DELETE,
                'contact'       => PERM_DELETE,
                'countries'     => PERM_DELETE,
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
                'news'          => PERM_DELETE,
                'opponents'     => PERM_DELETE,
                'pages'         => PERM_DELETE,
                'partners'      => PERM_DELETE,
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
     * @param  string           $tableName      The name of the tbale
     * @param  Closure          $tableRows      A closure defining the table rows
     * @param  array            $foreignKeys    An array with names of foreign keys
     * @param  boolean|array    $contentObject  Bool/array of attributes that won't be added (the array indicates true)
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
        Schema::create($tableName, function($table) use ($tableRows, $foreignKeys, $contentObject)
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
            if ($tableRows) $tableRows($table);

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
     * @param  string           $tableName      The name of the tbale
     * @param  Closure          $tableRows      A closure defining the table rows
     * @param  array            $primaryKeys    An array with the names of both primary keys
     * @return void
     */
    public function createPivot($tableName, Closure $tableRows, $primaryKeys = array())
    {
        /*
         * Delete existing table:
         */
        Schema::dropIfExists($tableName);

        /*
         * Add primary keys:
         */
        Schema::create($tableName, function($table) use ($primaryKeys)
        {
            $table->engine = 'InnoDB'; // Since we create the table here we ensure InnoDB is the storage engine

            /*
             * Add the primarey keys:
             */
            foreach ($primaryKeys as $primaryKey) {
                $table->integer($primaryKey)->unsigned();
            }
            $table->primary($primaryKeys);

        });

        /*
         * Add the table rows:
         */
        if ($tableRows) Schema::table($tableName, $tableRows);
    }

    /**
     * Creates one or more default categories
     * 
     * @param  array $tables Array of table names
     * @return void
     */
    public function createDefaultCategories($tables)
    {
        foreach ($tables as $table) {
            DB::table($table)->insert([
                [
                    'id'            => '1', 
                    'title'         => 'Default', 
                    'creator_id'    => 1, 
                    'updater_id'    => 1, 
                    'created_at'    => DB::raw('NOW()'),
                    'updated_at'    => DB::raw('NOW()'),
                ],
            ]);
        }        
    }
       
}