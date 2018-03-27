<?php

namespace Contentify;

use DB;
use Input;
use Response;
use View;

class Ratings
{

    /**
     * Minimum value for a valid rating (must be <= MAX_RATING)
     */
    const MIN_RATING = 1;

    /**
     * Maximum value for a valid rating (must be >= MIN_RATING)
     */
    const MAX_RATING = 5;

    /**
     * Directly outputs the rating HTML code.
     * 
     * @param  string $foreignType Identifier for the content the rating is related to (usually a model class)
     * @param  int    $foreignId   ID, if the rating is related to a certain model instance
     * @return string
     */
    public function show($foreignType, $foreignId)
    {
        $rating = DB::table('ratings')->where('foreign_type', '=', $foreignType)->
            where('foreign_id', '=', $foreignId)->avg('rating');

        if ($rating === null) {
            $rating = 0;
        }

        $myRating = null;
        if (user()) {
            $result = DB::table('ratings')->where('foreign_type', '=', $foreignType)->
                where('foreign_id', '=', $foreignId)->whereUserId(user()->id)->first();

            if ($result) {
                $myRating = $result->rating;
            }
        }

        $maxRating = self::MAX_RATING;

        echo View::make('rating', compact('rating', 'myRating', 'foreignType', 'foreignId', 'maxRating'))->render();
    }

    /**
     * Stores a rating
     * 
     * @param  string   $foreignType The foreign type identifier
     * @param  int      $foreignId   The foreign id (can be 0)
     * @return \Illuminate\Http\Response
     */
    public function store($foreignType, $foreignId)
    {
        if (! user() or ! user()->hasAccess('ratings', PERM_CREATE)) {
            return Response::make(trans('app.access_denied'), 403);
        }

        $rating = (int) Input::get('rating');

        if ($rating < self::MIN_RATING) {
            $rating = self::MIN_RATING;
        }
        if ($rating > self::MAX_RATING) {
            $rating = self::MAX_RATING;
        }

        $result = DB::table('ratings')->where('foreign_type', '=', $foreignType)->
                where('foreign_id', '=', $foreignId)->whereUserId(user()->id)->first();
        if ($result) {
            return Response::make('0', 500);
        }

        DB::table('ratings')->insert([
            'rating'        => $rating,
            'foreign_type'  => $foreignType, 
            'foreign_id'    => $foreignId,
            'user_id'       => user()->id,
        ]);

        return Response::make('1', 200);
    }

}