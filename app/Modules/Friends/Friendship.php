<?php

namespace App\Modules\Friends;

use BaseModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * The Friendship model is a helper model and a representation of
 * the many-to-many-relationship between users. It's not meant
 * for storing friendships!
 *
 * @property bool $confirmed
 * @property \Carbon $messaged_at
 * @property int $sender_id
 * @property int $receiver_id
 * @property \User $sender
 * @property \User $receiver
 */
class Friendship extends BaseModel
{

    /**
     * To avoid friendship request spamming, the user has to wait this number of seconds (= 10 minutes)
     * until he/she can send another friendship request to the same other user
     */
    const FRIENDSHIP_REQUEST_LIFESPAN = 60 * 10;
    
    public $table = 'friends';

    protected $dates = ['messaged_at'];

    public static $relationsData = [
        'sender'   => [self::BELONGS_TO, 'User'],
        'receiver' => [self::BELONGS_TO, 'User'],
    ];

    /**
     * Query scope that returns only the (confirmed) friendships of two users
     * 
     * @param  Builder $query       The query builder object
     * @param  int     $friendOneId The ID of the first user
     * @param  int     $friendTwoId The ID of the second user
     * @param  bool    $confirmed   Only show confirmed friendships? Default = true
     * @return Builder
     */
    public function scopeAreFriends(Builder $query, int $friendOneId, int $friendTwoId, bool $confirmed = true) : Builder
    {
        if ($confirmed) {
            $query->whereConfirmed(1);
        }

        return $query->where(function($query) use ($friendOneId, $friendTwoId)
        {
            $query->whereSenderId($friendOneId)
                ->whereReceiverId($friendTwoId);
        })->orWhere(function($query) use ($friendOneId, $friendTwoId)
        {
            $query->whereReceiverId($friendOneId) // Receiver <-> Sender
                ->whereSenderId($friendTwoId);
        });
    }

    /**
     * Query scope that returns the friendships of a user
     * 
     * @param  Builder  $query     The query builder object
     * @param  int      $userId    The ID of the user
     * @param  bool     $confirmed Only show confirmed friendships? Default = true
     * @return Builder
     */
    public function scopeFriendsOf(Builder $query, int $userId, bool $confirmed = true) : Builder
    {
        if ($confirmed) {
            $query->whereConfirmed(1);
        }

        return $query->where(function($query) use ($userId)
        {
            $query->whereSenderId($userId)->orWhere('receiver_id', $userId);
        });
    }

}
