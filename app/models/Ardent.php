<?php

use LaravelBook\Ardent\Ardent as OriginalArdent;

class Ardent extends OriginalArdent {

    /**
     * Path to uploaded files
     * @var string
     */
    protected $uploadPath;

    public static function relations()
    {
        return static::$relationsData;
    }

    /**
     * Override this method to apply filters to a query of a model.
     * The Filter class provides methods to get values of filters.
     * 
     * @param  Builder $query Apply filters to this query
     * @return Builder
     */
    public function scopeFilter($query) { }

    /**
     * Path to uploaded files
     * 
     * @return string
     */
    public function uploadPath()
    {
        $class = class_basename(get_class($this));

        $dir = $this->uploadPath;
        if (! $dir) {
            $dir = strtolower(str_plural($class));
        }

        return url('/').'/uploads/'.$dir.'/';
    }

}