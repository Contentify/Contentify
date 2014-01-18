<?php namespace App\Modules\Diag\Controllers;

use \Carbon\Carbon as Carbon;
use App, Config, View, BackController, File;

class AdminDiagController extends BackController {

    protected $icon = 'information.png';
    
    public function getIndex()
    {
        if (! $this->checkAccessRead()) return;

        $filename = app('path.base').'/bootstrap/compiled.php';
        if (File::exists($filename)) {
            
            $optimized = '1 - compiled: '.Carbon::createFromTimeStamp(filemtime($filename));
        } else {
            $optimized = 0;
        }

        $settings = array(
            'Artisan optimized' => $optimized,
            'App.environment'   => App::environment(),
            'App.url'           => Config::get('app.url'),
            'App.debug'         => (int) Config::get('app.debug'),
            'App.key'           => (Config::get('app.key') == '01234567890123456789012345678912' ? 'Dummy' : 'Updated'),
            'Modules.mode'      => Config::get('modules::mode'),
            'Modules.debug'     => (int) Config::get('modules::debug')
        );
    
        $this->pageView('diag::admin_index', compact('settings'));
    }
}