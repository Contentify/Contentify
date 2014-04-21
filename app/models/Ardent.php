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
     * Path to uploaded files.
     * NOTE: When uploading files set $local = true!
     *
     * @param  bool $local If true, return a local path (e. g. "C:\Contentify\public/uploads/test")
     * @return string
     */
    public function uploadPath($local = false)
    {
        $class = class_basename(get_class($this));

        $dir = $this->uploadPath;
        if (! $dir) {
            $dir = strtolower(str_plural($class));
        }

        if ($local) {
            $base = public_path();
        } else {
            $base = url('/');
        }

        return $base.'/uploads/'.$dir.'/';
    }

    /**
     * Decides if a model is modifiable.
     * This includes updating and deleting.
     * Affects only parts of the CMS (not of Laravel).
     * 
     * @return bool
     */
    public function modifiable()
    {
        return true;
    }

}