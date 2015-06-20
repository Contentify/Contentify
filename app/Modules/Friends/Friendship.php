<?php namespace App\Modules\Friends;

use BaseModel;

/**
 * The Friendship model is a helper model and a representaion of 
 * the many-to-many-relationship between users. It's not meant
 * for storing friendships!
 */
class Friendship extends BaseModel {
    
    public $table = 'friends';

    protected $dates = ['messaged_at'];

    public static $relationsData = [
        'sender'   => [self::BELONGS_TO, 'User'],
        'receiver' => [self::BELONGS_TO, 'User'],
    ];

    /**
     * Query scope that returns only the friendships of two users
     * 
     * @param  Builder      $query       The query builder object
     * @param  int          $friendOneId The ID of the first user
     * @param  int          $friendTwoId The ID of the second user
     * @return Builder
     */
    public function scopeAreFriends($query, $friendOneId, $friendTwoId)
    {
        return $query->where(function($query) use ($friendOneId, $friendTwoId)
        {
            $query->whereSenderId($friendOneId)
                ->whereReceiverId($friendTwoId);
        })->orWhere(function($query) use ($friendOneId, $friendTwoId)
        {
            $query->whereReceiverId($friendOneId)
                ->whereSenderId($friendTwoId);
        });
    }

    /**
     * Query scope that returns the friendships of a user
     * 
     * @param  Builder  $query  The query builder object
     * @param  int      $userId The ID of the user
     * @return Builder
     */
    public function scopeFriendsOf($query, $userId)
    {
        return $query->whereSenderId($userId)->orWhere('receiver_id', $userId);
    }

}