<?php 

namespace App\Modules\Shouts\Http\Controllers;

use App\Modules\Shouts\Shout;
use DB;
use FrontController;
use Input;
use Response;

class ShoutsController extends FrontController
{

    /**
     * Stores a shout
     * 
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        if (! user()) {
            return Response::make(null, 403); // 403: Not allowed
        }

        $this->deleteOld();

        $shout = new Shout(['text' => htmlspecialchars(Input::get('text'))]);
        $shout->creator_id = user()->id;

        $okay = $shout->save();

        if (! $okay) {
            return Response::make(null, 400);
        } else {
            return Response::make(null, 200);
        }
    }

    /**
     * Deletes all shouts that are not part of the 20 newest shouts
     *
     * @return void
     */
    protected function deleteOld()
    {
        $ids = DB::table('shouts')->orderBy('created_at', 'desc')->take(20)->pluck('id')->toArray();

        $ids[] = 0;

        DB::table('shouts')->whereNotIn('id', $ids)->delete();
    }

}