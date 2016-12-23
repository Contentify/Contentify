<?php namespace App\Modules\Cups;

use MsgException, User, DB, BaseModel;

class Cup extends BaseModel {

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
     * Temporary stores a collection of matches to avoid unecessary database queries
     * @var Collection|null A collection of matches
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

        self::deleting(function($cup)
        {
            DB::table('cups_matches')->whereCupId($cup->id)->delete();

            DB::table('cups_users')->whereCupId($cup->id)->delete();

            DB::table('cups_participants')->whereCupId($cup->id)->delete();

            DB::table('cups_referees')->whereCupId($cup->id)->delete();            
        });

        self::saving(function($cup)
        {
            if ($cup->isDirty('players_per_team') and $cup->countParticipants()) {
                throw new MsgException('You cannot change the "players per team" value when a cup has participants!');
            }
        });

        self::saved(function($cup)
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
     * @return Collection
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
     * @return Collection
     */
    public function matches()
    {
        return $this->hasMany('App\Modules\Cups\Match')->orderBy('round')->orderBy('row');
    }

    /**
     * Returns all matches of this cup with their participants.
     * This imitates Eloquent's eager loading so it only executes three MySQL queries.
     *
     * @param bool refresh If true, force database query
     * @return Collection
     */
    public function matchesDetailed($refresh = false)
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
            $match->left_participant = $leftParticipants->where('id',  $match->left_participant_id)->pop();
            $match->right_participant = $rightParticipants->where('id',  $match->right_participant_id)->pop();
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
    public function scopePublished($query)
    {
        return $query->wherePublished(true);
    }

    /**
     * Returns an array that uses the values of another array as values and keys.
     * 
     * @param  string $attribute The name of thea ttribute that contains the source array
     * @return array
     */
    public static function makeOptionArray($attribute) 
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
     * @return boolean
     */
    public function forTeams()
    {
        return ($this->players_per_team > 1);
    }

    /**
     * Returns an array with the IDs of the participants of this cup
     * 
     * @return array
     */
    public function participantIds()
    {
        return DB::table('cups_participants')->whereCupId($this->id)->lists('participant_id');
    }

    /**
     * Counts then participants of this cup
     * 
     * @return int
     */
    public function countParticipants()
    {
        return DB::table('cups_participants')->whereCupId($this->id)->count();
    }

    /**
     * Returns the participating object of a given user for this cup:
     * - Returns null if the user does not participate.
     * - Returns the user object if it is a 1on1.
     * - Returns the team of the user if it is a team cup.
     * 
     * @param  User $user
     * @return Team|User|null
     */
    public function getParticipantOfUser($user)
    {
        if (! $user) {
            return null;
        }

        if ($this->forTeams()) {
            $teams = $this->participants;
            $userTeamIds = DB::table('cups_team_members')->whereUserId($user->id)->lists('team_id');

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

            if (in_array($user->id, $participantIds)) {
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
     * @return boolean
     */
    public function isUserInCup($user) 
    {
        return ($this->getParticipantOfUser($user) !== null);
    }

    /**
     * Returns true if a participant has checked-in to this cup.
     * 
     * @param  Team|User $participant
     * @return boolean
     */
    public function hasParticipantCheckedIn($participant)
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
    public function teamsOfUser($user, $isOrganizer = false)
    {
        $query = DB::table('cups_team_members')->whereUserId($user->id);

        if ($isOrganizer) {
            $query->whereOrganizer(true);
        }

        $userTeamIds = $query->lists('team_id');

        return Team::whereIn('id', $userTeamIds)->get();
    }

    /**
     * Returns all cups of a given user.
     * 
     * @param User $user     The user object
     * @param bool $onlyOpen If true return only cups that still are open
     * @return Collection|null
     */
    public function cupsByUser($user, $onlyOpen = false)
    {
        if (! $user) {
            return null;
        }

        $query = DB::table('cups_users')->whereUserId($user->id);
        if ($onlyOpen) {
            $query->whereCupClosed(false);
        }
        $userCupIds = $query->lists('cup_id');

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
     * @param  int $participants Number of participants
     * @return int
     */
    public function rounds($participants = null)
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
     * Returns collection of participants to render bracket in JSON.
     * 
     * @return array
     */
    public function renderBracket()
    {

        $matches = Match::select('left_participant_id', 'left_score', 'right_participant_id', 'right_score', 'round', 'row')->whereCupId($this->id)->whereRound(1)->orderBy('row', 'asc')->get();
        $cup = Cup::select('slots')->whereId($this->id)->first();

        $teams = array();
        $results = array();
        $nbRounds = log($cup->slots)/log(2);
        $rounds = array();

        for ($i = 1; $i <= $nbRounds; $i++) {
            $rounds[] = $i;
        }

        foreach ($matches as $key => $match) {
            $teams[] = array($match->left_participant ? $match->left_participant->username : "Wildcard", $match->right_participant ? $match->right_participant->username : "Wildcard");
        }

        foreach ($rounds as $round) {
            $matches = Match::select('left_participant_id', 'left_confirmed', 'right_confirmed', 'left_score', 'right_participant_id', 'right_score', 'round')->whereCupId($this->id)->whereRound($round)->orderBy('row', 'asc')->get();
            foreach ($matches as $match) {
                if($match->left_confirmed && $match->right_confirmed) {
                    $results[0][$round][] = array($match->left_score, $match->right_score);
                }
                else {
                    $results[0][$round][] = array(0, 0);
                }
            }
        }

        array_splice($results[0], $cup->slots / 2);
        
        $teams = json_encode($teams);
        $results = json_encode($results);
        $bracket = array('teams' => $teams, 'results' => $results);

        return $bracket;
    }

}
