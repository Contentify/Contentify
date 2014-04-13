<?php

class Comment extends Ardent {

    protected $softDelete = true;

    protected $fillable = array('text');

    public static $rules = array(
        'text'  => 'required|min:3',
    );

    public static $relationsData = array(
        'creator' => array(self::BELONGS_TO, 'User'),
    );

    /**
     * Count the comments that are related to a certain foreign type (model).
     * NOTE: The result of the SQL query is cached!
     * 
     * @param  string   $foreignType Name of the foreign type (model)
     * @param  int      $foreignId   ID of the foreign type or null
     * @return int
     */
    public static function count($foreignType, $foreignId = null)
    {
        $query = self::remember(5)->whereForeignType($foreignType);
        if ($foreignId) $query->whereForeignId($foreignId);
        return $query->count();
    }
}