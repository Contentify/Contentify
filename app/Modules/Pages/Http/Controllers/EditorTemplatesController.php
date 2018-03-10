<?php 

namespace App\Modules\Pages\Http\Controllers;

use App\Modules\Pages\Fragment;
use View, BackController;

class EditorTemplatesController extends BackController {

    /**
     * Returns a template with a select with all editor templates
     * 
     * @return View
     */
    public function index()
    {
        if (! $this->checkAccessUpdate()) return Response::make(null, 403);

        $templates = Fragment::all();

        return View::make('pages::editor_templates', compact('templates'));
    }

    /**
     * Returns an editor template
     * 
     * @param  int  $id The ID of the template
     * @return View
     */
    public function show($id)
    {
        if (! $this->checkAccessUpdate()) return Response::make(null, 403);

        $template = Fragment::findOrFail($id);

        return $template->text;
    }

}