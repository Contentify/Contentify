<?php namespace App\Modules\Search\Http\Controllers;

use Input, Redirect, Validator, FrontController;

class SearchController extends FrontController {

    public function getIndex()
    {
        $this->pageView('search::form');
    }

    public function postCreate()
    {
        $subject = Input::get('subject');

        $validator = Validator::make(['subject' => $subject], ['subject' => 'required|min:3']);
        
        if ($validator->passes()) {
            $finder = app()['modules'];
            $modules = $finder->modules(); // Retrieve all module info objects

            $resultBags = array();
            foreach ($modules as $module) {
                if (! $module->def('enabled')) continue;
                
                $controllers = $module->def('search'); // def() will return null if "search" is not defined
                if ($controllers) { 
                    // A module might have more than one controller that supports the search:
                    foreach ($controllers as $controller) { 
                        $classPath = 'App\Modules\\'.ucfirst($module->name())
                            .'\Http\Controllers\\'.ucfirst($controller).'Controller';
                        $instance = new $classPath; // Create isntance of the controller...
                        $results = $instance->globalSearch($subject); // ...and call the search method.
                    }

                    if (sizeof($results) > 0) {
                        // Wrap the results in a result bag:
                        $resultBags[] = ['title' => $controller, 'results' => $results]; 
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