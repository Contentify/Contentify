<?php

class InstallController extends Controller {

    public function index() 
    {
        $this->createDatabase();
        $this->createSeed();
    }

    /**
     * Creates the database tables
     * 
     * @return void
     */
    public function createDatabase()
    {
        /*
         * Notice: The default length of strings is 255 chars.
         */
        
        Schema::dropIfExists('config');
        Schema::create('config', function($table)
        {
            $table->string('name', 255); // We cannot name it "key". "key" is a keyword in SQL.
            $table->primary('name');
            $table->text('value')->nullable();
            $table->dateTime('updated_at')->default(DB::RAW('"0000-00-00 00:00:00"'));
        });
        
        return; // DEBUG
        
        $this->create('comments', function($table)
        {
            $table->text('text')->nullable();
            $table->string('foreign_type', 30);
            $table->integer('foreign_id', false, true);
        }, array(), ['title', 'access_counter']);      

        $this->create('newscats', function($table)
        {
            $table->string('image')->nullable();
        });

        $this->create('news', function($table)
        {
            $table->text('intro')->nullable();
            $table->text('text')->nullable();
            $table->boolean('published')->default(false);
            $table->boolean('internal')->default(false);
            $table->boolean('allow_comments')->default(false);
        }, ['newscat']);    

        $this->create('games', function($table)
        {
            $table->string('short', 6)->nullable();
            $table->string('image')->nullable();
        });

        $this->create('contact_messages', function($table)
        {
            $table->string('username', 30);
            $table->string('email');
            $table->text('text');
            $table->string('ip');
            $table->boolean('new')->default(true);
        });
    }

    protected function createSeed() {
        DB::table('config')->insert(array('name' => 'app.analytics'));
        //$this->createUserGroups();
    }

    /**
     * Create user permision groups
     * 
     * @return void
     */
    protected function createUserGroups()
    {
        Sentry::createGroup(array(
            'name'        => 'Visitors',
            'permissions' => array()
        ));

        Sentry::createGroup(array(
            'name'        => 'Users',
            'permissions' => array(
                'frontend'  => true
            )
        ));

        Sentry::createGroup(array(
            'name'        => 'Members',
            'permissions' => array(
                'frontend'  => true,
                'internal'  => true,
            )
        ));

        Sentry::createGroup(array(
            'name'        => 'Admins',
            'permissions' => array(
                'frontend'  => true,
                'internal'  => true,
                'backend'   => true
            )
        ));

        Sentry::createGroup(array(
            'name'        => 'Super-Admins',
            'permissions' => array(
                'frontend'  => true,
                'internal'  => true,
                'backend'   => true
            )
        ));
    }

    /**
     * Helper functions. Creates a database table.
     * 
     * @param  string           $tableName      The name of the tbale
     * @param  Closure          $tableRows      A closure defining the table rows
     * @param  array            $foreignKeys    An array with names of foreign keys
     * @param  boolean|array    $contentObject  False or true or an array of attributes that won't be added (the array indicates true)
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
        Schema::table($tableName, $tableRows);

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
                if ($contentObject === true or ! in_array('creator_id', $contentObject)) {
                    $table->integer('creator_id')->unsigned()->default(0);
                    $table->foreign('creator_id')->references('id')->on('users');
                }
                if ($contentObject === true or ! in_array('updater_id', $contentObject)) {
                    $table->integer('updater_id')->unsigned()->default(0);
                    $table->foreign('updater_id')->references('id')->on('users');
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
                $table->integer($foreignKey.'_id')->unsigned();
                $table->foreign($foreignKey.'_id')->references('id')->on(str_plural($foreignKey));
            });
        }
    }
}