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
		    $modules = $finder->modules();

		    $resultBags = array();
		    foreach ($modules as $module) {
		    	$controllers = $module->def('search'); // def() will return null if "search" is not defined
		        if ($controllers) { 
		        	if (! is_array($controllers)) dd($module);
		        	foreach ($controllers as $controller) {
		        		$classPath = 'App\Modules\\'.ucfirst($module->name()).'\Controllers\\'.ucfirst($controller).'Controller';
		        		$instance = new $classPath;
		        		$results = $instance->search($subject);
		        	}

		        	if ($results) {
		        		$resultBags[] = ['title' => $controller, 'results' => $results];
		        	}
		        }
		    }

		    $this->pageView('search::form', ['resultBags' => $resultBags]);
		} else {
			return Redirect::to('search')->withInput()->withErrors($validator->messages());
		}
	}
}