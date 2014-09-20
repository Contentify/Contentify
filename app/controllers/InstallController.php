<?php

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
            case 5:
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
            case 4:
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
            case 3:
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
                 * Add user to group "Users"
                 */
                /*
                $adminGroup = Sentry::findGroupById(2); 
                $user->addGroup($adminGroup);
                */

                $title      = 'Database Setup Complete';
                $content    = '<p>Database filled with initial seed.</p>';
                break;
            case 2:
                $dbCon      = Config::get('database.default');
                $dbName     = Config::get("database.connections.{$dbCon}.database");
                $title      = 'Database Setup';
                $content    = '<p>Contentify will now setup the database.</p>
                              <p>Before you proceed make sure you have updated the database connection settings.</p>
                              <p>The current database name is: <br><code>'.$dbName.'</code></p>';
                break;
            case 1:
                $title      = 'Server Requirements';
                $content    = '<ul><li>PHP >= 5.4.0</li><li>MCrypt PHP Extension</li></ul>
                              <p class="warning">Please don\'t continue 
                              if your server does not meet these requirements!</p>';
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

        return; // DEBUG
        
        $this->create('downloadcats', function($table) { }); // Supports slugs

        $this->create('downloads', function($table) 
        { 
            $table->text('description')->nullable();
            $table->string('file')->nullable();
            $table->integer('file_size')->default(0);
            $table->boolean('is_image')->default(false);
        }, ['downloadcat_id']);        
        
        $this->create('slidecats', function($table) { },  [], ['slug']); 

        $this->create('slides', function($table)
        {
            $table->string('url')->nullable();
            $table->string('image')->nullable();
        }, ['slidecat_id'], ['slug']);

        $this->create('videos', function($table)
        {
            $table->string('url')->nullable();
            $table->string('permanent_id')->nullable();
            $table->integer('position')->default(0);
        });

        $this->create('pagecats', function($table) { });

        $this->create('pages', function($table)
        {
            $table->text('text')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamp('published_at');
            $table->boolean('internal')->default(false);
            $table->boolean('enable_comments')->default(false);
        }, ['pagecat_id']);

        $this->create('advertcats', function($table) { },  [], ['slug']); 

        $this->create('adverts', function($table)
        {
            $table->text('code')->nullable();
            $table->string('url')->nullable();
            $table->string('image')->nullable();
        }, ['advertcat_id']);

        $this->create('partnercats', function($table) { },  [], ['slug']);

        $this->create('partners', function($table)
        {
            $table->text('text')->nullable();
            $table->string('url')->nullable();
            $table->integer('position')->default(0);
            $table->string('image')->nullable();
        }, ['partnercat_id']);

        $this->createPivot('team_user', function($table)
        {
            $table->string('task')->nullable();
            $table->text('description')->nullable();
            $table->integer('position')->default(0);
        }, ['user_id', 'team_id']);

        $this->create('teamcats', function($table) { },  [], ['slug']);

        $this->create('teams', function($table) 
        { 
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('position')->default(0);
        }, ['teamcat_id']);
               
        $this->create('languages', function($table) 
        { 
            $table->string('code', 2);
        });

        $this->create('countries', function($table) 
        { 
            $table->string('code', 3);
            $table->string('icon')->nullable();
        });

        $this->create('galleries', function($table) { }); 
        
        $this->create('images', function($table)
        {
            $table->string('tags')->nullable();
            $table->string('image')->nullable();
        }, array(), ['title', 'slug']);
       
        $this->create('visits', function($table)
        {
            $table->string('ip');
            $table->integer('user_agents');
            $table->date('visited_at');
        }, array(), false);
        
        Schema::dropIfExists('config');
        Schema::create('config', function($table)
        {
            $table->string('name', 255); // We can't name it "key" - it's a keyword in SQL. Eloquent can't handle it(?)
            $table->primary('name');
            $table->text('value')->nullable();
            $table->timestamp('updated_at');
        });
        
        $this->create('comments', function($table)
        {
            $table->text('text')->nullable();
            $table->string('foreign_type', 30);
            $table->integer('foreign_id', false, true)->nullable();
        }, array(), ['title', 'slug', 'access_counter']);      

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

        $this->create('games', function($table)
        {
            $table->string('short', 6)->nullable();
            $table->string('icon')->nullable();
        }, [], ['slug']);

        $this->create('contact_messages', function($table)
        {
            $table->string('username', 30);
            $table->string('email');
            $table->text('text');
            $table->string('ip');
            $table->boolean('new')->default(true);
        });
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
                'news'          => PERM_DELETE,
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
                'modules'       => PERM_DELETE,
                'news'          => PERM_DELETE,
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
     * Helper functions. Creates a database table.
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
        Schema::create($tableName, function($table)
        {
            $table->engine = 'InnoDB'; // Since we create the table here we ensure InnoDB is used as storage engine

            $table->increments('id'); // Primary key (unique, auto-increment)
        });

        /*
         * Add the table rows:
         */
        if ($tableRows) Schema::table($tableName, $tableRows);

        /*
         * Add the content object attributes:
         */
        if ($contentObject) {
            Schema::table($tableName, function($table) use ($contentObject)
            {
                if ($contentObject === true or ! in_array('title', $contentObject)) {
                    /*
                     * We can use after() to insert he title attribute right after id.
                     * But after() only workws with MySQL databases so we have to check that:
                     */
                    if (strtolower(DB::connection()->getDriverName()) == 'mysql') {
                        $table->string('title', 70)->after('id');
                    } else {
                        $table->string('title', 70);
                    }
                }
                if ($contentObject === true or ! in_array('slug', $contentObject)) {
                    if (strtolower(DB::connection()->getDriverName()) == 'mysql') {
                        $table->string('slug')->unique()->after('id');
                    } else {
                        $table->string('slug')->unique();
                    }
                }
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
                $table->timestamps();
            });

            /*
             * Add soft deletes:
             */ 
            Schema::table($tableName, function($table)
            {
                $table->softDeletes();
            });
        }

        /*
         * Add the foreign keys:
         */
        foreach ($foreignKeys as $foreignKey) {
            Schema::table($tableName, function($table) use ($foreignKey)
            {
                $table->integer($foreignKey)->unsigned();
                //$table->foreign($foreignKey)->references('id')->on(str_plural($foreignKey));
            });
        }
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
            $table->engine = 'InnoDB'; // Since we create the table here we ensure InnoDB is used as storage engine

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