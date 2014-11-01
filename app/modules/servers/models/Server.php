<?php namespace App\Modules\Servers\Models;

use SoftDeletingTrait, BaseModel;

class Server extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'ip', 'hoster', 'slots', 'game_id'];

    protected $rules = [
        'title'     => 'required',
        'ip'        => 'required|min:7', // If we enforce it to be an ip adding a port is invalid!
        'slots'     => 'integer|min:0',
        'game_id'   => 'integer',
    ];

    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
        'game'      => [self::BELONGS_TO, 'App\Modules\Games\Models\Game'],
    ];

}