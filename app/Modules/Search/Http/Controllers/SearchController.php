<?php 

namespace App\Modules\Search\Http\Controllers;

use Exception, BadMethodCallException, Input, Redirect, Validator, FrontController;

class SearchController extends FrontController
{

    public function getIndex()
    {
        $this->pageView('search::form');
    }

    public function postCreate()
    {
        $subject = Input::get('subject');

        $validator = Validator::make(['subject' => $subject], ['subject' => 'required|min:3']);
        
        if ($validator->passes()) {
            $moduleRepo = app()['modules'];
            $modules = $moduleRepo->all(); // Retrieve all module info objects

            $resultBags = array();
            foreach ($modules as $module) {
                if (! $module['enabled']) continue;
                
                $controllers = isset($module['search']) ? $module['search'] : null;
                if ($controllers) { 
                    // A module might have more than one controller that supports the search:
                    foreach ($controllers as $controller) { 
                        $classPath = 'App\Modules\\'.ucfirst($module['slug'])
                            .'\Http\Controllers\\'.ucfirst($controller).'Controller';

                        $instance = new $classPath; // Create instance of the controller...

                        try {
                            $results = $instance->globalSearch($subject); // ...and call the search method.
                        } catch(BadMethodCallException $ex) {
                            throw new Exception(
                                'Error: Controller "'.$classPath.'" does not implement the globalSearch() method. '.
                                'You have to implement this method if you want this controller to be searchable.'
                            );
                        }
                        
                        if (sizeof($results) > 0) {
                            $resultBags[] = ['title' => $controller, 'results' => $results];
                        }
                    }
                }
            }

            Input::flash(); // We keep the subject and display it in the form
            $this->pageView('search::form', compact('resultBags'));
        } else {
            return Redirect::to('search')->withInput()->withErrors($validator->messages());
        }
    }
}
