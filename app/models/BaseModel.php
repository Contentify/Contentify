<?php

use LaravelBook\Ardent\Ardent;

class BaseModel extends Ardent {

    /**
     * True if model is slugable
     * @var bool
     */
    protected $slugable = false;

    /**
     * Name of the upload directory (null = name of class)
     * @var string
     */
    protected $uploadDir = null;

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
     * @param  bool $local If true, return a local path (e. g. "C:\Contentify\public/uploads/games/")
     * @return string
     */
    public function uploadPath($local = false)
    {
        $class = class_basename(get_class($this));

        $dir = $this->uploadDir;
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

    /**
     * Creates a simple slug or a unique slug
     * 
     * @param  bool   $unique   Unique or not?
     * @return void
     */
    function createSlug($unique = true)
    {
        if (! $this->slugable) return; // Do not throw exception here.

        $slug = Str::slug($this->title);

        if ($unique) {
            /*
             * Retrieve the model with the highest slug counter.
             * (orderBy uses "natural sorting")
             */
            $model = static::orderBy(DB::Raw('LENGTH(slug) DESC, slug'), 'DESC')
                ->whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")
                ->withTrashed()->first();

            /*
             * If the slug is in use already:
             * Extract the counter value, increase it and create the new slug.
             */
            if ($model) {
                $max = (int) substr($model->slug, strlen($slug) + 1);
                $slug .= '-'.($max + 1);
            }
        }

        $this->slug = $slug;
    }

}