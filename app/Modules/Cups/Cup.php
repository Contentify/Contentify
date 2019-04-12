<?php

namespace App\Modules\Cups;

use BaseModel;
use Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use MsgException;
use User;

/**
 * @property \Carbon                          $created_at
 * @property \Carbon                          $deleted_at
 * @property string                           $title
 * @property string                           $slug
 * @property int                              $game_id
 * @property int                              $players_per_team
 * @property int                              $slots
 * @property string                           $prize
 * @property string                           $description
 * @property string                           $rulebook
 * @property \Carbon                          $join_at
 * @property \Carbon                          $check_in_at
 * @property \Carbon                          $start_at
 * @property bool                             $featured
 * @property bool                             $published
 * @property bool                             $closed
 * @property string                           $image
 * @property int                              $creator_id
 * @property int                              $updater_id
 * @property int                              $access_counter
 * @property \App\Modules\Games\Game          $game
 * @property \User[]                          $referees
 * @property \User                            $creator
 * @property \App\Modules\Cups\Match[]        $matches
 * @property \User[]|\App\Modules\Cups\Team[] $participants
 */
class Cup extends BaseModel
{
    /**
     * Name of the event that is fired when a cup has been seeded
     */
    const EVENT_NAME_CUP_SEEDED = 'contentify.cups.cupSeeded';
    
    /**
     * Name of the event that is fired when a participant has been added to a cup
     */
    const EVENT_NAME_PARTICIPANT_ADDED = 'contentify.cups.participantAdded';
    
    /**
     * Name of the event that is fired when a participant has been removed from a cup
     */
    const EVENT_NAME_PARTICIPANT_REMOVED = 'contentify.cups.participantRemoved';    
    
    /**
     * Array with all possible values of: How many players have to be in a team at least?
     * @var int[]
     */
    public static $playersPerTeamValues = [1, 2, 3, 4, 5, 6];

    /**
     * Array with all possible values of: How many players/teams can participate in a cup?
     * @var int[]
     */
    public static $slotValues = [2, 4, 8, 16, 32, 64, 128, 256, 512, 1024];

    /**
     * Temporary stores a collection of matches to avoid unnecessary database queries
     *
     * @var \Illuminate\Database\Eloquent\Collection|null A collection of matches
     */
    protected $matchesStored = null;

    protected $dates = ['deleted_at', 'join_at', 'check_in_at', 'start_at'];

    protected $slugable = true;

    protected $fillable = [
        'title',
        'game_id',
        'players_per_team',
        'slots',
        'prize',
        'description',
        'rulebook',
        'join_at',
        'check_in_at',
        'start_at',
        'featured',
        'published',
        'closed',
        'relation_referees',
    ];

    public static $fileHandling = ['image' => ['type' => 'image']];

    protected $rules = [
        'title'             => 'required|min:3',
        'slots'             => 'integer|min:0',
        'game_id'           => 'integer|min:1',
        'players_per_team'  => 'integer|min:0',
        'published'         => 'boolean',
        'featured'          => 'boolean',
        'closed'            => 'boolean',
    ];

