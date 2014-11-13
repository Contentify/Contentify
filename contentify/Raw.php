<?php namespace Contentify;

class Raw {

    /**
     * The value to print
     * @var mixed
     */
    protected $value;

    /**
     * Constructor call
     * 
     * @param mixed $value The value to print
     * @param mixed $escape Another value that's going to be auto escaped
     */
    public function __construct($value, $escape = null)
    {
        if ($escape !== null) {
            $this->value = (string) $value.e($escape);
        } else {
            $this->value = (string) $value;
        }
    }

    public function __toString()
    {
        return $this->value;
    }

}