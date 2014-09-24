<?php namespace App\Modules\Images\Models;

use Str, BaseModel;

class Image extends BaseModel {

    protected $fillable = ['tags', 'gallery_id', 'title'];

    public static $fileHandling = ['image' => ['type' => 'image', 'thumbnails' => [100, 200]]];

    public static $rules = [
        'tags'     => 'required',
    ];

    public static $relationsData = [
        'gallery'   => [self::BELONGS_TO, 'App\Modules\Galleries\Models\Gallery'],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    public function gallerySlug()
    {
        $slug = Str::slug($this->title);

        if ($slug) {
            return '/'.$slug;
        } else {
            return null;
        }
    }

}