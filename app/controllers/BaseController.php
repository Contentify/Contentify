<?php

class BaseController extends Controller {

    /**
     * The name of the module
     * @var string
     */
    protected $module = '';

    /**
     * The name of the controller (without class path)
     * @var string
     */
    protected $controller = '';

    /**
     * The name of the model (without class path)
     * @var string
     */
    protected $model = '';

    /**
     * The name of the model (with class path)
     * @var string
     */
    protected $modelFullName = '';

    /**
     * The name of the form template (for CRUD auto handling)
     * @var string
     */
    protected $formTemplate = '';

	/**
	 * Constructor call
	 */
	public function __construct()
	{
        /*
         * Save module and controller name
         */
        $className          = get_class($this);
        $this->module       = explode('\\', $className)[2];
        $className          = class_basename($className);
        $this->controller   = str_replace(['Admin', 'Controller'], '', $className);

        /*
         * Set model full name
         */
        if (! $this->modelFullName) {
            if (str_contains($this->model, '\\')) {
                $this->modelFullName = $this->model;
            } else {
                $this->modelFullName = 'App\Modules\\'.$this->module.'\Models\\'.$this->model;
            }
        }

        /*
         * Set CRUD form template name
         */
        if (! $this->formTemplate) {
            if ($this->module === str_plural($this->model)) {
                $this->formTemplate = 'form';
            } else {
                $this->formTemplate = strtolower($this->controller).'_form'; // If modelname and modulename differ, the form name should be e. g. "users_form"
            }
            if (starts_with(strtolower($className), 'admin')) $this->formTemplate = 'admin_'.$this->formTemplate;
        }

        /*
         * Enable auto CSRF protection
         */ 
        $this->beforeFilter('csrf', array('on' => ['post', 'put', 'delete']));
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	/**
	 * Shortcut for $this->layout->nest(): Adds a view to the main layout.
	 *
	 * @param string $template Name of the template
	 * @param array  $data     Array with data passed to the compile engine
     * @param bool   $replace  Replace the output already  * 
	 * @return void
	 */
	protected function pageView($template = '', $data = array(), $replace = false)
	{
		if ($this->layout != NULL) {
            if ($replace or $this->layout->page == NULL) {
                $this->layout->page = View::make($template, $data);
            } else {
                $this->layout->page .= View::make($template, $data)->render();
            }
		} else {
			throw new Exception('Error: $this->layout is NULL!');
		}
	}

	/**
	 * Shortcut for $this->layout->nest(): Adds a string to the main layout.
	 *
	 * @param string $output  HTML or text to output on the template.
     * @param bool   $replace Replace the output already 
	 * @return void
	 */
	protected function pageOutput($output, $replace = false)
	{
		if ($this->layout != NULL) {
			if ($replace) {
                $this->layout->page = $output;
            } else {
                $this->layout->page .= $output;
            }
		} else {
			throw new Exception('Error: $this->layout is NULL!');
		}
	}

	/**
	 * Adds a message view to the main layout.
	 *
	 * @param string $title
	 * @param string $text
	 * @return void
	 */
	protected function message($title, $text = '')
	{
		if ($this->layout != NULL) {
			$this->layout->page = View::make('message', array('title' => $title, 'text' => $text));
		} else {
			throw new Exception('Error: $this->layout is NULL!');
		}
	}

	/**
	 * Inserts a flash message to the main layout.
	 *
	 * @param string $title
	 * @return void
	 */
	protected function messageFlash($title)
	{
		Session::flash('_message', $title);
	}

    /**
     * Builds an index form (page) from a model and $data
     * 
     * @param  array $data Array with information how to build the form. See $defaults for details.
     * @param  string $surface Frontend ("front") or backend ("admin")?
     * @return void
     */
    protected function buildIndexForm($data, $surface = 'admin')
    {
        if (! $this->checkAccessRead()) return;
        
        /*
         * Set default values
         */
        $defaults = array(
            'buttons'       => ['new'],
            'search'        => '',
            'searchFor'     => 'title',
            'tableHead'     => [],
            'tableRow'      => [],
            'actions'       => ['edit', 'delete'],
            'brightenFirst' => true,
            'sortby'        => 'id',
            'order'         => 'desc',
            );

        $data = array_merge($defaults, $data);

        /*
         * Generate Buttons
         */
        $buttons = '';
        if (is_array($data['buttons'])) {
            foreach ($data['buttons'] as $type) {
                $type = strtolower($type);
                switch ($type) {
                    case 'new':
                        $buttons .= button(trans('app.create'), route($surface.'.'.strtolower($this->controller).'.create'), 'add');
                        break;
                    case 'category':
                        $buttons .= button(trans('app.categories'), route($surface.'.'.strtolower($this->module).'cats.index'), 'folder');
                        break;
                }
            }
        }

        /*
         * Get search string.
         */
        if (Input::old('search')) {
            $data['search'] = Input::old('search');
        }
        if (Input::get('search')) {
            $data['search'] = Input::get('search');
        }

        /*
         * Get sort attributes.
         */
        if (Input::get('sortby')) {
            $sortby = strtolower(Input::get('sortby'));
            if (in_array($sortby, $data['tableHead'])) $data['sortby'] = $sortby;

            $order = strtolower(Input::get('order'));
            if ($order === 'desc' or $order === 'asc') {
                $data['order'] = $order;
            }
        }
        $sortSwitcher = sort_switcher($data['sortby'], $data['order'], $data['search']);

        /*
         * Retrieve model and entity from DB
         */
        $model = $this->modelFullName;
        $perPage = Config::get('app.'.$surface.'ItemsPerPage');
        if ($data['search'] and $data['searchFor']) {
            $entities = $model::orderBy($data['sortby'], $data['order'])
            ->where($data['searchFor'], 'LIKE', '%'.$data['search'].'%')
            ->paginate($perPage);
        } else {
            $entities = $model::orderBy($data['sortby'], $data['order'])
            ->paginate($perPage);   
        }

        $paginator = $entities->appends(['sortby' => $data['sortby'], 'order' => $data['order'], 'search' => $data['search']])->links();

        /*
         * Prepare the table (head and rows)
         */
        $tableHead = array();
        foreach ($data['tableHead'] as $title => $sortby) {
            if ($sortby != NULL) {
                $tableHead[] = HTML::link(URL::current().'?sortby='.$sortby, $title);
            } else {
                $tableHead[] = $title;
            }
        }
        if (sizeof($data['actions']) > 0) {
            $tableHead[] = trans('app.actions');
        }

        $tableRows = array();
        foreach ($entities as $entity) {
            $row = $data['tableRow']($entity);

            if (is_array($data['actions']) and sizeof($data['actions']) > 0) {
                $actionsCode = '';
                foreach ($data['actions'] as $action) {
                    if (is_string($action)) {
                        $action = strtolower($action);
                        switch ($action) {
                            case 'edit':
                                $actionsCode .= image_link('page_edit', 
                                    trans('app.edit'), 
                                    route($surface.'.'.strtolower($this->controller).'.edit', [$entity->id]));
                                break;
                            case 'delete':
                                $actionsCode .= image_link('bin', 
                                    trans('app.delete'), 
                                    route($surface.'.'.strtolower($this->controller).'.destroy', [$entity->id]).'?method=DELETE&_token='.csrf_token(),
                                    false,
                                    ['data-confirm-delete' => true]);
                                break;
                        }
                        $actionsCode .= ' ';
                    }
                }
                $row[] = $actionsCode;
            }
            if (is_callable($data['actions'])) {
                $row[] = $data['actions']($entity);
            }

            $tableRows[] = $row;
        }

        /*
         * Generate the table
         */
        $contentTable = $this->contentTable($tableHead, $tableRows, $data['brightenFirst']);

        /*
         * Generate the view
         */
        $this->pageView('backend.index_form', array(
            'buttons'       => $buttons,
            'contentTable'  => $contentTable,
            'sortSwitcher'  => $sortSwitcher,
            'paginator'     => $paginator,
            'searchString'  => $data['search']
            ));
    }

    /**
     * Returns HTML code for a table.
     * 
     * @param array     $header          Array with the table header items (String-Array)
     * @param array     $rows            Array with all the table rows items (Array containing String-Arrays)
     * @param bool      $highlightFirst  Enable special look for the items in the first column? (true/false)
     * @return string
     */
    protected function contentTable($header, $rows, $brightenFirst = true)
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
            $code   .= '<tr>';
            $isFirst = true;
            foreach ($row as $value) {
                if ($isFirst and $brightenFirst) {
                    $code   .= '<td style="color: silver">';
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

    /**
     * Returns true if the current user has read access to the module.
     * 
     * @return boolean
     */
    public function hasAccessRead() 
    {
        return (user() and user()->hasAccess(strtolower($this->module), 1));
    }

    /**
     * Returns true if the current user has create access to the module.
     * 
     * @return boolean
     */
    public function hasAccessCreate() 
    {
        return (user() and user()->hasAccess(strtolower($this->module), 2));
    }

    /**
     * Returns true if the current user has update access to the module.
     * 
     * @return boolean
     */
    public function hasAccessUpdate() 
    {
        return (user() and user()->hasAccess(strtolower($this->module), 3));
    }

    /**
     * Returns true if the current user has delete access to the module.
     * 
     * @return boolean
     */
    public function hasAccessDelete() 
    {
        return (user() and user()->hasAccess(strtolower($this->module), 4));
    }

    /**
     * Returns true if the current user has read access to the module.
     * If not a message will be set.
     * 
     * @return bool
     */
    public function checkAccessRead()
    {
        if ($this->hasAccessRead()) {
            return true;
        } else {
            $this->message('Access denied.');
            return false;
        }
    }

    /**
     * Returns true if the current user has create access to the module.
     * If not a message will be set.
     * 
     * @return bool
     */
    public function checkAccessCreate()
    {
        if ($this->hasAccessCreate()) {
            return true;
        } else {
            $this->message('Access denied.');
            return false;
        }
    }

    /**
     * Returns true if the current user has update access to the module.
     * If not a message will be set.
     * 
     * @return bool
     */
    public function checkAccessUpdate()
    {
        if ($this->hasAccessUpdate()) {
            return true;
        } else {
            $this->message('Access denied.');
            return false;
        }
    }

    /**
     * Returns true if the current user has delete access to the module.
     * If not a message will be set.
     * 
     * @return bool
     */
    public function checkAccessDelete()
    {
        if ($this->hasAccessDelete()) {
            return true;
        } else {
            $this->message('Access denied.');
            return false;
        }
    }
}