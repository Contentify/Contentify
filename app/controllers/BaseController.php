<?php

abstract class BaseController extends Controller {

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
    protected $modelName = '';

    /**
     * The name of the model (with class path)
     * @var string
     */
    protected $modelClass = '';

    /**
     * The name of the form template (for CRUD auto handling)
     * @var string
     */
    protected $formTemplate = '';

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
        if (! $this->modelClass) {
            if (str_contains($this->modelName, '\\')) {
                $this->modelClass = $this->modelName;
            } else {
                $this->modelClass = 'App\Modules\\'.$this->module.'\Models\\'.$this->modelName;
            }
        }

        /*
         * Set CRUD form template name
         */
        if (! $this->formTemplate) {
            if ($this->module === str_plural($this->modelName)) {
                $this->formTemplate = 'form';
            } else {
                // If modelname & modulename differ, the form name should be e. g. "users_form":
                $this->formTemplate = strtolower($this->controller).'_form'; 
            }
            if (starts_with(strtolower($className), 'admin')) $this->formTemplate = 'admin_'.$this->formTemplate;
        }

        /*
         * Enable auto CSRF protection
         */ 
        $this->beforeFilter('csrf', ['on' => ['post', 'put', 'delete']]);
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
            $this->layout                   = View::make($this->layout);
            $this->layout->page             = null;
            $this->layout->metaTags         = [];
            $this->layout->openGraph        = null;
            $this->layout->title            = null;
            $this->layout->breadcrumb       = [];
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
        if ($this->layout != null) {
            if ($replace or $this->layout->page == null) {
                $this->layout->page = View::make($template, $data);
            } else {
                $this->layout->page .= View::make($template, $data)->render();
            }
        } else {
            throw new Exception('Error: Controller layout is null!');
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
        if ($this->layout != null) {
            if ($replace) {
                $this->layout->page = $output;
            } else {
                $this->layout->page .= $output;
            }
        } else {
            throw new Exception('Error: $this->layout is null!');
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
        if ($this->layout != null) {
            $this->layout->page .= View::make('message', ['title' => $title, 'text' => $text]);
        } else {
            throw new Exception('Error: $this->layout is null!');
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
     * Adds a meta tag to the variables of the main layout.
     * Use HTML::metaTags() to render them.
     *
     * @param string $template Name of the meta tag
     * @param string $content  Content of the meta tag
     * @return void
     */
    protected function metaTag($name, $content)
    {
        if ($this->layout != null) {
            $this->layout->metaTags[$name] = $content;
        } else {
            throw new Exception('Error: Controller layout is null!');
        }
    }

    /**
     * Sets the title tag for this layout. It's passed as a variable to the template.
     * Use HTML::title() to render it.
     *
     * @param string $title The title
     * @return void
     */
    protected function title($title)
    {
        if ($this->layout != null) {
            $this->layout->title = $title;
        } else {
            throw new Exception('Error: Controller layout is null!');
        }
    }

    /**
     * Binds an OpenGraph instance to this layout. The instance is passed as a variable to the template.
     * Use HTML::openGraphTags() to render the tags.
     *
     * @param OpenGraph $openGraph OpenGraph instance
     * @return void
     */
    protected function openGraph(OpenGraph $openGraph)
    {
        if ($this->layout != null) {
            $this->layout->openGraph = $openGraph;
        } else {
            throw new Exception('Error: Controller layout is null!');
        }
    }

    /**
     * Sets the links for the breadcrumb navigation.
     * Use the Navi::Breadcrump widget to render the breadcrum navi.
     *
     * @param array $links Array with items of title (key) and URLs (link)
     * @return void
     */
    protected function breadcrumb($links = array())
    {
        if ($this->layout != null) {
            $this->layout->breadcrumb = $links;
        } else {
            throw new Exception('Error: Controller layout is null!');
        }
    }

    /**
     * Generates an index page from a model and $data
     * 
     * @param  array  $data             Array with information how to build the form. See $defaults for details.
     * @param  string $userInterface    Frontend ("front") or backend ("admin")?
     * @return void
     */
    protected function indexPage($data, $userInterface = 'admin')
    {
        /*
         * Access checking is only available for the backend.
         * Frontend controllers have to perform it on their own.
         */
        if ($userInterface == 'admin') {
            if (! $this->checkAccessRead()) return;
        }
        
        /*
         * Set default values
         */
        $defaults = [
            'buttons'       => ['new'],                         // Array of names of buttons or custom HTML codes
            'search'        => '',                              // String: search term
            'searchFor'     => 'title',                         // Name of model attribute
            'tableHead'     => [],                              // Array of items for the table head part
            'tableRow'      => [],                              // Array of items for the table body part
            'actions'       => ['edit', 'delete', 'restore'],   // Array of named actions or Closures
            'brightenFirst' => true,                            // True / false
            'sortby'        => 'id',                            // Model attribute name. You can not use MySQL functions
            'order'         => 'desc',                          // Asc / desc
            'filter'        => false,                           // Bool: Apply filters? (Calls the model's scopeFilter method)
            'dataSource'    => null                             // Null (means: take from database) or Closure
        ];

        $data = array_merge($defaults, $data);

        $modelClass = $this->modelClass;

        /*
         * Generate Buttons
         */
        $buttons = '';
        if (is_array($data['buttons'])) {
            foreach ($data['buttons'] as $button) {
                $type = strtolower($button);
                switch ($type) {
                    case 'new':
                        $url = route($userInterface.'.'.strtolower($this->controller).'.create');
                        $buttons .= button(trans('app.create'), $url, 'add');
                        break;
                    case 'category':
                        $url = route($userInterface.'.'.strtolower($this->module).'cats.index');
                        $buttons .= button(trans('app.categories'), $url, 'folder');
                        break;
                    default:
                        $buttons = $button;
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
        if (! $data['dataSource']) {
            if (Input::get('sortby')) {
                $sortby = strtolower(Input::get('sortby'));
                if (in_array($sortby, $data['tableHead'])) $data['sortby'] = $sortby; 

                $order = strtolower(Input::get('order'));
                if ($order === 'desc' or $order === 'asc') {
                    $data['order'] = $order;
                }
            }
            $sortSwitcher = sort_switcher($data['sortby'], $data['order'], $data['search']);
        } else {
            $sortSwitcher = null;
        }

        /*
         * Switch recycle bin mode: Show soft deleted models if recycle bin mode is enabled.
         */
        if ($userInterface == 'admin' and (new $modelClass)->isSoftDeleting()) { // Create an model because isSoftDeleting() is tied to an instance
            $recycleBinMode = Input::get('binmode');
            if ($recycleBinMode !== null) {
                Session::put('recycleBinMode', (bool) $recycleBinMode);
            }
            $recycleBin = recycle_bin_button();
        } else {
            $recycleBin = '';
        }

        /*
         * Retrieve models from DB (or array) and create paginator
         */
        $perPage = Config::get('app.'.$userInterface.'ItemsPerPage');

        if ($data['dataSource']) {
            $models     = $data['dataSource'];
            $paginator  = null;
        } else {
            $models = $modelClass::orderBy($data['sortby'], $data['order']);
            if ($userInterface == 'admin' and Session::get('recycleBinMode')) {
                $models = $models->withTrashed(); // Show trashed
            }
            if ($data['filter'] === true) {
                $models = $models->filter();
            } elseif (is_callable($data['filter'])) {
                $models = $data['filter']($models);
            }
            if ($data['search'] and $data['searchFor']) {
                $models = $models->where($data['searchFor'], 'LIKE', '%'.$data['search'].'%'); // Search for string
            }

            $models = $models->paginate($perPage);

            $paginator = $models->appends([
                'sortby'    => $data['sortby'], 
                'order'     => $data['order'], 
                'search'    => $data['search']
            ])->links();
        }

        /*
         * Prepare the table head
         */
        $tableHead = array();
        foreach ($data['tableHead'] as $title => $sortby) {
            if ($sortby != null) {
                $tableHead[] = HTML::link(URL::current().'?sortby='.urlencode($sortby), $title);
            } else {
                $tableHead[] = $title;
            }
        }
        if (sizeof($data['actions']) > 0) {
            $tableHead[] = trans('app.actions');
        }

        /*
         * Prepare the rows
         */
        $tableRows = array();
        foreach ($models as $model) {
            $row = $data['tableRow']($model);

            if (is_array($data['actions']) and sizeof($data['actions']) > 0) {
                $actionsCode = '';
                foreach ($data['actions'] as $action) {
                    if (is_string($action)) {
                        $action = strtolower($action);
                        switch ($action) {
                            case 'edit':
                                if ($model->modifiable()) {
                                    $actionsCode .= image_link('page_edit', 
                                        trans('app.edit'), 
                                        route($userInterface.'.'.strtolower($this->controller).'.edit', [$model->id]));
                                }
                                break;
                            case 'delete':
                                $urlParams = '?method=DELETE&_token='.csrf_token();
                                if ($model->modifiable()) {
                                    $actionsCode .= image_link('bin', 
                                        trans('app.delete'), 
                                        route(
                                            $userInterface.'.'.strtolower($this->controller).'.destroy', 
                                            [$model->id]
                                        ).$urlParams,
                                        false,
                                        ['data-confirm-delete' => true]);
                                }
                                break;
                            case 'restore':
                                if ($model->trashed()) {
                                    $actionsCode .= image_link('undo', 
                                    trans('app.restore'), 
                                    route($userInterface.'.'.strtolower($this->controller).'.restore', [$model->id]));
                                }
                                break;
                        }
                        $actionsCode .= ' ';
                    }
                    if (is_callable($action)) {
                        $actionsCode .= $action($model);
                    }
                }
                $row[] = $actionsCode;
            }

            if (is_callable($data['actions'])) {
                $row[] = $data['actions']($model);
            }

            $tableRows[] = $row;
        }

        /*
         * Generate the table
         */
        $contentTable = HTML::table($tableHead, $tableRows, $data['brightenFirst']);
        
        /*
         * Generate the view
         */
        $this->pageView('backend.index_page', [
            'buttons'       => $buttons,
            'contentTable'  => $contentTable,
            'sortSwitcher'  => $sortSwitcher,
            'recycleBin'    => $recycleBin,
            'paginator'     => $paginator,
            'searchString'  => $data['search'],
            'showSearchBox' => $data['searchFor'] and (! $data['dataSource']) ? true : false
        ]);
    }

    /**
     * Returns true if the current user has read access to the module.
     * 
     * @return boolean
     */
    public function hasAccessRead() 
    {
        return (user() and user()->hasAccess(strtolower($this->module), PERM_READ));
    }

    /**
     * Returns true if the current user has create access to the module.
     * 
     * @return boolean
     */
    public function hasAccessCreate() 
    {
        return (user() and user()->hasAccess(strtolower($this->module), PERM_CREATE));
    }

    /**
     * Returns true if the current user has update access to the module.
     * 
     * @return boolean
     */
    public function hasAccessUpdate() 
    {
        return (user() and user()->hasAccess(strtolower($this->module), PERM_UPDATE));
    }

    /**
     * Returns true if the current user has delete access to the module.
     * 
     * @return boolean
     */
    public function hasAccessDelete() 
    {
        return (user() and user()->hasAccess(strtolower($this->module), PERM_DELETE));
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
            $this->message(trans('app.access_denied'));
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
            $this->message(trans('app.access_denied'));
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
            $this->message(trans('app.access_denied'));
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
            $this->message(trans('app.access_denied'));
            return false;
        }
    }

    /**
     * Returns true if the current user is authenticated.
     * If not a message will be set.
     * 
     * @return bool
     */
    public function checkAuth()
    {
        if (Sentry::check()) {
            return true;
        } else {
            $this->message(trans('app.no_auth'));
            return false;
        }
    }
}