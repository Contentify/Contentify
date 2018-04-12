<?php

namespace App\Modules\Languages;

use BaseModel;
use Exception;

/**
 * @property string $title
 * @property string $code
 */
class Language extends BaseModel
{

    protected $fillable = ['title', 'code'];

    protected $rules = [
        'title' => 'required|min:3',
        'code'  => 'required|max:6',
    ];

    /**
     * A "language" is not only a row of a table but also consists of language files.
     * Users cannot create (a new) or delete a language trough this model.
     *
     * {@inheritdoc}
     */
    public static function destroy($ids) {
        throw new Exception('Model deletion forbidden');
    }

    public function delete() {
        throw new Exception('Model deletion forbidden');
    }

}