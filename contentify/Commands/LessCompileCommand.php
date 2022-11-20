<?php

namespace Contentify\Commands;

use Config;
use HTML;
use File;
use Illuminate\Console\Command;
use Less_Parser;

class LessCompileCommand extends Command
{

    /**
     * Name of the event that is fired after the website settings have been updated
     */
    const EVENT_NAME_LESS_COMPILED = 'contentify.lessCompileCommand.lessCompiled';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'less:compile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Compile the theme's frontend and the backend LESS files to CSS files";

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Compiling LESS files...');

        $theme = Config::get('app.theme');

        // Filenames of the source files (with path and with ".less" as extension!)
        $lessFiles = [
            Config::get('modules.path').'/'.$theme.'/Resources/Assets/less/frontend.less',
            resource_path('assets/less/backend.less'),
        ];

        foreach ($lessFiles as $sourceFilename) {
            $this->compileLessFile($sourceFilename);
        }

        event(self::EVENT_NAME_LESS_COMPILED, [$lessFiles]);

        HTML::refreshAssetPaths();
    }

    /**
     * Compiles a LESS file to a CSS file.
     * If debug mode is NOT active, also compresses the output CSS file.
     *
     * @param string $sourceFilename
     * @return void
     * @throws \Exception
     */
    protected function compileLessFile(string $sourceFilename)
    {
        $sourceFileTitle = basename($sourceFilename, '.less');
        $target = public_path('css/'.$sourceFileTitle.'.css');

        // Only compile the file if it has changed
        if (! File::exists($target) or File::lastModified($sourceFilename) > File::lastModified($target)) {
            $debug = Config::get('app.debug');

            // Create a new instance for each file - or call the reset method
            $parser = new Less_Parser(['compress' => ! $debug]);

            $parser->parseFile($sourceFilename);

            file_put_contents($target, $parser->getCss());

            $this->info('CSS files has been compiled: ' . $sourceFilename . ' -> ' . $target . "\n");
        }
    }

}
