<?php

use LaravelBook\Ardent\Ardent as OriginalArdent;

class Ardent extends OriginalArdent {

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

}