<?php

namespace Contentify;

use BaseController;
use BaseModel;
use Closure;
use DB;
use Eloquent;
use Exception;
use HTML;
use Log;
use Paginator;
use Redirect;
use Request;
use Session;
use Str;
use URL;
use UserActivities;

/**
 * The model handler is a class that takes care of frequently occurring tasks related to models.
 * It's a very important and powerful part of Contentify. It helps to avoid writing a lot of code
 * when dealing with models.
 *
 * @see https://github.com/Contentify/Contentify/wiki/Model-Handler
 */
class ModelHandler
{

    /**
     * Form fields of auto generated relationship fields use this as a prefix for their names
     */
    const RELATION_FIELD_PREFIX = '_relation_';

    /**
     * @var BaseController
     */
    protected $controller;

    /**
     * @var Uploader
     */
    protected $uploader;

    public function __construct()
    {
        $this->uploader = new Uploader();
    }

    /**
     * Setter for $controller
     *
     * NOTE: For an unknown reason we have to specify the full path of the base controller here
     *
     * @param \Contentify\Controllers\BaseController $controller The controller object
     * @return void
     */
    public function controller(\Contentify\Controllers\BaseController $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Generates an index page from a model and $data
     *
     * @param array $data Array with information how to build the form. See $defaults for details.
     * @param string $surface Frontend ("front") or backend ("admin")? Default: "admin"
     * @return void
     * @throws Exception
     */
    public function index(array $data, string $surface = 'admin')
    {
        $controller = $this->getControllerOrFail();

        /*
         * Access checking is only available for the backend.
         * Frontend controllers have to perform it on their own.
         */
        if ($surface == 'admin' and ! $controller->checkAccessRead()) {
            return;
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
            'sortby'        => 'id',                            // Model attribute name. You cannot use MySQL functions
            'order'         => 'desc',                          // Asc / desc
            'filter'        => false,                           // Bool: Apply filters? (Calls model::scopeFilter())
            'permaFilter'   => null,                            // Null / Closure: Add a permanent filter to the query?
            'dataSource'    => null,                            // Null (means: take from database) or array
            'infoText'      => '',                              // String (may include HTML tags) with extra info
            'pageTitle'     => true,                            // False: no, true: auto, string: defines a custom title
            'limit'         => null,                            // Integer: Limit number of models per page. Null = auto
        ];

        $data = array_merge($defaults, $data);

        $modelClass = $controller->getModelClass();

        $controllerRouteName = kebab_case($controller->getControllerName());

        /*
         * Generate Buttons
         */
        $buttons = '';
        if (is_array($data['buttons'])) {
            foreach ($data['buttons'] as $button) {
                $type = strtolower($button);
                switch ($type) {
                    case 'new':
                        $url = route($surface.'.'.$controllerRouteName.'.create');
                        $buttons .= button(trans('app.create'), $url, 'plus-circle');
                        break;
                    case 'category':
                        $url = route(
                            $surface.'.'.Str::singular(strtolower($controller->getModuleName())).'-cats.index'
                        );
                        $buttons .= button(trans('app.categories'), $url, 'folder');
                        break;
                    case 'config':
                        $url = url($surface.'/'.strtolower($controller->getModuleName()).'/config') ;
                        $buttons .= button(trans('app.config'), $url, 'cog');
                        break;
                    default:
                        $buttons .= $button;
                }
            }
        }

        /*
         * Get search string.
         */
        if (Request::old('search')) {
            $data['search'] = Request::old('search');
        }
        if (Request::get('search')) {
            $data['search'] = Request::get('search');
        }

        /*
         * Get sort attributes.
         */
        if (! is_array($data['dataSource'])) {
            if (Request::get('sortby')) {
                $sortBy = strtolower(Request::get('sortby'));

                // ATTENTION: Only allow ordering by this column if it's white listed!
                // Never use user input for column ordering before Laravel 5.8!
                // @see https://murze.be/an-important-security-release-for-laravel-query-builder
                if (in_array($sortBy, $data['tableHead'])) {
                    $data['sortby'] = $sortBy;
                }

                $order = strtolower(Request::get('order'));
                if ($order === 'desc' or $order === 'asc') {
                    $data['order'] = $order;
                }
            }
        }

        /*
         * Switch recycle bin mode: Show soft deleted models if recycle bin mode is enabled.
         */
        if ($surface == 'admin' and (new $modelClass)->isSoftDeleting()) { // isSoftDeleting() is instance-tied
            $recycleBinMode = Request::get('binmode');
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
        $perPage = $data['limit'] ?: Config::get('app.'.$surface.'ItemsPerPage');

        if (is_array($data['dataSource'])) {
            $page = (int) Paginator::resolveCurrentPage();

            if ($page) {
                $page--;
            } else {
                $page = 0; // Ensure $page always starts at 0
            }

            $offset = $page * $perPage;

            $models = array_slice($data['dataSource'], $offset, $perPage); // We have to take the models from the array

            $models = new Paginator($models, sizeof($data['dataSource']), $perPage);
        } else {
            $models = $modelClass::orderBy($data['sortby'], $data['order']);

            if ($surface == 'admin' and Session::get('recycleBinMode') and (new $modelClass)->isSoftDeleting()) {
                $models = $models->withTrashed(); // Show trashed
            }

            if ($data['filter'] === true) {
                $models = $models->filter();
            } elseif ($data['filter'] instanceof Closure) {
                $models = $data['filter']($models);
            }
            if ($data['permaFilter']) {
                $models = $data['permaFilter']($models);
            }

            if ($data['search']) {
                $pos = strpos($data['search'], ':');

                if ($pos === false) {
                    if (is_array($data['searchFor'])) {
                        $models = $models->whereHas($data['searchFor'][0], function($query) use ($data)
                        {
                            $query->where($data['searchFor'][1], 'LIKE', '%'.$data['search'].'%');
                        });
                    } elseif ($data['searchFor']) {
                        $models = $models->where($data['searchFor'], 'LIKE', '%'.$data['search'].'%');
                    }
                } else {
                    $searchFor  = substr($data['search'], 0, $pos);
                    $search     = substr($data['search'], $pos + 1);
                    $models = $models->where($searchFor, 'LIKE', '%'.$search.'%');
                    // TODO: Check if attribute $searchFor exists?
                    // Use fillables and/or tableHead attribute names
                }
            }

            $models = $models->paginate($perPage);
        }

        $paginator = $models->appends([
            'sortby'    => $data['sortby'],
            'order'     => $data['order'],
            'search'    => $data['search']
        ])->render();

        /*
         * Prepare the table head
         */
        $tableHead = [];
        foreach ($data['tableHead'] as $title => $sortBy) {
            if ($sortBy != null) {
                $tableHead[] = HTML::sortSwitcher(URL::current(), $title, $sortBy, $data['order'], $data['search']);
            } else {
                $tableHead[] = $title;
            }
        }
        if (is_array($data['actions']) and sizeof($data['actions']) > 0) {
            $tableHead[] = trans('app.actions');
        }

        /*
         * Prepare the rows
         */
        $tableRows = [];
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
                                    $actionsCode .= icon_link(
                                        'edit',
                                        trans('app.edit'),
                                        route($surface.'.'.$controllerRouteName.'.edit', [$model->id]),
                                        false,
                                        ['data-color' => 'blue']
                                    );
                                }
                                break;
                            case 'delete':
                                $urlParams = '?method=DELETE&_token='.csrf_token();
                                if ($model->modifiable()) {
                                    $actionsCode .= icon_link(
                                        'trash',
                                        trans('app.delete'),
                                        route($surface.'.'.$controllerRouteName.'.destroy', [$model->id])
                                            .$urlParams,
                                        false,
                                        ['data-confirm-delete' => true, 'data-color' => 'red']
                                    );
                                }
                                break;
                            case 'restore':
                                if ($model->isSoftDeleting() and $model->trashed()) {
                                    $actionsCode .= icon_link(
                                        'undo',
                                        trans('app.restore'),
                                        route($surface.'.'.$controllerRouteName.'.restore', [$model->id]),
                                        false,
                                        ['data-color' => 'yellow']
                                    );
                                }
                                break;
                        }
                        $actionsCode .= ' ';
                    }
                    if ($action instanceof Closure) {
                        $actionsCode .= $action($model);
                    }
                }
                $row[] = raw($actionsCode);
            }

            if ($data['actions'] instanceof Closure) {
                $row[] = $data['actions']($model);
            }

            $tableRows[] = $row;
        }

        /*
         * Generate the table
         */
        $modelTable = HTML::table(
            $tableHead,
            $tableRows,
            ['data-model-table' => 1, 'data-brighten-first' => (int) $data['brightenFirst']]
        );

        /*
         * Generate the view
         */
        $pageTitle = $data['pageTitle'] === true ? trans_object($controller->getModuleName()) : $data['pageTitle'];
        $controller->pageView('model_index', [
            'buttons'       => $buttons,
            'infoText'      => $data['infoText'],
            'modelTable'    => $modelTable,
            'recycleBin'    => $recycleBin,
            'paginator'     => $paginator,
            'searchString'  => $data['search'],
            'showSearchBox' => $data['searchFor'] and (! $data['dataSource']) ? true : false,
            'pageTitle'     => $pageTitle,
        ]);
    }

    /**
     * Shows a model
     *
     * @param int $id The ID of the model
     * @return void
     * @throws Exception
     */
    public function show(int $id)
    {
        $controller = $this->getControllerOrFail();

        $modelClass = $controller->getModelClass();

        $model = $modelClass::findOrFail($id);

        $controller->pageView(strtolower($controller->getModuleName()).'::show', compact('model'));
    }

    /**
     * CRUD: create model
     *
     * @return void
     * @throws Exception
     */
    public function create()
    {
        $controller = $this->getControllerOrFail();

        if (! $controller->checkAccessCreate()) {
            return;
        }

        $controller->pageView(
            snake_case($controller->getModuleName()).'::'.$controller->getFormTemplate(),
            ['modelClass' => $controller->getModelClass()]
        );
    }

    /**
     * CRUD: store a model that extends from the BaseModel class
     *
     * @return \Illuminate\Http\RedirectResponse|null
     * @throws Exception
     */
    public function store()
    {
        $controller = $this->getControllerOrFail();

        if (! $controller->checkAccessCreate()) {
            return null;
        }

        $modelClass = $controller->getModelClass();
        /** @var BaseModel $model */
        $model = new $modelClass();
        $model->creator_id = user()->id;
        $model->updater_id = user()->id;
        $model->fill(Request::all());
        $this->fillRelations($modelClass, $model);

        if (isset($model['title']) and $model->slugable()) {
            $model->createSlug();
        }

        $okay = $model->save();

        if (! $okay) {
            return Redirect::route('admin.'.kebab_case($controller->getControllerName()).'.create')
                ->withInput()->withErrors($model->getErrors());
        }

        UserActivities::addCreate(false, user()->id, $controller->getModelClass());

        $errors = $this->uploader->uploadModelFiles($model);
        if (count($errors) > 0) {
            return Redirect::route('admin.'.kebab_case($controller->getControllerName()).'.create')
               ->withInput()->withErrors($errors);
        }

        $controller->alertFlash(trans('app.created', [trans_object(basename($controller->getModelName()))]));
        if (Request::get('_form_apply') !== null) {
            return Redirect::route('admin.'.kebab_case($controller->getControllerName()).'.edit', [$model->id]);
        } else {
            return Redirect::route('admin.'.kebab_case($controller->getControllerName()).'.index');
        }
    }

    /**
     * CRUD: edit a model that extends from the BaseModel class
     *
     * @param int $id The ID of the model
     * @return void
     * @throws Exception
     */
    public function edit(int $id)
    {
        $controller = $this->getControllerOrFail();

        if (! $controller->checkAccessUpdate()) {
            return;
        }

        $modelClass = $controller->getModelClass();
        /** @var BaseModel $model */
        $model      = $modelClass::findOrFail($id);

        if (! $model->modifiable()) {
            throw new Exception("Error: Model $modelClass is not modifiable.");
        }

        $controller->pageView(
            snake_case($controller->getModuleName()).'::'.$controller->getFormTemplate(),
            ['model' => $model, 'modelClass' => $modelClass]
        );
    }

    /**
     * CRUD: update a model that extends from the BaseModel class
     *
     * @param int $id The ID of the model
     * @return \Illuminate\Http\RedirectResponse|null
     * @throws Exception
     */
    public function update(int $id)
    {
        $controller = $controller = $this->getControllerOrFail();

        if (! $controller->checkAccessUpdate()) {
            return null;
        }

        $modelClass = $controller->getModelClass();
        /** @var BaseModel $model */
        $model      = $modelClass::findOrFail($id);

        if (! $model->modifiable()) {
            throw new Exception("Error: Model $modelClass is not modifiable.");
        }

        $model->updater_id = user()->id;
        $model->fill(Request::all());
        $this->fillRelations($modelClass, $model);

        if (isset($model['title']) and $model->slugable()) {
            $model->createSlug();
        }

        $okay = $model->save();

        if (! $okay) {
            return Redirect::route('admin.'.kebab_case($controller->getControllerName()).'.edit', ['id' => $model->id])
                ->withInput()->withErrors($model->getErrors());
        }

        UserActivities::addUpdate(false, user()->id, $controller->getModelClass());

        $errors = $this->uploader->uploadModelFiles($model);
        if (count($errors) > 0) {
            return Redirect::route(
                'admin.'.kebab_case($controller->getControllerName()).'.edit',
                ['id' => $model->id]
            )->withInput()->withErrors($errors);
        }

        $controller->alertFlash(trans('app.updated', [trans_object(basename($controller->getModelName()))]));
        if (Request::get('_form_apply') !== null) {
            return Redirect::route('admin.'.kebab_case($controller->getControllerName()).'.edit', [$id]);
        } else {
            return Redirect::route('admin.'.kebab_case($controller->getControllerName()).'.index');
        }
    }

    /**
     * CRUD: delete a model that extends from the BaseModel class
     *
     * @param int $id The ID of the model
     * @return \Illuminate\Http\RedirectResponse|null
     * @throws Exception
     */
    public function destroy(int $id)
    {
        $controller = $this->getControllerOrFail();

        if (! $controller->checkAccessDelete()) {
            return null;
        }

        $modelClass = $controller->getModelClass();

        /** @var BaseModel $model */
        if (method_exists($modelClass, 'trashed')) {
            $model  = $modelClass::withTrashed()->find($id);
        } else {
            $model  = $modelClass::find($id);
        }

        if (! $model->modifiable()) {
            throw new Exception("Error: Model $modelClass is not modifiable.");
        }

        if (method_exists($modelClass, 'relations')) {
            $relations = $modelClass::relations();
            foreach ($relations as $name => $relation) {
                if (array_key_exists('dependency', $relation) and $relation['dependency']) {
                    $dependencies = $model->$name;
                    if (sizeof($dependencies) > 0) {
                        $controller->alertFlash(trans('app.delete_error', [sizeof($dependencies), $name]));
                        return Redirect::route('admin.'.kebab_case($controller->getControllerName()).'.index');
                    }
                }
            }
        }

        // Delete related files even if it's only a soft deletion.
        $this->uploader->deleteModelFiles($model);

        if (! method_exists($modelClass, 'trashed') or ! $model->trashed()) {
            $modelClass::destroy($id); // Delete model. If soft deletion is enabled for this model it's a soft deletion
        } else {
            $model->forceDelete(); // Finally delete this model
        }

        UserActivities::addDelete(false, user()->id, $controller->getModelClass());

        $controller->alertFlash(trans('app.deleted', [trans_object(basename($controller->getModelName()))]));
        return Redirect::route('admin.'.kebab_case($controller->getControllerName()).'.index');
    }

    /**
     * CRUD-related: restore a model that extends from the BaseModel class after soft deletion
     *
     * @param int $id The ID of the model
     * @return \Illuminate\Http\RedirectResponse|null
     * @throws Exception
     */
    public function restore(int $id)
    {
        $controller = $this->getControllerOrFail();

        if (! $controller->checkAccessDelete()) {
            return null;
        }

        $modelClass = $controller->getModelClass();

        /** @var BaseModel $model */
        $model  = $modelClass::withTrashed()->find($id);

        $model->restore();

        $controller->alertFlash(trans('app.restored', [trans_object(basename($controller->getModelName()))]));
        return Redirect::route('admin.'.kebab_case($controller->getControllerName()).'.index');
    }

    /**
     * Helper action method for searching. All we do here is to redirect with the input.
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function search()
    {
        $controller = $this->getControllerOrFail();

        return Redirect::route('admin.'.kebab_case($controller->getControllerName()).'.index')
            ->withInput(Request::only('search'));
    }

    /**
     * Retrieve values from inputs (usually select elements) that deal with foreign models
     *
     * TODO: Introducte interface with relations() method and require it as type
     * of the $model parameter
     *
     * @param  string    $modelClass Full name of the model class
     * @param  Eloquent  $model      Object with this model type
     * @return void
     * @throws Exception
     */
    public function fillRelations(string $modelClass, Eloquent $model)
    {
        $relations = $modelClass::relations();

        foreach (Request::all() as $name => $value) {
            if (starts_with($name, self::RELATION_FIELD_PREFIX)) {
                $name = substr($name, strlen(self::RELATION_FIELD_PREFIX)); // Remove the prefix to get the relation name

                if ($value === '') {
                    /*
                     * Set $value to null instead of an empty string. This will prevent Eloquent from
                     * changing it to (int) 0.
                     */
                    $value = null;
                }

                if (isset($relations[$name])) {
                    $relation = $relations[$name];

                    $foreignModelFull   = $relation[1]; // Fully classified foreign model name
                    $foreignModel       = class_basename($foreignModelFull);
                    $key                = (new $foreignModelFull)->getKeyName(); // Primary key of the model
                    if (isset($relation['foreignKey'])) {
                        $key = $relation['foreignKey'];
                    }

                    /*
                     * Handle the different types of relations
                     */
                    switch ($relation[0]) {
                        case 'belongsTo':
                            $attribute = snake_case($name).'_'.$key;

                            if ($model->isFillable($attribute)) {
                                $model->$attribute = $value;
                            } else {
                                Log::warning("Form tries to fill guarded attribute '$attribute'.");
                            }
                            break;
                        case 'belongsToMany':
                            if ($model->id === null) {
                                throw new Exception(
                                    "Error: Relation '$name' tries to retrieve the model ID, but the ID is null."
                                );
                            }

                            $sourceKey = class_basename(strtolower($modelClass)).'_'.$model->getKeyName();
                            DB::table($relation['table'])->where($sourceKey, '=', $model->id)->delete();

                            $insertion = [];
                            foreach ($value as $id) {
                                if ($id) {
                                    $insertion[] = [
                                        $sourceKey => $model->id,
                                        strtolower($foreignModel).'_'.$key => $id
                                    ];
                                }
                            }

                            if ($model->isFillable('relation_'.$name)) {
                                if (sizeof($insertion) > 0) {
                                    DB::table($relation['table'])->insert($insertion);
                                }
                            } else {
                                Log::warning("Form tries to fill guarded pivot table '$relation[table]'".
                                    " for relation '$name'.");
                            }

                            break;
                        default:
                            throw new Exception(
                                "Error: Unsupported relation type '{$relation[0]}' for model of type '{$modelClass}'."
                            );
                    }
                } else {
                    Log::warning("Unknown relation '{$name}'."); // Just log it, don't throw an exception.
                }
            }
        }
    }

    /**
     * Returns the controller object or throws an exception if it's null
     *
     * NOTE: For an unknown reason we have to specify the full path of the base controller here
     *
     * @return \Contentify\Controllers\BaseController The controller object
     * @throws Exception
     */
    protected function getControllerOrFail() : \Contentify\Controllers\BaseController
    {
        if (! $this->controller) {
            throw new Exception('Model handler: No controller instance has been set.');
        }

        return $this->controller;
    }
}
