<?php namespace App\Modules\Friends\Controllers;

use App\Modules\Friends\Models\Friendship;
use App\Modules\Messages\Models\Message;
use User, DB, Redirect, FrontController;

class FriendsController extends FrontController {

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
     * @return Redirect
     */
    public function add($id)
    {
        $friend = User::findOrFail($id);

        $friendship = Friendship::areFriends(user()->id, $id)->first();

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
     * @return Redirect
     */
    public function confirm($id)
    {
        $friend = User::findOrFail($id);

        $friendship = Friendship::areFriends(user()->id, $id)->first();

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
     * Destroys a friendship (=destroys the user-to-user-relationship)
     * 
     * @param  int $id The ID if the user (friend)
     * @return Redirect
     */
    public function destroy($id)
    {
        $friend = User::findOrFail($id);
        
        $friendship = Friendship::areFriends(user()->id, $id)->firstOrFail();

        // It's important that users can only delete confirmed friendship to avoid abuse for friendship request spamming
        DB::table('friends')->whereSenderId($friendship->sender_id)->whereReceiverId($friendship->receiver_id)
            ->whereConfirmed(true)->delete();

        $friend->sendSystemMessage(
            trans('friends::deletion_title'),
            trans('friends::deletion_text', [user()->username])
        );

        $this->alertFlash(trans('app.deleted', ['Friendship'])); // Friendship terminated. Take this, diction!
        return Redirect::to('friends/'.user()->id);
    }
    
}