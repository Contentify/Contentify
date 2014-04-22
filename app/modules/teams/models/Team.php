<?php namespace App\Modules\Teams\Models;

use BaseModel;

class Team extends BaseModel {

    protected $softDelete = true;

    protected $fillable = ['title', 'description', 'position', 'teamcat_id'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    public static $rules = [
        'title'     => 'required',
        'position'  => 'integer',
    ];

    public static $relationsData = [
        'teamcat'   => [self::BELONGS_TO, 'App\Modules\Teams\Models\Teamcat'],
    ];

    /**
     * Ardent does not support orderBy for pivot attributes 
     * so we have to use Eloquent instead.
     * @link https://github.com/laravelbook/ardent/issues/185
     */
    public function users()
    {
        return $this->belongsToMany('User')->withPivot('task', 'description', 'position')
            ->orderBy('pivot_position', 'asc');
    }

}