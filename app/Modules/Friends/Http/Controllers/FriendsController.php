<?php

namespace App\Modules\Friends\Http\Controllers;

use App\Modules\Friends\Friendship;
use DB;
use FrontController;
use Illuminate\Http\RedirectResponse;
use Redirect;
use User;

class FriendsController extends FrontController
{

    /**
     * Shows the friends of a user
     * 
     * @param  int $id The ID of the user
     * @return mixed
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        $this->pageView('friends::index', compact('user'));
    }

    /**
     * Tries to send a friendship request to a user
     * 
     * @param int $id The ID if the user
     * @return RedirectResponse
     */
    public function add($id)
    {
        /** @var User $friend */
        $friend = User::findOrFail($id);

        /** @var Friendship $friendship */
        $friendship = Friendship::areFriends(user()->id, $id, false)->first();

        $friendshipImpossible = ($friendship and 
            ($friendship->confirmed or $friendship->messaged_at->timestamp > time() - 60 * 10)); // 10 Minutes

        if ($friendshipImpossible or user()->id == $friend->id) {
            $this->alertFlash(trans('friends::request_error'));
            return Redirect::to('users/'.$friend->id);
        }

        if ($friendship) {
            DB::table('friends')->whereSenderId($friendship->sender_id)->whereReceiverId($friendship->receiver_id)
                ->update(['messaged_at' => DB::raw('NOW()')]);
        } else {
            DB::table('friends')->insert([
                'sender_id'     => user()->id, 
                'receiver_id'   => $friend->id,
                'messaged_at'   => DB::raw('NOW()'),
            ]);
        }

        $friend->sendSystemMessage(
            trans('friends::request_title'),
            trans('friends::request_text', [user()->username]).link_to('friends/confirm/'.user()->id, 'Accept')
        );

        $this->alertFlash(trans('friends::request_sent', [$friend->username]));
        return Redirect::to('users/'.$id);
    }

    /**
     * Confirms a friendship
     * 
     * @param  int $id The ID if the user
     * @return RedirectResponse
     */
    public function confirm($id)
    {
        /** @var User $friend */
        $friend = User::findOrFail($id);

        /** @var Friendship $friendship */
        $friendship = Friendship::areFriends(user()->id, $id, false)->first();

        if (! $friendship or $friendship->confirmed) {
            $this->alertFlash(trans('friends::request_error'));
            return Redirect::to('users/'.$friend->id);
        }

        DB::table('friends')->whereSenderId($friendship->sender_id)->whereReceiverId($friendship->receiver_id)
                ->update(['confirmed' => 1]);

        $this->alertFlash(trans('friends::request_accepted'));
        return Redirect::to('users/'.$id);
    }

    /**
     * Destroys a friendship (= destroys the user-to-user-relationship)
     * 
     * @param  int $id The ID if the user (friend)
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        /** @var User $friend */
        $friend = User::findOrFail($id);
        
        // NOTE: Only receive confirmed friendships.
        // It's important that users can only delete confirmed friendship to avoid abuse for friendship request spamming
        /** @var Friendship $friendship */
        $friendship = Friendship::areFriends(user()->id, $id)->firstOrFail();

        if ($friendship) {
            DB::table('friends')->whereSenderId($friendship->sender_id)->whereReceiverId($friendship->receiver_id)
                ->delete();

            $friend->sendSystemMessage(
                trans('friends::deletion_title'),
                trans('friends::deletion_text', [user()->username])
            );
        }

        $this->alertFlash(trans('app.deleted', ['Friendship'])); // Friendship terminated. Take this, diction!
        return Redirect::to('friends/'.user()->id);
    }
    
}