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
     * @param string $escape Another value that is auto escaped
     */
    public function __construct($value, $escape)
    {
        $this->value = $value.e($escape);
    }

    public function __toString()
    {
        return $this->value;
    }

}