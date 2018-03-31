<?php

namespace Contentify;

use Group;
use Sentinel;

abstract class ModuleInstaller
{

    /**
     * The name of the module
     *
     * @var string
     */
    protected $module;

    /**
     * The number of the current step (starting at 0)
     *
     * @var int
     */
    protected $step;

    /**
     * If true, the permission [<moduleName> => PERM_DELETE]
     * is added automatically.
     *
     * @var boolean
     */
    protected $addDefaultPermission = true;

    /**
     * Array consisting of pairs of permission names and levels.
     * These permission are added to the super admin role.
     * Example: array('permissionName' => 4)
     * Note that per default a basic module permission is added,
     * so you only need to use this array to provide extra perms.
     *
     * @var array
     */
    protected $extraPermissions = array();

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
     * @return string|bool
     */
    abstract public function execute();

    /**
     * This method is always executed after a module has been successfully installed.
     * 
     * @return void
     */
    public final function after()
    {
        /*
         * Update backend navigation
         */
        (new BackendNavGenerator())->update();

        /*
         * Add permissions
         */
        $role = Sentinel::findRoleBySlug('super-admins');
        $rolePermissions = $role->permissions;

        // Add default permission
        if ($this->addDefaultPermission) {
            $rolePermissions[$this->module] = PERM_DELETE;
        }

        // Add additional permissions
        if ($this->extraPermissions and is_array($this->extraPermissions)) {
            foreach ($this->extraPermissions as $permission => $level) {
                $rolePermissions[$permission] = $level;
            }
        }

        $role->permissions = $rolePermissions;
        $role->save();
    }

    /**
     * Helper function. Returns the URL of the installer for the next (or a given) step.
     * 
     * @param  int $step The step (null = auto)
     * @return string
     */
    protected final function nextStep($step = null)
    {
        if ($step === null) {
            $step = $this->step;
            $step++;
        }

        return route('modules.install', ['name' => $this->module, 'step' => $step]);
    }

}