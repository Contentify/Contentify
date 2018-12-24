<?php

namespace App\Modules\Contact;

use BaseModel;

/**
 * NOTE: This is only a helper model for validation!
 *
 * @property string $username
 * @property string $email
 * @property int    $team_id
 * @property string $role
 * @property string $text
 */
class ApplicationMessage extends BaseModel
{

    protected $fillable = ['username', 'email', 'team_id', 'role', 'text'];

    protected $rules = [
        'username'  => 'required',
        'email'     => 'required|email',
        'team_id'   => 'required|integer|min:1',
        'role'      => 'required|min:3',
        'text'      => 'required|min:3',
    ];

}
