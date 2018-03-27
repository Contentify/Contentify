<?php

namespace Contentify\Commands;

use FormGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class MakeFormCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a form from a database table';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return string
     */
    public function fire()
    {
        $formGenerator = new FormGenerator;

        $filename = $this->argument('filename');
        $table    = $this->argument('table');
        $module   = $this->argument('module');

        $code = $formGenerator->generate($table, $module);

        $filename = __DIR__.'/../../resources/views/'.$filename.'.blade.php';
        file_put_contents($filename, $code);

        $this->info('Done. Form has been generated: '.$filename."\n");
        
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
            ['filename', InputArgument::REQUIRED, 'The file name (without extension) of the generated template.'],
            ['table', InputArgument::REQUIRED, 'The name of the desired table.'],
            ['module', InputArgument::OPTIONAL, 'The name of the desired module.']
        ];
    }

}
