<?php

namespace Contentify\Models;

class ConfigBag
{

    /**
     * @var string
     */
    protected $namespace = '';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [];
    
    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Get the config namespace.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Get the fillable attributes for the model.
     *
     * @return string
     */
    public function getFillable()
    {
        return $this->fillable;
    }

    /**
     * Get the global validation rules.
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules ?: [];
    }

}