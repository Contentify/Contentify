<?php namespace App\Modules\Auth\Controllers;

use Cartalyst\Sentry\Users\UserNotFoundException;
use Str, Mail, Sentry, Redirect, Captcha, User, Input, FrontController;

class RestorePasswordController extends FrontController {
    
    public function getIndex()
    {
        $this->pageView('auth::restore_password');
    }

    /**
     * This method will generate a reset password code, save it with the user entity
     * and send it to the user's email address.
     */
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

            $this->message(
                t('An email has been to the given email address. Follow the instruction to generate a new password.')
            );

            //$this->pageView('auth::emails.restore_password', compact('user')); // DEBUG
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            $this->message(t('No user found with the given email address.'));
            return;
        }
    }

    /**
     * This method will check email and the submitted code (it is included into t he URL)
     * and if they pass generate a new password and send it to the user.
     * 
     * @param  string $email The user's email adress
     * @param  string $code  Reset password code
     */
    public function getNew($email, $code)
    {
        try {
            $user = Sentry::findUserByLogin($email);

            if ($user->reset_password_code != $code) {
                $this->message(t('Code does not match.'));
                return;
            }

            $password = strtolower(Str::random(9)); // Generate a new password

            Mail::send('auth::emails.send_password', compact('user'), function($message) use ($email, $user, $password)
            {
                $message->to($email, $user->username)->subject('New Password');
            });

            $this->message(
                t('An email with your new password has been sent to your email address.')
            );

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