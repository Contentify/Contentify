<?php namespace App\Modules\Roles;

/**
 * Super simple helper class that represents role permissions
 */
class Permission {

    /**
     * Namer of the permisson
     * @var string
     */
    public $name;

    /**
     * Array consiting of pairs (value ID and value)
     * @var array
     */
    public $values;

    /**
     * The ID of the current value
     * @var mixed
     */
    public $current;

    public function __construct($name, $values, $current) {
        $this->name     = $name;
        $this->values   = $values;
        $this->current  = $current;
    }

}