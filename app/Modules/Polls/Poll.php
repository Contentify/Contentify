<?php

namespace App\Modules\Polls;

use BaseModel;
use Cache;
use Comment;
use DB;
use SoftDeletingTrait;
use User;

/**
 * @property \Carbon $created_at
 * @property \Carbon $deleted_at
 * @property string  $title
 * @property string  $slug
 * @property bool    $open
 * @property bool    $internal
 * @property int     $max_votes
 * @property string  $option1
 * @property string  $option2
 * @property string  $option3
 * @property string  $option4
 * @property string  $option5
 * @property string  $option6
 * @property string  $option7
 * @property string  $option8
 * @property string  $option9
 * @property string  $option10
 * @property string  $option11
 * @property string  $option12
 * @property string  $option13
 * @property string  $option14
 * @property string  $option15
 * @property int     $creator_id
 * @property int     $updater_id
 * @property \User   $creator
 */
class Poll extends BaseModel
{

    use SoftDeletingTrait;

    /**
     * Maximum number of possible options per poll. Changing this values will have limited effect,
     * since for example the database columns of the options are hardcoded
     */
    const MAX_OPTIONS = 15;

    const RESULTS_CACHE_KEY = 'polls::results.';

    protected $dates = ['deleted_at'];

    protected $slugable = true;

    protected $fillable = [
        'title',
        'open',
        'internal',
        'max_votes',
        'option1',
        'option2',
        'option3',
        'option4',
        'option5',
        'option6',
        'option7',
        'option8',
        'option9',
        'option10',
        'option11',
        'option12',
        'option13',
        'option14',
        'option15',
    ];

    protected $rules = [
        'title'     => 'required|min:3',
        'open'      => 'boolean',
        'internal'  => 'boolean',
        'max_votes' => 'required|integer|min:1|max:15'
    ];

    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    /**
     * Returns true if a given user already participated in the current poll
     *
     * @param User $user
     * @return bool
     */
    public function userVoted(User $user)
    {
        $counter = DB::table('polls_votes')->wherePollId($this->id)->whereUserId($user->id)->count();

        return ($counter > 0);
    }

    /**
     * Stores the votes of a user for the current poll in the database. The votes will not be validated 
     * so you should validate them before calling this method.
     *
     * @param User  $user  The voting user
     * @param int[] $votes The votes of the user (=the IDs of the options)
     */
    public function vote(User $user, array $votes)
    {
        $records = [];
        foreach ($votes as $vote) {
            $records[] = ['poll_id' => $this->id, 'user_id' => $user->id, 'option_id' => $vote];
        }

        DB::table('polls_votes')->insert($records);
        $this->updateResults();
    }

    /**
     * Counts the votes of the current polls and stores the result in the cache.
     * We do this for performance reasons. The method also returns the results.
     *
     * @return int[]
     */
    public function updateResults()
    {
        $results = [];
        for ($counter = 1; $counter <= Poll::MAX_OPTIONS; $counter++) {
            $results[$counter] = DB::table('polls_votes')->wherePollId($this->id)->whereOptionId($counter)->count();
        }

        Cache::forever(self::RESULTS_CACHE_KEY.$this->id, $results);

        return $results;
    }

    /**
     * Returns the (cached) results of the current poll.
     *
     * @return int[]
     */
    public function getResults()
    {
        if (! Cache::has(self::RESULTS_CACHE_KEY.$this->id)) {
            return $this->updateResults();
        } else {
            return Cache::get(self::RESULTS_CACHE_KEY.$this->id);
        }
    }

    /**
     * Count the comments that are related to this poll.
     *
     * @return int
     */
    public function countComments()
    {
        return Comment::count('polls', $this->id);
    }

}
