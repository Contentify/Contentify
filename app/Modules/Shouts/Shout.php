<?php 

namespace App\Modules\Shouts;

use BaseModel;
use File;

/**
 * @property string $text
 * @property int    $creator_id
 * @property \User  $creator
 */
class Shout extends BaseModel
{

    /**
     * Path to the public sub directory
     */
    const PUBLIC_DIRECTORY = '/share/';

    protected $fillable = ['text'];

    protected $rules = [
        'text'      => 'required',
    ];

    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    public static function boot()
    {
        parent::boot();

        self::saved(function(self $shout)
        {
            $shout->updateFiles();
        });
    }

    /**
     * For a better performance, we use caching.
     * We create two files in the public directory so they can be accessed directly.
     * This is faster than using a controller that utilizes Laravel's caching class.
     * 
     * @return void
     */
    public function updateFiles()
    {
        $path = public_path().self::PUBLIC_DIRECTORY;

        $shouts = self::orderBy('created_at', 'desc')->with('creator')->take(10)->get();

        $jsonShouts = [];
        $maxId = 0;

        // We have to reverse the order so that the oldest shout comes first
        for ($i = sizeof($shouts) - 1; $i >= 0; $i--) { 
            $shout = $shouts[$i];

            if ($shout->id > $maxId) {
                $maxId = $shout->id;
            }

            // NOTE: json_decode() will auto-cast this array to an object.
            $jsonShouts[] =  [
                'id'                => $shout->id,
                'text'              => $shout->text, 
                'creator_id'        => $shout->creator_id,
                'creator_username'  => $shout->creator->username,
                'created_at'        => $shout->created_at->timestamp,
            ];
        }

        $json = json_encode($jsonShouts);

        File::put($path.'shouts.json', $json);

        File::put($path.'shouts.beacon', $maxId);
    }

}
