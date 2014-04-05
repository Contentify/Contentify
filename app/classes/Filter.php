<?php namespace Contentify;

use Input;

class Filter {

    /**
     * True if the filter exists
     * 
     * @param  string  $name The name of the filter
     * @return boolean
     */
    public static function has($name)
    {
        if (! Input::has('filter')) return null;

        $filters = Input::get('filter');
        $filters = explode('_', $filters);

        foreach ($filters as $filter) {
            if (! $filter) continue;

            $parts = explode('-', $filter);

            if ($parts[0] == $name) {
                return true;
            }  
        }

        return false;
    }

    /**
     * Returns the value of a filter. 
     * Returns null if no value is set.
     * NOTE: Use has() to check if a filter exists, because
     * get() might return null even if a filter exists.
     * (Null is a valid value.)
     * 
     * @param  string       $name The name of the filter
     * @return string|null
     */
    public static function get($name)
    {
        if (! Input::has('filter')) return null;

        $filters = Input::get('filter');
        $filters = explode('_', $filters);

        foreach ($filters as $filter) {
            if (! $filter) continue;

            $parts = explode('-', $filter);

            if ($parts[0] == $name) {
                return (isset($parts[1]) ? $parts[1] : null);
            }  
        }

        return null;
    }

}