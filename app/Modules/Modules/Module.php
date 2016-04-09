<?php namespace App\Modules\Modules;

use Exception, File, BaseModel;

class Module extends BaseModel {

    protected $fillable = ['title'];

    /**
     * Returns an array with models for all existing modules
     * 
     * @return array
     */
    public static function findAll()
    {
        $dirs = File::directories(app_path().'/Modules');

        array_walk($dirs, 'self::createInstance');

        return $dirs;
    }

    /**
     * Function for the array walker. Creates (and returns) an instance for the given module name.
     * 
     * @param  string $item The module name (path included)
     * @return Module
     */
    protected static function createInstance(&$item)
    {
        $item = new Module(['title' => class_basename($item)]);
    }

    /**
     * Check if a installer file exists. Returns filename or false.
     * 
     * @return string|boolean
     */
    public function installer()
    {
        $filename = app_path().'/modules/'.$this->title.'/Installer.php';
        
        if (File::exists($filename)) {
            return $filename;
        } else {
            return false;
        }
    }

    public function enabled()
    {
        $moduleBase = app()['modules'];

        return $moduleBase->isEnabled($this->title);
    }

}