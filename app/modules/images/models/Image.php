<?php namespace App\Modules\Images\Models;

use Str, BaseModel;

class Image extends BaseModel {

    protected $fillable = ['tags', 'gallery_id', 'title', 'width', 'height'];

    public static $fileHandling = ['image' => ['type' => 'image', 'thumbnails' => [100, 200]]];

    protected $rules = [
        'tags'          => 'required',
        'gallery_id'    => 'sometimes|integer',
        'width'         => 'sometimes|integer|min:0',
        'height'        => 'sometimes|integer|min:0',
    ];

    public static $relationsData = [
        'gallery'   => [self::BELONGS_TO, 'App\Modules\Galleries\Models\Gallery'],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    public static function boot()
    {
        parent::boot();

        self::saving(function($image)
        {
            /*
             * Retrieve the size of the image and save it.
             */
            if ($image->image) {
                try {
                    $imgData = getimagesize($image->uploadPath().$image->image);    
                } catch (Exception $e) {
                    
                }
                
                $image->width = $imgData[0];
                $image->height = $imgData[1];
            }
        });
    }

    /**
     * Helper function that creates a URL-prepared slug if the image
     * is displayed as a part of a gallery. Images do not always 
     * have a slug, because they do not always have a title.
     *
     * @return int
     */
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