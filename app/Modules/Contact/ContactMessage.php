<?php namespace App\Modules\Contact;

use Mail, Sentinel, SoftDeletingTrait, BaseModel, User;

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
        $users = User::findAllUsersWithAccess('contact', PERM_READ);

        if (! $users) {
            return;
        }

        Mail::send('contact::emails.notify', ['msg' => $this], function ($mail) use ($users) {
            $user = $users->pop();

            $mail->to($user->email, $user->username);

            while ($user = $users->pop()) {
                $mail->cc($user->email, $user->username);
            }

            $mail->subject(trans('app.new').': '.trans('app.object_contact_message'));
        });
    }

}
