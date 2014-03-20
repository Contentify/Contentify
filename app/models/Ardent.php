<?php

use LaravelBook\Ardent\Ardent as OriginalArdent;

class Ardent extends OriginalArdent {

    public static function relations()
    {
        return static::$relationsData;
    }

    /**
     * Count the comments that are related to this news.
     * 
     * @return int
     */
    public function countComments()
    {
        // NOTE: The result of this query is cached!
        dd(__class__);
        return Comment::remember(5)->whereForeignType('news')->whereForeignId($this->id)->count();
    }

}