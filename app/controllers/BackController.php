<?php

class BackController extends BaseController {
	/**
     * The layout that should be used for responses.
     */
    protected $layout = 'backend';

    /**
    * 
    */
    protected $form = array(
    	'model' 		=> '',
    	'module' 		=> '',
    	'form' 			=> '',
    	'modelName'		=> ''
    );

	/**
	 * Constructor call
	 */
	public function __construct()
	{
		parent::__construct();

		// Enable auto CSRF protection
		$this->beforeFilter('csrf', array('on' => 'post'));

		if ($this->form['module'] == '') $this->form['module'] = str_plural($this->form['model']); 
		if ($this->form['form'] == '') $this->form['form'] = 'form';
		if ($this->form['modelName'] == '') $this->form['modelName'] = 'App\Modules\\'.$this->form['module'].'\Models\\'.$this->form['model'];

	}

	public function index()
	{
		//die('<a href="'.route('admin.games.destroy', 17).'?method=delete">Click me!</a>');

		$this->message('Index of '.$this->form['module'].' called!');
	}

	public function create()
	{
		$this->pageView(strtolower($this->form['module']).'::'.$this->form['form']);
	}

	public function store()
	{
		$entity = new $this->form['modelName'](Input::all());

		$entity->save();

		$this->messageFlash($this->form['model'].t(' created.'));
		return Redirect::route('admin.'.strtolower($this->form['module']).'.index');
	}

	public function edit($id)
	{
		$model = $this->form['modelName'];
		$entity = $model::findOrFail($id);

		$this->pageView(
			strtolower($this->form['module']).'::'.$this->form['form'], 
			array('entity' => $entity)
		);
	}

	public function update($id)
	{
		$model = $this->form['modelName'];
		$entity = $model::findOrFail($id);

		$entity->fill(Input::all());
		$entity->save();

		return Redirect::route('admin.'.strtolower($this->form['module']).'.index');
	}

	public function destroy($id)
	{
		$model = $this->form['modelName'];
		$model::destroy($id);

		return Redirect::route('admin.'.strtolower($this->form['module']).'.index');
	}
}