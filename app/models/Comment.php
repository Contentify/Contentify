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
}