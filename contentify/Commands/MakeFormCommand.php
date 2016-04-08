<?php namespace Contentify\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use FormGenerator;

class MakeFormCommand extends Command {

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
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $formGenerator = new FormGenerator;

        $fileName   = $this->argument('fileName');
        $table      = $this->argument('table');
        $module     = $this->argument('module');

        $code = $formGenerator->generate($table, $module);

        $fileName = __DIR__.'/../../resources/views/'.$fileName.'.blade.php';
        file_put_contents($fileName, $code);

        $this->info('Done. Form has been generated: '.$fileName."\n");  
        
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
            ['fileName', InputArgument::REQUIRED, 'The file name (without extension) of the generated template.'],
            ['table', InputArgument::REQUIRED, 'The name of the desired table.'],
            ['module', InputArgument::OPTIONAL, 'The name of the desired module.']
        ];
    }

}
