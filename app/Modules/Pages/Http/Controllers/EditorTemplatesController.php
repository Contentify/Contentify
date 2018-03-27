<?php 

namespace App\Modules\Pages\Http\Controllers;

use App\Modules\Pages\Fragment;
use BackController;
use Response;
use View;

class EditorTemplatesController extends BackController
{

    /**
     * Returns a template with a HTML select element that includes all editor templates
     * 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        if (! $this->checkAccessUpdate()) {
            return Response::make(null, 403);
        }

        $templates = Fragment::all();

        return View::make('pages::editor_templates', compact('templates'));
    }

    /**
     * Returns an editor template
     * 
     * @param  int  $id The ID of the template
     * @return string|\Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! $this->checkAccessUpdate()) {
            return Response::make(null, 403);
        }

        $template = Fragment::findOrFail($id);

        return $template->text;
    }

}