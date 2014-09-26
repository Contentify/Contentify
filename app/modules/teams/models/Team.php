<?php namespace App\Modules\Teams\Models;

use SoftDeletingTrait, BaseModel;

class Team extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $slugable = true;

    protected $fillable = ['title', 'description', 'position', 'teamcat_id'];

    public static $fileHandling = ['image' => ['type' => 'image']];

    protected $rules = [
        'title'         => 'required',
        'position'      => 'sometimes|integer',
        'teamcat_id'    => 'required|integer',
    ];

    public static $relationsData = [
        'teamcat'   => [self::BELONGS_TO, 'App\Modules\Teams\Models\Teamcat'],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
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