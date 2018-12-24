<?php

namespace App\Modules\Contact;

use BaseModel;
use Mail;
use SoftDeletingTrait;
use User;

/**
 * @property \Carbon $created_at
 * @property \Carbon $deleted_at
 * @property string  $username
 * @property string  $email
 * @property string  $title
 * @property string  $text
 * @property bool    $new
 * @property string  $ip
 */
class ContactMessage extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['username', 'email', 'title', 'text'];

    protected $rules = [
        'username'  => 'required',
        'email'     => 'required|email',
        'title'     => 'required|min:3',
        'text'      => 'required|min:3',
    ];

    /**
     * Notifies admins about a new contact message
     * 
     * @return void
     */
    public function notify()
    {
        $users = User::findAllUsersWithAccess('contact', PERM_READ);

        if (! $users) {
            return;
        }

        Mail::send('contact::emails.notify', ['msg' => $this], function ($message) use ($users) {
            $user = $users->pop();

            /** @var \Illuminate\Mail\Message $message */
            $message->to($user->email, $user->username);

            while ($user = $users->pop()) {
                $message->cc($user->email, $user->username);
            }

            $message->subject(trans('app.new').': '.trans('app.object_contact_message'));
        });
    }

}
