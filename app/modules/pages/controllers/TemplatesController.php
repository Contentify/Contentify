<?php namespace App\Modules\Pages\Controllers;

use App\Modules\Pages\Models\Fragment;
use View, BackController;

class TemplatesController extends BackController {

    /**
     * Returns a template with a select with all editor templates
     * 
     * @return View
     */
    public function index()
    {
        // TODO: Rechte checken

        $templates = Fragment::all();

        return View::make('pages::templates', compact('templates'));
    }

    /**
     * Returns an editor template
     * 
     * @param  int  $id The ID of the template
     * @return View
     */
    public function show($id)
    {
        // TODO: Rechte checken

        $template = Fragment::findOrFail($id);

        return $template->text;
    }

}