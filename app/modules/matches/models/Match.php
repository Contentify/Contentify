<?php namespace App\Modules\Matches\Models;

use ContentFilter, SoftDeletingTrait, BaseModel;

class Match extends BaseModel {

    use SoftDeletingTrait;

    /**
     * Match state "open"
     */
    const STATE_OPEN = 0;

    /**
     * Match state "closed"
     */
    const STATE_CLOSED = 1;

    /**
     * Match state "hidden"
     */
    const STATE_HIDDEN = 2;

    /**
     * Match state "delayed"
     */
    const STATE_DELAYED = 3;

    protected $dates = ['deleted_at', 'played_at'];

    protected $fillable = [
        'state', 
        'featured', 
        'url', 
        'broadcast', 
        'left_lineup',
        'right_lineup',
        'text',
        'played_at',
        'game_id',
        'tournament_id',
        'left_team_id',
        'right_team_id',
    ];

    protected $rules = [
        'state'     => 'integer|min:0',
        'featured'  => 'boolean',
        'url'       => 'sometimes|url',
    ];

    public static $relationsData = [
        'matchScores'  => [self::HAS_MANY, 'App\Modules\Matches\Models\MatchScore'],
        'game'         => [self::BELONGS_TO, 'App\Modules\Games\Models\Game'],
        'tournament'   => [self::BELONGS_TO, 'App\Modules\Tournaments\Models\Tournament'],
        'leftTeam'     => [self::BELONGS_TO, 'App\Modules\Teams\Models\Team'],
        'rightTeam'    => [self::BELONGS_TO, 'App\Modules\Opponents\Models\Opponent'],
        'creator'      => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    /**
     * Array with the names of available match states.
     * // TODO: Localization?
     * @var array
     */
    public static $states = [
        self::STATE_OPEN      => 'Open', 
        self::STATE_CLOSED    => 'Closed', 
        self::STATE_HIDDEN    => 'Hidden', 
        self::STATE_DELAYED   => 'Delayed'
    ];

    public static function boot()
    {
        parent::boot();

        self::saved(function($match)
        {
            /*
             * Apply the right lineup to its team. This makes
             * on-the-fly team lineup changes possible.
             */
            if ($match->right_lineup) {
                $match->right_team->lineup = $match->right_lineup;
                $match->right_team->save();
            }
        });
    }

    public function scopeFilter($query)
    {
        if (ContentFilter::has('team_id')) {
            $id = (int) ContentFilter::get('team_id');
            return $query->whereLeftTeamId($id);
        }
    }

    /**
     * Count the comments that are related to this match.
     * 
     * @return int
     */
    public function countComments()
    {
        return Comment::count('match', $this->id);
    }

    /**
     * Returns the score of the match with HTML spans which 
     * indicate if the left or the right team is the winner.
     * 
     * @return string The HTML code
     */
    public function scoreCode()
    {
        $leftScore  = $this->left_score;
        $rightScore = $this->right_score;

        if ($this->state != self::STATE_CLOSED and $leftScore == 0 and $rightScore == 0) {
            $leftScore  = '?';
            $rightScore = '?';
        }

        if ($this->left_score > $this->right_score) {
            return '<span class="left-win"><span class="win">'.$leftScore.
                '</span>:<span class="defeat">'.$rightScore.'</span></span>';
        } elseif ($this->left_score < $this->right_score) {
            return '<span class="left-defeat"><span class="defeat">'.$leftScore.
                '</span>:<span class="win">'.$rightScore.'</span></span>';
        } else {
            return '<span class="left-draw"><span class="draw">'.$leftScore.
                '</span>:<span class="draw">'.$rightScore.'</span></span>';
        }
    }

    /**
     * Updates the match score that is directly written
     * to the match model. That's a shortcut so you don't have to
     * run trough all map results each time you want to know
     * what the overall result is.
     *
     * @return void
     */
    public function updateScore()
    {
        $matchScores = $this->match_scores;

        if (sizeof($matchScores) == 0) {
            $this->left_score  = 0;
            $this->right_score = 0;
        } elseif (sizeof($matchScores) == 1) {
            $this->left_score  = $matchScores[0]->left_score;
            $this->right_score = $matchScores[0]->right_score;
        } else {
            $this->left_score  = 0;
            $this->right_score = 0;

            foreach ($matchScores as $matchScore) {
                if ($matchScore->left_score > $matchScore->right_score) {
                     $this->left_score++;
                } elseif ($matchScore->left_score < $matchScore->right_score) {
                    $this->right_score++;
                }
                // Ignore draws
            }
        }
        
        $this->forceSave();
    }

}