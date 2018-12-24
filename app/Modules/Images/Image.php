<?php

namespace App\Modules\Images;

use BaseModel;
use Str;

/**
 * @property \Carbon                        $created_at
 * @property \Carbon                        $deleted_at
 * @property string                         $title
 * @property string                         $tags
 * @property string                         $image
 * @property int|null                       $gallery_id
 * @property int                            $width
 * @property int                            $height
 * @property int                            $access_counter
 * @property int                            $creator_id
 * @property int                            $updater_id
 * @property \App\Modules\Galleries\Gallery $gallery
 * @property \User                          $creator
 */
class Image extends BaseModel
{

    protected $fillable = ['tags', 'gallery_id', 'title', 'width', 'height'];

    public static $fileHandling = ['image' => ['type' => 'image', 'thumbnails' => [100, 200]]];

    protected $rules = [
        'tags'          => 'required',
        'gallery_id'    => 'nullable|integer',
        'width'         => 'sometimes|integer|min:0',
        'height'        => 'sometimes|integer|min:0',
    ];

    public static $relationsData = [
        'gallery'   => [self::BELONGS_TO, 'App\Modules\Galleries\Gallery'],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    public static function boot()
    {
        parent::boot();

        self::saving(function(self $image)
        {
            /*
             * Retrieve the size of the image and save it.
             */
            if ($image->image) {
                $imgData = getimagesize($image->uploadPath().$image->image);

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
     * @return string|null
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
