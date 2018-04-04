<?php 

namespace App\Modules\Pages;

use SoftDeletingTrait;
use StiModel;

/**
 * @property \Carbon $deleted_at
 * @property \Carbon $published_at
 * @property string $title
 * @property string $slug
 * @property string $text
 * @property bool $published
 * @property bool $internal
 * @property bool $enable_comments
 * @property int $pagecat_id
 * @property int $access_counter
 * @property int $creator_id
 * @property int $updater_id
 * @property \App\Modules\Pages\Pagecat $pagecat
 * @property \User $creator
 */
class Page extends StiModel
{

    use SoftDeletingTrait;

    protected $table = 'pages';

    protected $subclassField = 'pagecat_id';

    protected $dates = ['deleted_at', 'published_at'];

    protected $slugable = true;

    protected $fillable = [
        'title', 
        'text', 
        'published_at', 
        'published',
        'internal',
        'enable_comments',
        'pagecat_id'
    ];

    protected $rules = [
        'title'             => 'required|min:3',
        'published'         => 'boolean',
        'internal'          => 'boolean',
        'enable_comments'   => 'boolean',
        'pagecat_id'        => 'required|integer'
    ];

    public static $relationsData = [
        'pagecat' => [self::BELONGS_TO, 'App\Modules\Pages\Pagecat'],
        'creator' => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}