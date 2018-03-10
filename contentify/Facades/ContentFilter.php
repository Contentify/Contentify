<?php

namespace Contentify\Facades;

use Illuminate\Support\Facades\Facade;

class ContentFilter extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'contentFilter';
    }

}