<?php

function dd($var)
{
    die(var_dump($var));
}

chdir(__DIR__.'/../app/Modules');

$modulesDir = getcwd();

$moduleDirs = scandir($modulesDir);

foreach ($moduleDirs as $moduleDir) {
    if ($moduleDir != '.' and $moduleDir != '..') {
        $firstLevelDirs = scandir($modulesDir.'/'.$moduleDir);

        foreach ($firstLevelDirs as $item) {
            if ($item != '.' and $item != '..') {
                $pos = strpos($item, '.');

                $httpDir = $modulesDir.'/'.$moduleDir.'/Http';
                $resDir = $modulesDir.'/'.$moduleDir.'/Resources';

                switch ($item) {
                    case 'controllers':
                        if (! file_exists($httpDir)) {
                            mkdir($httpDir);
                        }
                        rename($modulesDir.'/'.$moduleDir.'/controllers', $modulesDir.'/'.$moduleDir.'/Http/Controllers');

                        $controllers = scandir($modulesDir.'/'.$moduleDir.'/Http/Controllers');

                        foreach ($controllers as $controller) {
                            if ($controller != '.' and $controller != '..') {
                                $fileName = $modulesDir.'/'.$moduleDir.'/Http/Controllers/'.$controller;
                                $lines = file($fileName);

                                $content = '<?php namespace App\Modules\\'.ucfirst($moduleDir)."\Http\Controllers;\n\r";

                                $lines[0] = '';
                                foreach ($lines as $line) {
                                    $content .= $line;
                                }
                                
                                file_put_contents($fileName, $content);
                            }
                        }

                        break;
                    case 'views':
                        if (! file_exists($resDir)) {
                            mkdir($resDir);
                        }
                        rename($modulesDir.'/'.$moduleDir.'/views', $modulesDir.'/'.$moduleDir.'/Resources/Views');
                        break;
                    case 'models':
                        $modelsDir = $modulesDir.'/'.$moduleDir.'/models';
                        $models = scandir($modelsDir);

                        foreach ($models as $model) {
                            if ($model != '.' and $model != '..') {
                                $fileName = $modulesDir.'/'.$moduleDir.'/'.$model;

                                rename($modelsDir.'/'.$model, $fileName);
                                
                                $lines = file($fileName);

                                $content = '<?php namespace App\Modules\\'.ucfirst($moduleDir).";\n\r";

                                $lines[0] = '';
                                foreach ($lines as $line) {
                                    $content .= $line;
                                }
                                
                                file_put_contents($fileName, $content);
                            }
                        }

                        rmdir($modelsDir);
                        break;
                    case 'lang':
                        if (! file_exists($resDir)) {
                            mkdir($resDir);
                        }
                        rename($modulesDir.'/'.$moduleDir.'/lang', $modulesDir.'/'.$moduleDir.'/Resources/Lang');
                        break;
                    case 'routes.php':
                        if (! file_exists($httpDir)) {
                            mkdir($httpDir);
                        }

                        $fileName = $modulesDir.'/'.$moduleDir.'/Http/routes.php';
                        rename($modulesDir.'/'.$moduleDir.'/routes.php', $fileName);

                        $lines = file($fileName);

                        $lines[2] = "ModuleRoute::context('".ucfirst($moduleDir)."');\n\r";

                        $content = '';
                        foreach ($lines as $line) {
                            $content .= $line;
                        }
                        
                        file_put_contents($fileName, $content);

                        break;
                    case 'module.json':
                        $fileName = $modulesDir.'/'.$moduleDir.'/module.json';
                        $content = file_get_contents($fileName);

                        $content = substr($content, 1);
                        $content = "{\n\r    \"slug\": \"".ucfirst($moduleDir)."\",".$content;
                        
                        file_put_contents($fileName, $content);

                        break;
                    case 'Installer.php':
                        // Just leave it at its place
                        break;
                    default:
                        echo 'Unknown item: "'.$moduleDir.'/'.$item.'"<br>';
                }
            }
        }

        $providerDir = $modulesDir.'/'.$moduleDir.'/Providers';
        if (! file_exists($providerDir)) {
            mkdir($providerDir);

            $content = '<?php namespace App\Modules\\'.ucfirst($moduleDir).'\Providers;

use Illuminate\Support\ServiceProvider;
use App, Lang, View;

class '.ucfirst($moduleDir).'ServiceProvider extends ServiceProvider {

    public function register()
    {
        App::register(\'App\Modules\\'.ucfirst($moduleDir).'\Providers\RouteServiceProvider\');

        Lang::addNamespace(\''.$moduleDir.'\', realpath(__DIR__.\'/../Resources/Lang\'));

        View::addNamespace(\''.$moduleDir.'\', realpath(__DIR__.\'/../Resources/Views\'));
    }

}';

            file_put_contents($providerDir.'/'.ucfirst($moduleDir).'ServiceProvider.php', $content);

            $content = '<?php namespace App\Modules\\'.ucfirst($moduleDir).'\Providers;

use Caffeinated\Modules\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider {

    public function map(Router $router)
    {
        $router->group([\'namespace\' => $this->namespace], function($router)
        {
            require (config(\'modules.path\').\'/'.ucfirst($moduleDir).'/Http/routes.php\');
        });
    }

}';

            file_put_contents($providerDir.'/RouteServiceProvider.php', $content);
        }

        rename($moduleDir, ucfirst($moduleDir));
    }
}

dd($moduleDirs);

