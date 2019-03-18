<?php 

namespace App\Modules\Search\Http\Controllers;

use BadMethodCallException;
use Contentify\GlobalSearchInterface;
use Exception;
use FrontController;
use Input;
use Redirect;
use Validator;

class SearchController extends FrontController
{

    public function getIndex()
    {
        $this->pageView('search::form');
    }

    /**
     * @throws Exception
     */
    public function postCreate()
    {
        $subject = Input::get('subject');

        $validator = Validator::make(['subject' => $subject], ['subject' => 'required|min:3']);
        
        if ($validator->passes()) {
            $resultBags = $this->performSearch($subject);
            
            Input::flash(); // We keep the subject and display it in the form
            $this->pageView('search::form', compact('resultBags'));
        } else {
            return Redirect::to('search')->withInput()->withErrors($validator->messages());
        }
    }
    
    /**
     * Performs a global search in the frontend. Returns the results as an array of arrays.
     *
     * @param string $subject
     * @return array
     */
    public function performSearch(string $subject) : array
    {
        /** @var \Caffeinated\Modules\Contracts\Repository $moduleRepo */
        $moduleRepo = app()['modules'];
        $modules = $moduleRepo->all(); // Retrieve all module info objects

        $resultBags = array();
        foreach ($modules as $module) {
            if (! $module['enabled']) continue;

            $controllerNames = isset($module['search']) ? $module['search'] : null;
            if ($controllerNames) {
                // A module might have more than one controller that supports the search:
                foreach ($controllerNames as $controllerName) {
                    $classPath = 'App\Modules\\'.ucfirst($module['slug'])
                        .'\Http\Controllers\\'.ucfirst($controllerName).'Controller';

                    $controller = new $classPath; // Create an instance of the controller...

                    if (! $controller instanceof GlobalSearchInterface) {
                        throw new Exception(
                            'Error: Controller "'.$classPath.'" does not implement the GlobalSearchInterface. '.
                            'You have to implement this interface if you want this controller to be searchable.'
                        );
                    }

                    $results = $controller->globalSearch($subject); // ...and call the search method.

                    if (is_array($results) and sizeof($results) > 0) {
                        $resultBags[] = ['title' => $controllerName, 'results' => $results];
                    }
                }
            }
        }

    }
}
