<?php 

namespace App\Modules\Pages\Http\Controllers;

use App\Modules\Pages\Fragment;
use InvalidArgumentException;
use Widget;

class FragmentWidget extends Widget
{

    public function render(array $parameters = [])
    {
        if (isset($parameters['id'])) {
            $id = $parameters['id'];
        } else {
            throw new InvalidArgumentException('Fragment Widget: ID parameter missing.');
        }

        /** @var Fragment $fragment */
        $fragment = Fragment::findOrFail($id);

        return $fragment->text;
    }

}
