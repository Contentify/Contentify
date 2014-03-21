<?php namespace Contentify;

use Illuminate\Foundation\Composer;

class ModuleInstaller {

    /**
     * This is the core method and will perform the installation.
     * $step starts counting at 0.
     * Return false to terminate with error, true to finish 
     * or a string (or View) to pass visual output.
     * 
     * @param  int          $step Step counter
     * @return string|bool
     */
    public function execute($step)
    {
        return false;
    }

}