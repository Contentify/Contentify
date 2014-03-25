<?php

use LaravelBook\Ardent\Ardent as OriginalArdent;

class Ardent extends OriginalArdent {

    public static function relations()
    {
        return static::$relationsData;
    }

}