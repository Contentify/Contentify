<?php namespace Contentify\Controllers;

use File, Input, Validator, Sentry, Form, Config, View, Schema, Artisan, DB, Controller, Closure;

class InstallController extends Controller {

    /**
     * Index action method
     * 
     * @param  integer                              $step   Step number
     * @param  null|Illuminate\Support\MessageBag   $errors Validation errors
     * @return View
     */
    public function index($step = -1, $errors = null) 
    {
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
                        'username'  => "alpha_spaces|required|min:3|not_in:edit,password,daemon",
                        'email'     => 'email|required|unique:users,email',
                        'password'  => 'required|min:6|confirmed',
                    ]
                );

                if ($validator->fails()) {
                    return $this->index(4, $validator->messages());
                }

                /*
                 * Create the admin user (with ID = 2)
                 */
                $user = Sentry::register(array(
                    'email'     => $email,
                    'password'  => $password,
                    'username'  => $username,
                ), true);

                /*
                 * Add user to group "Admins"
                 */
                $adminGroup = Sentry::findGroupById(5); 
                $user->addGroup($adminGroup);

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
                $this->createDatabase();
                //$this->createSeed();

                /*
                 * Create the deamon user (with ID = 1)
                 */
                /*
                $user = Sentry::createUser(array(
                    'email'     => 'daemon@contentify.it',
                    'username'  => 'Daemon',
                    //'password'  => Str::random(),
                    'activated' => false,
                ));
                */

                /*
                 * Add user to group "Super-Admins"
                 */
                /*
                $superAdminGroup = Sentry::findGroupById(5); 
                $user->addGroup($superAdminGroup);
                */

                $title      = 'Database Setup Complete';
                $content    = '<p>Database filled with initial seed.</p>';
                break;
            case 3:
                $dbCon      = Config::get('database.default');
                $dbName     = Config::get("database.connections.{$dbCon}.database");
                $title      = 'Database Setup';
                $content    = '<p>Contentify will now setup the database.</p>
                              <p>Before you proceed make sure you have updated the database connection settings.</p>
                              <p>The current database name is: <br><code>'.$dbName.'</code></p>';
                break;
            case 2:
                $writableDirs = [app_path().'/storage', public_path().'/uploads'];

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
                if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
                    $version = '<span class="state yes">Yes, '.phpversion().'</span>';
                } else {
                    $version = '<span class="state no">No, '.phpversion().'</span>';
                }
                if (extension_loaded('mcrypt')) {
                    $mCrypt = '<span class="state yes">Yes</span>';
                } else {
                    $mCrypt = '<span class="state no">No</span>';
                }
                if (extension_loaded('fileinfo')) {
                    $fileInfo = '<span class="state yes">Yes</span>';
                } else {
                    $fileInfo = '<span class="state no">No</span>';
                }

                $title      = 'Server Requirements';
                $content    = "<ul>
                              <li>PHP >= 5.4.0 $version</li>
                              <li>MCrypt Extension $mCrypt</li>
                              <li>FileInfo Extension $fileInfo</li>
                              </ul>
                              <p class=\"warning\">Please do not continue 
                              if your server does not meet these requirements!</p>";
                break;
            default:
                $step       = 0; // Better save than sorry! (E.g. if step was -1)
                $title      = 'Welcome To Contentify';
                $content    = '<p>Please click on the "Next" button to start the installation.</p>
                              <p><a href="http://contentify.it/docs" target="_blank">Take a look at our documentation 
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
         * - We recommend to use timestamp() to create a datetime attribute.
         */
 
        return; // DEBUG

        Schema::dropIfExists('config');
        Schema::create('config', function($table)
        {
            $table->string('name'); // We can't name it "key" - it's a keyword in SQL. Eloquent can't handle it(?)
            $table->primary('name');
            $table->text('value')->nullable();
            $table->timestamp('updated_at');
        });
       
        $this->create('visits', function($table)
        {
            $table->string('ip');
            $table->integer('user_agents');
            $table->date('visited_at');
        }, array(), false);

        $this->create('languages', function($table) 
        { 
            $table->string('code', 2);
        });

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
        }, array(), ['title', 'slug', 'access_counter']);  

        $this->create('contact_messages', function($table)
        {
            $table->string('username', 30);
            $table->string('email');
            $table->text('text');
            $table->string('ip');
            $table->boolean('new')->default(true);
        });

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
            $table->timestamp('published_at');
            $table->boolean('internal')->default(false);
            $table->boolean('enable_comments')->default(false);
        }, ['pagecat_id']);

        $this->create('newscats', function($table)
        {
            $table->string('image')->nullable();
        }, [], ['slug']);

        $this->create('news', function($table)
        {
            $table->text('intro')->nullable();
            $table->text('text')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamp('published_at');
            $table->boolean('internal')->default(false);
            $table->boolean('enable_comments')->default(false);
        }, ['newscat_id']);

        $this->create('galleries', function($table) { }); 
        
        $this->create('images', function($table)
        {
            $table->string('tags')->nullable();
            $table->string('image')->nullable();
        }, array(), ['title', 'slug']);

        $this->createPivot('team_user', function($table)
        {
            $table->string('task')->nullable();
            $table->text('description')->nullable();
            $table->integer('position')->default(0);
        }, ['user_id', 'team_id']);

        $this->create('teamcats', function($table) { }, [], ['slug']);

        $this->create('teams', function($table) 
        { 
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('position')->default(0);
        }, ['teamcat_id']);

        $this->create('advertcats', function($table) { }, [], ['slug']); 

        $this->create('adverts', function($table)
        {
            $table->text('code')->nullable();
            $table->string('url')->nullable();
            $table->string('image')->nullable();
        }, ['advertcat_id']);

        $this->create('partnercats', function($table) { }, [], ['slug']);

        $this->create('partners', function($table)
        {
            $table->text('text')->nullable();
            $table->string('url')->nullable();
            $table->integer('position')->default(0);
            $table->string('image')->nullable();
        }, ['partnercat_id']);

        $this->create('videos', function($table)
        {
            $table->string('url')->nullable();
            $table->string('permanent_id')->nullable();
            $table->integer('position')->default(0);
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
        }, ['slidecat_id'], ['slug']);

               
        $this->create('tournaments', function($table)
        {
            $table->string('short', 6)->nullable();
            $table->string('icon')->nullable();
        },  [], ['slug']);
        
        $this->create('awards', function($table)
        {
            $table->string('url')->nullable();
            $table->integer('position')->default(0);
            $table->timestamp('achieved_at');
        }, ['game_id', 'tournament_id'], ['slug']);

        $this->create('opponents', function($table)
        {
            $table->string('short', 6)->nullable();
            $table->string('url')->nullable();
            $table->string('lineup');
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
            $table->timestamp('played_at');
            $table->integer('left_score')->default(0); // Total score
            $table->integer('right_score')->default(0);
        }, 
        ['left_team_id' => 'team_id', 'right_team_id' => 'team_id', 'game_id', 'tournament_id'], 
        ['title', 'slug']);

        /*
         * Run migrations trough Artisan.
         * Unfortunately it's not the simple Artisan::call('migrate')
         * it should be.
         */
        define('STDIN', fopen('php://stdin', 'r'));
        Artisan::call('migrate', ['--quiet' => true, '--force' => true]);
    }

    /**
     * Creates the initial database seed
     * 
     * @return void
     */
    protected function createSeed() {
        //DB::table('config')->insert(['name' => 'app.analytics']);
        // DEBUG
        
        //$this->createUserGroups();

        DB::table('pagecats')->insert([
            ['id' => '1', 'title' => 'Blog Post'],
            ['id' => '2', 'title' => 'Custom Page'],
            ['id' => '3', 'title' => 'Custom Content'],
        ]);

        DB::table('teamcats')->insert([
            ['id' => '1', 'title' => 'Staff'],
            ['id' => '2', 'title' => 'Gaming'],
        ]);

        DB::table('languages')->insert([
            ['id' => '1', 'title' => 'English', 'code' => 'en']
        ]);

        DB::insert('INSERT INTO countries(title, code, icon) VALUES
        ("European Union", "eu", "eu.png"),
        ("Argentina", "ar", "ar.png"),
        ("Australia", "au", "au.png"),
        ("Austria", "at", "at.png"),
        ("Belgium", "be", "be.png"),
        ("Bosnia Herzegowina", "ba", "ba.png"),
        ("Brazil", "br", "br.png"),
        ("Bulgaria", "bg", "bg.png"),
        ("Canada", "ca", "ca.png"),
        ("Chile", "cl", "cl.png"),
        ("China", "cn", "cn.co"),
        ("Colombia", "co", "co.png"),
        ("Czech Republic", "cz", "cz.png"),
        ("Croatia", "hr", "hr.png"),
        ("Cyprus", "cy", "cy.png"),
        ("Denmark", "dk", "dk.png"),
        ("Estonia", "ee", "ee.png"),
        ("Finland", "fi", "fi.png"),
        ("Faroe Islands", "fo", "fo.png"),
        ("France", "fr", "fr.png"),
        ("Germany", "de", "de.png"),
        ("Greece", "gr", "gr.png"),
        ("Hungary", "hu", "hu.png"),
        ("Iceland", "is", "is.png"),
        ("Ireland", "ie", "ie.png"),
        ("Israel", "il", "il.png"),
        ("Italy", "it", "it.png"),
        ("Japan", "jp", "jp.png"),
        ("Korea", "kr", "kr.png"),
        ("Latvia", "lv", "lv.png"),
        ("Lithuania", "lt", "lt.png"),
        ("Luxemburg", "lu", "lu.png"),
        ("Malaysia", "my", "my.png"),
        ("Malta", "mt", "mt.png"),
        ("Netherlands", "nl", "nl.png"),
        ("Mexico", "mx", "mx.png"),
        ("Mongolia", "mn", "mn.png"),
        ("New Zealand", "nz", "nz.png"),
        ("Norway", "no", "no.png"),
        ("Poland", "pl", "pl.png"),
        ("Portugal", "pt", "pt.png"),
        ("Romania", "ro", "ro.png"),
        ("Russian Federation", "ru", "ru.png"),
        ("Singapore", "sg", "sg.png"),
        ("Slovak Republic", "sk", "sk.png"),
        ("Slovenia", "si", "si.png"),
        ("Taiwan", "tw", "tw.png"),
        ("South Africa", "za", "za.png"),
        ("Spain", "es", "es.png"),
        ("Sweden", "se", "se.png"),
        ("Syria", "sy", "sy.png"),
        ("Switzerland", "ch", "ch.png"),
        ("Tibet", "ti", "ti.png"),
        ("Tunisia", "tn", "tn.png"),
        ("Turkey", "tr", "tr.png"),
        ("Ukraine", "ua", "ua.png"),
        ("United Kingdom", "uk", "uk.png"),
        ("USA", "us", "us.png"),
        ("Venezuela", "ve", "ve.png")');

        DB::insert('INSERT INTO games(title, short, icon) VALUES
        ("Counter-Strike: Global Offensive", "CS:GO", "default/csgo.png"),
        ("Counter-Strike: Source", "CS:S", "default/css.gif"),
        ("Counter-Strike 1.6", "CS", "default/cs.gif"),
        ("Call of Duty", "CoD", "default/cod.png"),
        ("Battlefield", "BF", "default/bf.png"),
        ("Unreal Tournament 3", "UT3", "default/ut3.png"),
        ("Left 4 Dead", "L4D", "default/l4d.png"),
        ("Crysis", "Crysis", "default/crysis.png"),
        ("Quake", "Quake", "default/quake.gif"),
        ("StarCraft II", "SC2", "default/sc2.png"),
        ("Warcraft III", "WC3", "default/wc3.gif"),
        ("Diablo III", "D3", "default/d3.png"),
        ("DotA 2", "DotA", "default/dota2.png"),
        ("League of Legends", "LoL", "default/lol.png"),
        ("World of Warcraft", "WoW", "default/wow.png"),
        ("World of Tanks", "WoT", "default/wot.png"),
        ("Trackmania", "TM", "default/tm.gif"),
        ("FIFA", "FIFA", "default/fifa.gif"),
        ("Minecraft", "MS", "default/mc.png")');

        DB::insert('INSERT INTO maps(title, game_id) VALUES
        ("Unknown", NULL),
        ("de_dust", 1),
        ("de_dust2", 1),
        ("de_inferno", 1),
        ("de_train", 1),
        ("de_cbble", 1),
        ("de_nuke", 1),
        ("de_inferno", 1),
        ("de_cache", 1),
        ("de_mirage", 1)');

        DB::insert('INSERT INTO tournaments(title, short) VALUES
        ("Electronic Sports League", "ESL"),
        ("E-Sports Entertainment Association", "ESEA"),
        ("Major League Gaming", "MLG"),
        ("Electronic Sports World Cup", "ESWC"),
        ("Dreamhack", "DH")');
    }

    /**
     * Create user permision groups
     * 
     * @return void
     */
    protected function createUserGroups()
    {
        Sentry::createGroup([
            'name'        => 'Visitors',
            'permissions' => array()
        ]);

        Sentry::createGroup([
            'name'        => 'Users',
            'permissions' => [
                'frontend'  => true,
                'comments'  => PERM_CREATE, // Users can also update and delete their own comments
            ]
        ]);

        Sentry::createGroup([
            'name'        => 'Members',
            'permissions' => [
                'frontend'  => true,
                'internal'  => true,
                'comments'  => PERM_CREATE,
            ]
        ]);

        Sentry::createGroup([
            'name'        => 'Admins',
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
                'diag'          => PERM_DELETE,
                'downloads'     => PERM_DELETE,
                'galleries'     => PERM_DELETE,
                'games'         => PERM_DELETE,
                'groups'        => PERM_DELETE,
                'help'          => PERM_DELETE,
                'images'        => PERM_DELETE,
                'maps'          => PERM_DELETE,
                'matches'       => PERM_DELETE,
                'modules'       => PERM_READ, // So Admins can't create modules that make them Super-Admins
                'news'          => PERM_DELETE,
                'opponents'     => PERM_DELETE,
                'pages'         => PERM_DELETE,
                'partners'      => PERM_DELETE,
                'slides'        => PERM_DELETE,
                'teams'         => PERM_DELETE,
                'tournaments'   => PERM_DELETE,
                'users'         => PERM_DELETE,
                'videos'        => PERM_DELETE,
            ]
        ]);

        Sentry::createGroup([
            'name'        => 'Super-Admins',
            'permissions' => [
                'frontend'      => true,
                'internal'      => true,
                'backend'       => true,
                'superuser'     => true,
                'adverts'       => PERM_DELETE,
                'auth'          => PERM_DELETE,
                'awards'        => PERM_DELETE,
                'comments'      => PERM_DELETE,
                'config'        => PERM_DELETE,
                'contact'       => PERM_DELETE,
                'diag'          => PERM_DELETE,
                'downloads'     => PERM_DELETE,
                'galleries'     => PERM_DELETE,
                'games'         => PERM_DELETE,
                'groups'        => PERM_DELETE,
                'help'          => PERM_DELETE,
                'images'        => PERM_DELETE,
                'maps'          => PERM_DELETE,
                'matches'       => PERM_DELETE,
                'modules'       => PERM_DELETE,
                'news'          => PERM_DELETE,
                'opponents'     => PERM_DELETE,
                'pages'         => PERM_DELETE,
                'partners'      => PERM_DELETE,
                'slides'        => PERM_DELETE,
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
    private function create($tableName, Closure $tableRows, $foreignKeys = array(), $contentObject = true)
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
                //$foreignTable = str_plural(substr($remoteKey, 0, -3));
                //$table->foreign($localKey)->references('id')->on($foreignTable);
            }

            /*
             * Add content object attributes:
             */
            if ($contentObject) {
                if ($contentObject === true or ! in_array('creator_id', $contentObject)) {
                    $table->integer('creator_id')->unsigned()->default(0);
                    //$table->foreign('creator_id')->references('id')->on('users');
                }
                if ($contentObject === true or ! in_array('updater_id', $contentObject)) {
                    $table->integer('updater_id')->unsigned()->default(0);
                    //$table->foreign('updater_id')->references('id')->on('users');
                }
                if ($contentObject === true or ! in_array('access_counter', $contentObject)) {
                    $table->integer('access_counter')->default(0);
                }

                $table->timestamps(); // Add timestamps (columns created_at, updated_at)

                $table->softDeletes(); // Add soft deletes (column deleted_at)
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
    private function createPivot($tableName, Closure $tableRows, $primaryKeys = array())
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
       
}