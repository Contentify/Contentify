<?php namespace App\Modules\Auth\Models;

class AuthConfig {

    protected $namespace = 'auth::';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'registration',
        'unicorns',
    ];
    
    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [
        'registration'  => 'boolean',
    ];

    /**
     * Get the config namespace.
     *
     * @return array
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Get the fillable attributes for the model.
     *
     * @return array
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