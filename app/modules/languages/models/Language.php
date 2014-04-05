<?php namespace App\Modules\Languages\Models;

use Exception, Ardent;

class Language extends Ardent {

    protected $fillable = ['title', 'code'];

    public static $rules = [
        'title' => 'required',
        'code'  => 'required',
    ];

    /**
     * A "language" is not only a row of a table but also consists of language files.
     * Users cannot create (a new) or delete a language trough this model.
     */
    public static function destroy($ids) {
        throw new Exception('Model deletion restricted');
    }

    public function delete() {
        throw new Exception('Model deletion restricted');
    }

}