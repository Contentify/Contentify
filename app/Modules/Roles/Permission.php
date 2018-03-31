<?php 

namespace App\Modules\Roles;

/**
 * Super simple helper class that represents role permissions
 */
class Permission
{

    /**
     * The name of the permission
     *
     * @var string
     */
    public $name;

    /**
     * Array consisting of pairs (value ID and value)
     *
     * @var string[]
     */
    public $values;

    /**
     * The ID of the current value
     *
     * @var int|null
     */
    public $current;

    /**
     * Permission constructor.
     *
     * @param string   $name
     * @param string[] $values
     * @param int|null $current
     */
    public function __construct($name, $values, $current) {
        $this->name     = $name;
        $this->values   = $values;
        $this->current  = $current;
    }

}