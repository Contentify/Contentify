<?php

class Comment extends Ardent {

    protected $softDelete = true;

    protected $fillable = array('text');

    public static $rules = array(
        'text'  => 'required',
    );

    public static $relationsData = array(
        'creator' => array(self::BELONGS_TO, 'User'),
    );
}