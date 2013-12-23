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

	protected function buildIndexForm($data)
	{
		$defaults = array(
			'tableHead'	=> null,
			'tableRow'	=> null
			);

		$data = array_merge($defaults, $data);

		$model = $this->form['modelName'];
		$entities = $model::all();

		$tableHead = array();
		foreach ($data['tableHead'] as $title => $order) {
			$tableHead[] = $title;
		}

		$tableRows = array();
		foreach ($entities as $entity) {
			$tableRows[] = $data['tableRow']($entity);
		}

		$table = $this->contentTable($tableHead, $tableRows, true);
		$this->pageOutput($table);
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

	/**
	 * Returns HTML code for a table.
	 * $header = Array with the table header items (String-Array)
	 * $rows = Array with all the table rows items (Array containing String-Arrays)
	 * $highlightfirst = Enable special look for the items in the first column? (true/false)
	 * @param array     $header
	 * @param array 	$rows
	 * @param bool 		$highlightFirst
	 * @return string
	 */
	protected function contentTable($header, $rows, $highlightFirst = true)
	{
		$code = '<table class="content-table">';

		/*
		 * Table head
		 */
		$code .= '<tr>';
		foreach ($header as $value) {
			$code .= '<th>';
			$code .= $value;
			$code .= '</th>';
		}
		$code .= '</tr>';

		/*
		 * Table body
		 */
		foreach ($rows as $row) {
			$code 	.= '<tr>';
			$isFirst = true;
			foreach ($row as $value) {
				if ($isFirst == true and $highlightFirst == true) {
					$code .= '<td style="color: silver">';
					$isFirst = false;
				} else {
					$code .= '<td>';
				}
				$code .= $value;
				$code .= '</td>';
			}
			$code .= '</tr>';
		}

		$code .= '</table>';

		return $code;
	}
}