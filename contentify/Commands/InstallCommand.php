<?php

namespace Contentify\Commands;

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

      
        
        echo $code;
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
