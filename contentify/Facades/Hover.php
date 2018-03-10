<?php

namespace Contentify\Facades;

use Illuminate\Support\Facades\Facade;

class Hover extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'hover';
    }

}