<?php namespace App\Modules\Auth\Controllers;

use Cartalyst\Sentry\Users\UserNotFoundException;
use Str, Mail, Sentry, Redirect, Captcha, User, Input, FrontController;

class RestorePasswordController extends FrontController {
    
    public function getIndex()
    {
        $this->pageView('auth::restore_password');
    }

    public function postIndex()
    {
        if (! Captcha::check(Input::get('captcha'))) {
            return Redirect::to('auth/restore')
                ->withInput()->withErrors(['message' => trans('app.captcha_invalid')]);
        }

        $email = Input::get('email');

        try {
            $user = Sentry::findUserByLogin($email);    

            $user->getResetPasswordCode(); // This will generate (and return) a new code

            Mail::send('auth::emails.restore_password', compact('user'), function($message) use ($email, $user)
            {
                $message->to($email, $user->username)->subject('Password Reset');
            });

            $this->message(t('An email was sent to the given email address. Follow the instruction to generate a new password.'));

            $this->pageView('auth::emails.restore_password', compact('user')); // DEBUG
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            $this->message(t('No user found with the given email address.'));
            return;
        }
    }

    public function getNew($email, $code)
    {
        try {
            $user = Sentry::findUserByLogin($email);

            if ($user->reset_password_code != $code) {
                $this->message(t('Code does not match.'));
                return;
            }

            $password = strtolower(Str::random(9)); // Generate a new password

            $this->message(t('Your new password is: '.$password));

            /*
             * Save the new password. Please note that we do not need to
             * crypt the password. The user model inherits from SentryUser and
             * will do the work.
             */
            $user->password = $password; 
            $user->save();
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            $this->message(t('No user found with the given email address.'));
            return;
        }
    }
}