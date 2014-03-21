<?php namespace Contentify;

use Illuminate\Foundation\Composer;

class ModuleInstaller {

    /**
     * The name of the module
     * @var string
     */
    protected $module;

    /**
     * The number of the current step (starting at 0)
     * @var int
     */
    protected $step;

    /**
     * Constructor call
     * 
     * @param string    $module The module name
     * @param int       $step   The number of the current step
     */
    public function __construct($module, $step)
    {
        $this->module   = $module;
        $this->step     = $step;
    }

    /**
     * This is the core method and will perform the installation.
     * $step starts counting at 0.
     * Return false to terminate with error, true to finish 
     * or a string (or View) to pass visual output.
     * 
     * 
     * @return string|bool
     */
    public function execute()
    {
        return false;
    }

    /**
     * Helper function. Returns the URL of the installer for the next (or a given) step.
     * 
     * @param  int $step The step (null = auto)
     * @return string
     */
    protected function nextStep($step = null)
    {
        if ($step === null) {
            $step = $this->step;
            $step++;
        }

        return route('modules.install', ['name' => $this->module, 'step' => $step]);
    }

}