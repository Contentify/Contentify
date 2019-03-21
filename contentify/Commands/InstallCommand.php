<?php

namespace Contentify\Commands;

use Config;
use Contentify\Installer;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class InstallCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs Contentify - no user input required';
    
    /**
     * The installer object
     *
     * @var Installer
     */
    protected $installer;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->installer = new Installer();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? time().'@contentify.org';
        $username = $this->argument('username') ?? 'superadmin';

        // Check preconditions
        require public_path('install.php');
        
        // Say hello
        $this->info('Welcome to Contentify '.Config::get('app.version'). ' installer!');
        $this->info('This command installs Contentify without asking for user input.');
        $this->info('Therefore it does not change the database credentials.');
        
        // Create database and seed it
        $this->info('Creating database...');
        $this->installer->createDatabase();
        $this->info('Creating user roles...');
        $this->installer->createUserRoles();
        $this->info('Creating daemon user...');
        $this->installer->createDaemonUser();
        $this->info('General seeding...');
        $this->installer->createSeed();
        
        // Create super admin account
        $this->info('Creating super admin user...');
        $password = Str::random(10); // TODO Use better way to generate password
        $this->installer->createAdminuser($username, $email, $password, $password);
        $this->info("Username: $username, email: $email, password: $password");
        
        // Say goodbye
        $this->info('Installation complete!');
        $this->installer->markAsInstalled();
        $this->installer->sendStatistics();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['email', InputArgument::OPTIONAL, 'The email of the super admin account.']
        ];
    }

}
