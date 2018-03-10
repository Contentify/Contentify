<?php

namespace App\Modules\Modules;

use File, BaseModel;

class Module extends BaseModel
{

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

        return $item;
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

    /**
     * Check if the module is enabled. 
     * 
     * @return boolean
     */
    public function enabled()
    {
        /** @var \Caffeinated\Modules\Modules $moduleBase */
        $moduleBase = app()['modules'];

        // Get module info by the basename property which we know,
        // so we can get the slug for the isEnabled() method.
        $moduleInfo = $moduleBase->where('basename', $this->title);

        return $moduleBase->isEnabled($moduleInfo['slug']);
    }

}