<?php namespace App\Modules\Search\Controllers;

use Input, Redirect, Validator;

class SearchController extends \FrontController {

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
		    	$controllers = $module->def('search'); // def() will return null if "search" is not defined
		        if ($controllers) { 
		        	foreach ($controllers as $controller) { // A module might have more than one controller that supports the search
		        		$classPath = 'App\Modules\\'.ucfirst($module->name()).'\Controllers\\'.ucfirst($controller).'Controller';
		        		$instance = new $classPath; // Create isntance of the controller...
		        		$results = $instance->search($subject); // ...and call the search method.
		        	}

		        	if (sizeof($results) > 0) {
		        		$resultBags[] = ['title' => $controller, 'results' => $results]; // Wrap the results in a result bag
		        	}
		        }
		    }

		    Input::flash(); // We keep the subject and display in the form
		    $this->pageView('search::form', ['resultBags' => $resultBags]);
		} else {
			return Redirect::to('search')->withInput()->withErrors($validator->messages());
		}
	}
}