    public static $relationsData = [
        'game'          => [self::BELONGS_TO, 'App\Modules\Games\Game'],
        'referees'      => [self::BELONGS_TO_MANY, 'User', 'table' => 'cups_referees', 'title' => 'username'],
        'creator'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    public static function boot()
    {
        parent::boot();

        self::deleting(function(self $cup)
        {
            DB::table('cups_matches')->whereCupId($cup->id)->delete();

            DB::table('cups_users')->whereCupId($cup->id)->delete();

            DB::table('cups_participants')->whereCupId($cup->id)->delete();

            DB::table('cups_referees')->whereCupId($cup->id)->delete();
        });

        self::saving(function(self $cup)
        {
            if ($cup->isDirty('players_per_team') and $cup->countParticipants()) {
                throw new MsgException('You cannot change the "players per team" value when a cup has participants!');
            }
        });

        self::saved(function(self $cup)
        {
            if ($cup->isDirty('closed')) {
                // cup_users is a helper table that needs manual updates.
                DB::table('cups_users')->whereCupId($cup->id)->update(['cup_closed' => $cup->closed]);
            }
        });
    }

    /**
     * Relationship: Returns all participants of this cup
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function participants()
    {
        if ($this->forTeams()) {
            return $this->belongsToMany('App\Modules\Cups\Team', 'cups_participants', null, 'participant_id')
                ->withPivot('checked_in');
        } else {
            return $this->belongsToMany('User', 'cups_participants', null, 'participant_id')
                ->withPivot('checked_in');
        }
    }

    /**
     * Relationship: Returns all matches of this cup (ordered by round and round-row)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matches()
    {
        return $this->hasMany('App\Modules\Cups\Match')->orderBy('round')->orderBy('row');
    }

    /**
     * Returns all matches of this cup with their participants.
     * This imitates Eloquent's eager loading so it only executes three MySQL queries.
     *
     * @param bool $refresh If true, force database query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function matchesDetailed(bool $refresh = false)
    {
        if ($this->matchesStored and ! $refresh) {
            return $this->matchesStored;
        }

        $matches = Match::whereCupId($this->id)->get();

        $leftParticipantIds = $matches->pluck('left_participant_id')->all();
        $rightParticipantIds = $matches->pluck('right_participant_id')->all();

        if ($this->forTeams()) {
            $leftParticipants = Team::whereIn('id', $leftParticipantIds)->get();
            $rightParticipants = Team::whereIn('id', $rightParticipantIds)->get();
        } else {
            $leftParticipants = User::whereIn('id', $leftParticipantIds)->get();
            $rightParticipants = User::whereIn('id', $rightParticipantIds)->get();
        }

        foreach ($matches as $match) {
            $match->left_participant = $leftParticipants->where('id', $match->left_participant_id)->pop();
            $match->right_participant = $rightParticipants->where('id', $match->right_participant_id)->pop();
        }

        $this->matchesStored = $matches;

        return $matches;
    }

    /**
     * Select only those that have been published
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished(Builder $query) : Builder
    {
        return $query->wherePublished(true);
    }

    /**
     * Returns an array that uses the values of another array as values and keys.
     * 
     * @param  string $attribute The name of the attribute that contains the source array
     * @return array
     */
    public static function makeOptionArray(string $attribute) : array
    {
        $originalArray = self::$$attribute;

        $array = [];

        foreach ($originalArray as $value) {
            $array[$value] = $value;
        }

        return $array;
    }

    /**
     * Returns true if the participants of the cup are teams. 
     * Returns false if they are users (for 1on1s).
     * 
     * @return bool
     */
    public function forTeams() : bool
    {
        return ($this->players_per_team > 1);
    }

    /**
     * Returns an array with the IDs of the participants of this cup
     * 
     * @return Collection
     */
    public function participantIds() : Collection
    {
        return DB::table('cups_participants')->whereCupId($this->id)->pluck('participant_id');
    }

    /**
     * Counts then participants of this cup
     * 
     * @return int
     */
    public function countParticipants() : int
    {
        return DB::table('cups_participants')->whereCupId($this->id)->count();
    }

    /**
     * Returns the participating object of a given user for this cup:
     * - Returns null if the user does not participate.
     * - Returns the user object if it is a 1on1.
     * - Returns the team of the user if it is a team cup.
     * 
     * @param  User|null $user
     * @return Team|User|null
     */
    public function getParticipantOfUser(User $user = null)
    {
        if (! $user) {
            return null;
        }

        if ($this->forTeams()) {
            $teams = $this->participants;
            $userTeamIds = DB::table('cups_team_members')->whereUserId($user->id)->pluck('team_id');

            if ($teams) {
                foreach ($teams as $team) {
                    foreach ($userTeamIds as $userTeamId) {
                        if ($userTeamId == $team->id) {
                            return $team;
                        }
                    }
                }
            }

            return null;
        } else {
            $participantIds = $this->participantIds();

            if ($participantIds->contains($user->id)) {
                return $user;
            } else {
                return null;
            }
        }
    }

    /**
     * Checks if a user is in this cup.
     * Works for 1on1 & team cups.
     * 
     * @param User $user
     * @return bool
     */
    public function isUserInCup(User $user) : bool
    {
        return ($this->getParticipantOfUser($user) !== null);
    }

    /**
     * Returns true if a participant has checked-in to this cup.
     * 
     * @param  Team|User $participant
     * @return bool
     */
    public function hasParticipantCheckedIn($participant) : bool
    {
        if (! $participant) {
            return false;
        }

        return DB::table('cups_participants')->whereParticipantId($participant->id)->whereCupId($this->id)->value('checked_in');
    }

    /**
     * Returns all cup teams of a given user
     * 
     * @param  User     $user        The user object
     * @param  boolean  $isOrganizer If true, return only those teams where the user is organizer
     * @return Collection
     */
    public function teamsOfUser(User $user, bool $isOrganizer = false) : Collection
    {
        $query = DB::table('cups_team_members')->whereUserId($user->id);

        if ($isOrganizer) {
            $query->whereOrganizer(true);
        }

        $userTeamIds = $query->pluck('team_id')->toArray();

        return Team::whereIn('id', $userTeamIds)->get();
    }

    /**
     * Returns all cups of a given user.
     * 
     * @param User|null $user     The user object
     * @param bool      $onlyOpen If true return only cups that still are open
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function cupsByUser(User $user = null, bool $onlyOpen = false)
    {
        if (! $user) {
            return null;
        }

        $query = DB::table('cups_users')->whereUserId($user->id);
        if ($onlyOpen) {
            $query->whereCupClosed(false);
        }
        $userCupIds = $query->pluck('cup_id')->toArray();

        $query = Cup::wherePublished(true);
        if ($onlyOpen) {
            $query->whereClosed(false);
        }
        return $query->whereIn('id', $userCupIds)->get();
    }

    /**
     * Returns the number of rounds a bracket (cup) will have with a given number of participants.
     * For example it will return 3 for 4 participants.
     * 
     * @param  int|null $participants Number of participants
     * @return int
     */
    public function rounds(int $participants = null) : int
    {
        if ($participants === null) {
            $participants = $this->countParticipants();
        }

        if ($participants < 2) {
            return 0;
        }

        return (int) ceil(log($participants) / log(2) + 1);
    }
    
    /**
     * Generates the matches for the first round of the current cup
     *
     * @return void
     * @throws MsgException
     */
    public function seed()
    {
        // Get all participants that are checked-in and randomize their order
        $participants = $this->participants()->where(DB::raw('`cups_participants`.`checked_in`'), true)
            ->get()->shuffle();

        if ($participants->isEmpty()) {
            throw new MsgException(trans('app.not_possible'));
        }

        // Set the amount of slots to the lowest possible value
        // (not less than the number of participants, of course)
        $slotValues = self::$slotValues;
        arsort($slotValues); // Sort from high to low
        foreach ($slotValues as $slots) {
            if ($slots < sizeof($participants)) {
                $this->save();
                break;
            }
            $this->slots = $slots;
        }

        $matches = Match::whereCupId($this->id)->where('winner_id', '>', 0)->where('right_participant_id', '!=', 0)->get();

        // (Re-)Seeding is not possible once matches have been played
        if (sizeof($matches) > 0) {
            throw new MsgException(trans('app.not_possible'));
        }

        // Delete the existing matches
        Match::whereCupId($this->id)->delete();

        // The number of matches that we will generate is ALWAYS 0.5 * number of slots -
        // it does NOT (directly) depend on the number of wildcard-matches! This fact is very important!
        // This algorithm ensures that wildcard-matches can only appear in the first round of the cup.
        // There will never be wildcard-matches in other rounds than the first one!
        $matches = [];
        for ($matchIndex = 0; $matchIndex < $this->slots / 2; $matchIndex++) {
            // Note: We know that we have at least 0.5 * slots participants.
            // This is because we have reduced the number of slots to the lowest possible value.
            // (The difference between two slot limits is always 50% of the bigger limit.)
            $leftParticipantId = $participants[$matchIndex]->id;

            // Note: Wildcard-matches will always have set right_participant_id to 0.
            $index = $this->slots / 2 + $matchIndex;
            $rightParticipantId = isset($participants[$index]) ? $participants[$index]->id : 0;

            $now = new Carbon;
            
            $matches[] = [
                'round'                 => 1,
                'row'                   => $matchIndex + 1,
                'with_teams'            => $this->forTeams(),
                'left_participant_id'   => $leftParticipantId,
                'right_participant_id'  => $rightParticipantId,
                'winner_id'             => $rightParticipantId > 0 ? 0 : $leftParticipantId,
                'cup_id'                => $this->id,
                'creator_id'            => user() ? user()->id : User::DAEMON_USER_ID,
                'created_at'            => $now,
            ];
        }

        Match::insert($matches);
        
        event(self::EVENT_NAME_CUP_SEEDED, [$cup]);
    }
    
    /**
     * Adds a participant to the current cup.
     * ATTENTION: Does not perform any checks!
     */
    public function addParticipantById(int $participantId)
    {
        DB::table('cups_participants')->insert([
            'cup_id' => $this->id,
            'participant_id' => $participantId,
        ]);
        
        event(self::EVENT_NAME_PARTICIPANT_ADDED, [$cup, $participantId]);
    }
    
    /**
     * Removes a participant from the given cup.
     * ATTENTION: Does not perform any checks!
     */
    public function removeParticipantByid(int $participantId)
    {
        DB::table('cups_participants')->whereCupId($cup->id)->whereParticipantId($participantId)->delete();
        
        event(self::EVENT_NAME_PARTICIPANT_REMOVED, [$cup, $participantId]);
    }        
}
