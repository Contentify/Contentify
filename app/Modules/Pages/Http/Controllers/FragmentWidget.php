<?php namespace App\Modules\Pages\Http\Controllers;

use App\Modules\Pages\Models\Fragment;
use Exception, Widget;

class FragmentWidget extends Widget {

    public function render($parameters = array())
    {
        if (isset($parameters['id'])) {
            $id = $parameters['id'];
        } else {
            throw new Exception('Fragment Widget: ID parameter missing');
        }

        $fragment = Fragment::findOrFail($id);

        return $fragment->text;
    }

}