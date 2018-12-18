<?php

namespace App\Modules\Downloads;

use BaseModel;
use Comment;
use Exception;
use File;
use Illuminate\Database\Eloquent\Builder;
use InterImage;
use SoftDeletingTrait;

/**
 * @property \Carbon                            $created_at
 * @property \Carbon                            $deleted_at
 * @property string                             $title
 * @property string                             $slug
 * @property string                             $description
 * @property int                                $download_cat_id
 * @property string                             $file
 * @property int                                $file_size
 * @property bool                               $is_image
 * @property bool                               $internal
 * @property bool                               $published
 * @property int                                $access_counter
 * @property int                                $creator_id
 * @property int                                $updater_id
 * @property \App\Modules\Downloads\DownloadCat $downloadCat
 * @property \User                              $creator
 */
class Download extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $slugable = true;

    protected $fillable = ['title', 'description', 'internal', 'published', 'download_cat_id'];

    public static $fileHandling = ['file'];

    protected $rules = [
        'title'             => 'required|min:3',
        'internal'          => 'boolean',
        'published'         => 'boolean',
        'download_cat_id'   => 'required|integer'
    ];

    public static $relationsData = [
        'downloadCat'   => [self::BELONGS_TO, 'App\Modules\Downloads\DownloadCat'],
        'creator'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    public static function boot()
    {
        parent::boot();

        self::saving(function(self $download)
        {
            $filename = $download->uploadPath(true).$download->file;
            if (File::isFile($filename)) {
                $download->file_size = File::size($filename); // Save file size
                
                try {
                    $imgData = getimagesize($filename); // Try to gather info about the image
                } catch (Exception $e) {

                }

                if (isset($imgData[2]) and $imgData[2]) {
                    $download->is_image = true;

                    /*
                     * Create Thumbnail
                     */
                    $size = 50;
                    InterImage::make($filename)->resize($size, $size, function ($constraint) {
                        /** @var \Intervention\Image\Constraint $constraint */
                        $constraint->aspectRatio();
                    })->save($download->uploadPath(true).$size.'/'.$download->file); 
                }
            }
        });
    }

    /**
     * Count the comments that are related to this download.
     * 
     * @return int
     */
    public function countComments()
    {
        return Comment::count('downloads', $this->id);
    }

    /**
     * Select only those that have been published
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished($query)
    {
        return $query->wherePublished(true);
    }

}
