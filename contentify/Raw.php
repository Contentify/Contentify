<?php namespace Contentify;

class Raw {

    /**
     * The value to print
     * @var string
     */
    protected $value;

    /**
     * Constructor call
     * 
     * @param string $value The value to print
     * @param string $escape Another value that's going to get auto escaped
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