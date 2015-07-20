<?php namespace App\Modules\Contact;

use Mail, Sentry, SoftDeletingTrait, BaseModel;

class ContactMessage extends BaseModel {

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
        $users = Sentry::findAllUsersWithAccess('contact');

        if (! $users) {
            return;
        }

        Mail::send('contact::emails.notify', ['msg' => $this], function ($mail) use ($users) {
            $user = array_pop($users);

            $mail->to($user->email, $user->username);

            while ($user = array_pop($users)) {
                $mail->cc($user->email, $user->username);
            }

            $mail->subject('New Contact Message');
        });
    }

}