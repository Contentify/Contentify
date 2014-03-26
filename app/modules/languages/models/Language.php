<?php namespace App\Modules\Languages\Models;

use Exception, Ardent;

class Language extends Ardent {

    protected $fillable = ['title', 'code'];

    public static $rules = [
        'title' => 'required',
        'code'  => 'required',
    ];

    public static function destroy($ids) {
        throw new Exception('Model deletion restricted');
    }

    public function delete() {
        throw new Exception('Model deletion restricted');
    }

}