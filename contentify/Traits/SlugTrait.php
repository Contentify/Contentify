<?php

namespace Contentify\Traits;

use DB;
use Exception;
use Str;

/**
 * Use this trait to "extend" Eloquent models so they can create "smart" slugs.
 * Classes that include this trait should (must) inherit from the \Contentify\Models\BaseModel class.
 */
trait SlugTrait
{

    /**
     * Creates a simple slug or a unique slug
     *
     * @param  bool   $unique         Unique or not?
     * @param  string $titleAttribute The name of the title attribute
     * @return void
     * @throws Exception
     */
    public function createSlug($unique = true, $titleAttribute = 'title')
    {
        if (! $this->slugable) {
            throw new Exception('This model does not support slugs.');
        }

        $slug = Str::slug($this->$titleAttribute);

        if ($unique) {
            /*
             * If the model has a valid slug already and the title has
             * not been changed, do nothing - just keep the current slug.
             */
            if ($this->slug and ! $this->isDirty($titleAttribute)) {
                return;
            }

            /*
             * Retrieve the model with the highest slug counter.
             * (orderBy uses "natural sorting")
             */
            $model = static::orderBy(DB::Raw('LENGTH(slug) DESC, slug'), 'DESC')
                ->whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'");

            if ($this->isSoftDeleting()) {
                $model = $model->withTrashed();
            }

            $model = $model->first();
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
