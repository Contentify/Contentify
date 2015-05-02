<?php namespace Contentify;

use Paginator, Session, HTML, URL, DB, Log, BaseController, UserActivities, Input, File, Redirect, InterImage, Exception;

class ModelHandler {

    protected $controller;

    /**
     * Setter for $controller
     * 
     * @param  BaseController $controller The controller object
     * @return void
     */
    public function controller($controller)
    {
        $this->controller = $controller;
    }

    /**
     * Generates an index page from a model and $data
     * 
     * @param  array  $data             Array with information how to build the form. See $defaults for details.
     * @param  string $userInterface    Frontend ("front") or backend ("admin")?
     * @return void
     */
    public function index($data, $userInterface = 'admin')
    {
        $controller = $this->getControllerOrFail();

        /*
         * Access checking is only available for the backend.
         * Frontend controllers have to perform it on their own.
         */
        if ($userInterface == 'admin') {
            if (! $controller->checkAccessRead()) return;
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
            'filter'        => false,                           // Bool: Apply filters? (Calls model::scopeFilter())
            'permaFilter'   => null,                            // Null / Closure: Add a permament filter to the query?
            'dataSource'    => null,                            // Null (means: take from database) or array
            'infoText'      => ''                               // String (may include HTML tags) with extra infos
        ];

        $data = array_merge($defaults, $data);

        $modelClass = $controller->getModelClass();

        /*
         * Generate Buttons
         */
        $buttons = '';
        if (is_array($data['buttons'])) {
            foreach ($data['buttons'] as $button) {
                $type = strtolower($button);
                switch ($type) {
                    case 'new':
                        $url = route($userInterface.'.'.strtolower($controller->getControllerName()).'.create');
                        $buttons .= button(trans('app.create'), $url, 'plus-circle');
                        break;
                    case 'category':
                        $url = route(
                            $userInterface.'.'.str_singular(strtolower($controller->getModuleName())).'cats.index'
                        );
                        $buttons .= button(trans('app.categories'), $url, 'folder');
                        break;
                    case 'config':
                        $url = url($userInterface.'/'.strtolower($controller->getModuleName()).'/config') ;
                        $buttons .= button(trans('app.config'), $url, 'cog');
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
        if ($userInterface == 'admin' and (new $modelClass)->isSoftDeleting()) { // isSoftDeleting() is instance-tied
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
            $page  = Paginator::getCurrentPage();

            if ($page) { // Ensure $page always starts at 0:
                $page--;
            } else {
                $page = 0;
            }

            $offset = $page * $perPage;

            $models = array_slice($data['dataSource'], $offset, $perPage); // We have to take the models from the array

            $models = Paginator::make($models, sizeof($data['dataSource']), $perPage);
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
                    // Use fillables &/ tableHead attribute names
                }                 
            }

            $models = $models->paginate($perPage);
        }

        $paginator = $models->appends([
            'sortby'    => $data['sortby'], 
            'order'     => $data['order'], 
            'search'    => $data['search']
        ])->links();

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
                                    $actionsCode .= icon_link('edit', 
                                        trans('app.edit'), 
                                        route(
                                            $userInterface.'.'.strtolower($controller->getControllerName()).'.edit', 
                                            [$model->id])
                                        );
                                }
                                break;
                            case 'delete':
                                $urlParams = '?method=DELETE&_token='.csrf_token();
                                if ($model->modifiable()) {
                                    $actionsCode .= icon_link('trash', 
                                        trans('app.delete'), 
                                        route(
                                            $userInterface.'.'.strtolower($controller->getControllerName()).'.destroy', 
                                            [$model->id]
                                        ).$urlParams,
                                        false,
                                        ['data-confirm-delete' => true]);
                                }
                                break;
                            case 'restore':
                                if ($model->isSoftDeleting() and $model->trashed()) {
                                    $actionsCode .= icon_link('undo', 
                                    trans('app.restore'), 
                                    route(
                                        $userInterface.'.'.strtolower($controller->getControllerName()).'.restore', 
                                        [$model->id])
                                    );
                                }
                                break;
                        }
                        $actionsCode .= ' ';
                    }
                    if (is_callable($action)) {
                        $actionsCode .= $action($model);
                    }
                }
                $row[] = raw($actionsCode);
            }

            if (is_callable($data['actions'])) {
                $row[] = $data['actions']($model);
            }

            $tableRows[] = $row;
        }

        /*
         * Generate the table
         */
        $modelTable = HTML::table($tableHead, $tableRows, $data['brightenFirst']);
        
        /*
         * Generate the view
         */
        $controller->pageView('model_index', [
            'buttons'       => $buttons,
            'infoText'      => $data['infoText'],
            'modelTable'  => $modelTable,
            'sortSwitcher'  => $sortSwitcher,
            'recycleBin'    => $recycleBin,
            'paginator'     => $paginator,
            'searchString'  => $data['search'],
            'showSearchBox' => $data['searchFor'] and (! $data['dataSource']) ? true : false
        ]);
    }

    /**
     * Shows a model
     * 
     * @param  int  $id The ID of the model
     * @return void
     */
    public function show($id)
    {
        $controller = $this->getControllerOrFail();

        $modelClass = $controller->getModelClass();

        $model = $modelClass::findOrFail($id);


        $this->pageView(strtolower($controller->getModuleName()).'::show', compact('model'));
    }

    /**
     * CRUD: create model
     */
    public function create()
    {
        $controller = $this->getControllerOrFail();

        if (! $controller->checkAccessCreate()) return;

        $controller->pageView(
            strtolower($controller->getModuleName()).'::'.$controller->getFormTemplate(),
            ['modelClass' => $controller->getModelClass()]
        );
    }

    /**
     * CRUD: store model
     */
    public function store()
    {
        $controller = $this->getControllerOrFail();

        if (! $controller->checkAccessCreate()) return;

        $modelClass = $controller->getModelClass();
        $model = new $modelClass();
        $model->creator_id = user()->id;
        $model->updater_id = user()->id;
        $model->fill(Input::all());
        $this->fillRelations($modelClass, $model);

        if (isset($model['title']) and $model->slugable()) {
            $model->createSlug();
        }
 
        $okay = $model->save();

        if (! $okay) {
            return Redirect::route('admin.'.strtolower($controller->getControllerName()).'.create')
                ->withInput()->withErrors($model->getErrors());
        }

        UserActivities::addCreate(false, user()->id, $controller->getModelClass());

        /*
         * File (and image) handling
         */
        if (isset($modelClass::$fileHandling) and sizeof($modelClass::$fileHandling) > 0) {
            foreach ($modelClass::$fileHandling as $fieldName => $fieldInfo) {
                if (! is_array($fieldInfo)) {
                    $fieldName = $fieldInfo;
                    $fieldInfo = ['type' => 'file'];
                }

                if (Input::hasFile($fieldName)) {
                    $file       = Input::file($fieldName);
                    $extension  = $file->getClientOriginalExtension();
                    $error      = false;

                    if (strtolower($fieldInfo['type']) == 'image') {
                        try {
                            $imgData = getimagesize($file->getRealPath());     
                        } catch (Exception $e) {
                            
                        }

                        if (! isset($imgData[2]) or ! $imgData[2]) {
                            $error = trans('app.invalid_image');
                        }
                    }

                    if (in_array($extension, $controller->getEvilFileExtensions())) {
                        $error = trans('app.bad_extension', [$extension]);
                    }

                    if ($error !== false) {
                        $model->delete(); // Delete the invalid model
                        return Redirect::route('admin.'.strtolower($this->getControllerName()).'.create')
                                ->withInput()->withErrors([$error]);
                    }
                    
                    $filePath           = $model->uploadPath(true);
                    $fileName           = $model->id.'_'.$fieldName.'.'.$extension;
                    $uploadedFile       = $file->move($filePath, $fileName);
                    $model->$fieldName  = $fileName;
                    $model->forceSave(); // Save model again, without validation

                    /*
                     * Create thumbnails for images
                     */
                    if (isset($fieldInfo['thumbnails'])) {
                        $thumbnails = $fieldInfo['thumbnails'];
                        
                        // Ensure $thumbnails is an array:
                        if (! is_array($thumbnails)) $thumbnails = compact('thumbnails'); 

                        foreach ($thumbnails as $thumbnail) {
                            InterImage::make($filePath.'/'.$fileName)
                                ->resize($thumbnail, $thumbnail, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->save($filePath.$thumbnail.'/'.$fileName); 
                        }
                    }
                } else {
                    // TODO Ignore missing files for now
                }
            }
        }

        $controller->alertFlash(trans('app.created', [$controller->getModelName()]));
        if (Input::get('_form_apply') !== null) {
            return Redirect::route('admin.'.strtolower($controller->getControllerName()).'.edit', array($model->id));
        } else {
            return Redirect::route('admin.'.strtolower($controller->getControllerName()).'.index');
        }
    }

    /**
     * CRUD: edit model
     * 
     * @param  int The id of the model
     */
    public function edit($id)
    {
        $controller = $this->getControllerOrFail();

        if (! $controller->checkAccessUpdate()) return;

        $modelClass = $controller->getModelClass();
        $model      = $modelClass::findOrFail($id);

        if (! $model->modifiable()) {
            throw new Exception("Error: Model $modelClass is not modifiable.");
        }

        $controller->pageView(
            strtolower($controller->getModuleName()).'::'.$controller->getFormTemplate(), 
            ['model' => $model, 'modelClass' => $modelClass]
        );
    }

    /**
     * CRUD: update model
     * 
     * @param  int The id of the model
     */
    public function update($id)
    {
        $controller = $controller = $this->getControllerOrFail();

        if (! $controller->checkAccessUpdate()) return;

        $modelClass = $controller->getModelClass();
        $model      = $modelClass::findOrFail($id);

        if (! $model->modifiable()) {
            throw new Exception("Error: Model $modelClass is not modifiable.");
        }

        $model->updater_id = user()->id;
        $model->fill(Input::all());
        $this->fillRelations($modelClass, $model);

        if (isset($model['title']) and $model->slugable()) {
            $model->createSlug();
        }

        $okay = $model->save();

        if (! $okay) {
            return Redirect::route('admin.'.strtolower($controller->getControllerName()).'.edit', ['id' => $model->id])
                ->withInput()->withErrors($model->getErrors());
        }

        UserActivities::addUpdate(false, user()->id, $controller->getModelClass());

        /*
         * File (and image) handling
         */
        if (isset($modelClass::$fileHandling) and sizeof($modelClass::$fileHandling) > 0) {
            foreach ($modelClass::$fileHandling as $fieldName => $fieldInfo) {
                if (! is_array($fieldInfo)) {
                    $fieldName = $fieldInfo;
                    $fieldInfo = ['type' => 'file'];
                }

                if (Input::hasFile($fieldName)) {
                    $file       = Input::file($fieldName);
                    $extension  = $file->getClientOriginalExtension();
                    $error      = false;

                    if (strtolower($fieldInfo['type']) == 'image') {
                        try {
                            $imgData = getimagesize($file->getRealPath());     
                        } catch (Exception $e) {

                        }

                        if (! isset($imgData[2]) or ! $imgData[2]) {
                            $error = trans('app.invalid_image');
                        }
                    }

                    if (in_array($extension, $controller->getEvilFileExtensions())) {
                        $error = trans('app.bad_extension', [$extension]);
                    }

                    if ($error !== false) {
                        return Redirect::route(
                            'admin.'.strtolower($this->getControllerName()).'.edit', 
                            ['id' => $model->id]
                        )->withInput()->withErrors([$error]);
                    }

                    $oldFile = $model->uploadPath(true).$model->$fieldName;
                    if (File::isFile($oldFile)) {
                        File::delete($oldFile); // Delete the old file so we never have "123.jpg" AND "123.png"
                    }

                    $filePath           = $model->uploadPath(true);
                    $fileName           = $model->id.'_'.$fieldName.'.'.$extension;
                    $uploadedFile       = $file->move($filePath, $fileName);
                    $model->$fieldName  = $fileName;
                    $model->forceSave(); // Save model again, without validation

                    /*
                     * Create thumbnails for images
                     */
                    if (isset($fieldInfo['thumbnails'])) {
                        $thumbnails = $fieldInfo['thumbnails'];

                        // Ensure $thumbnails is an array:
                        if (! is_array($thumbnails)) $thumbnails = compact('thumbnails');

                        foreach ($thumbnails as $thumbnail) {
                            InterImage::make($filePath.'/'.$fileName)
                                ->resize($thumbnail, $thumbnail, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->save($filePath.$thumbnail.'/'.$fileName); 
                        }
                    }
                } else {
                    // TODO Ignore missing files for now
                }
            }
        }

        $controller->alertFlash(trans('app.updated', [$controller->getModelName()]));
        if (Input::get('_form_apply') !== null) {
            return Redirect::route('admin.'.strtolower($controller->getControllerName()).'.edit', [$id]);
        } else {
            return Redirect::route('admin.'.strtolower($controller->getControllerName()).'.index');
        }
    }

    /**
     * CRUD: delete model
     * 
     * @param  int The id of the model
     */
    public function destroy($id)
    {
        $controller = $this->getControllerOrFail();

        if (! $controller->checkAccessDelete()) return;

        $modelClass = $controller->getModelClass();

        if (method_exists($modelClass,'withTrashed')) {
            $model  = $modelClass::withTrashed()->find($id);
        } else {
            $model  = $modelClass::find($id);
        }

        if (! $model->modifiable()) {
            throw new Exception("Error: Model $modelClass is not modifiable.");
        }

        /*
         * Delete related files even if it's only a soft deletion.
         */
        if ((! method_exists($modelClass,'withTrashed') or ! $model->trashed()) 
            and isset($modelClass::$fileHandling) and sizeof($modelClass::$fileHandling) > 0) {
            
            $filePath = $model->uploadPath(true);

            foreach ($modelClass::$fileHandling as $fieldName => $fieldInfo) {
                if (! is_array($fieldInfo)) {
                    $fieldName = $fieldInfo;
                    $fieldInfo = ['type' => 'file'];
                }

                File::delete($filePath.$model->$fieldName);

                /*
                 * Delete image thumbnails
                 */
                if (strtolower($fieldInfo['type']) == 'image' and isset($fieldInfo['thumbnails'])) {
                    $thumbnails = $fieldInfo['thumbnails'];
                    if (! is_array($thumbnails)) $thumbnails = compact('thumbnails'); // Ensure $thumbnails is an array

                    foreach ($thumbnails as $thumbnail) {
                        $fileName = $filePath.$thumbnail.'/'.$model->$fieldName;
                        if (File::isFile($fileName)) {
                            File::delete($fileName);
                        }
                    }
                }
            }
        }

        if (! method_exists($modelClass,'withTrashed') or ! $model->trashed()) {
            $modelClass::destroy($id); // Delete model. If soft deletion is enabled for this model it's a soft deletion
        } else {
            $model->forceDelete(); // Finally delete this model
        }

        UserActivities::addDelete(false, user()->id, $controller->getModelClass());

        $controller->alertFlash(trans('app.deleted', [$controller->getModelName()]));
        return Redirect::route('admin.'.strtolower($controller->getControllerName()).'.index');
    }

    /**
     * CRUD-related: restore model after soft deletion
     * 
     * @param  int The id of the model
     */
    public function restore($id)
    {
        $controller = $this->getControllerOrFail();

        if (! $controller->checkAccessDelete()) return;

        $modelClass = $controller->getModelClass();

        if (method_exists($modelClass,'withTrashed')) {
            $model  = $modelClass::withTrashed()->find($id);
        } else {
            $model  = $modelClass::find($id);
        }

        $model->restore();

        $controller->alertFlash(trans('app.restored', [$controller->getModelName()]));
        return Redirect::route('admin.'.strtolower($controller->getControllerName()).'.index');
    }

    /**
     * Helper action method for searching. All we do here is to redirect with the input.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search()
    {
        $controller = $this->getControllerOrFail();

        return Redirect::route('admin.'.strtolower($controller->getControllerName()).'.index')
            ->withInput(Input::only('search'));
    }

    
    /**
     * Retrieve values from inputs (usually select elements) that deal with foreign models
     * 
     * @param  string   $modelClass  Full name of the model class
     * @param  Model    $model       Object with this model type (by reference)
     * @return void
     */
    public function fillRelations($modelClass, &$model)
    {
        $relations = $modelClass::relations();

        foreach (Input::all() as $name => $value) {
            if (starts_with($name, '_relation_')) {
                $name = substr($name, 10); // Remove the prefix to get the name of the relation

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
                    if (isset($relation['foreignKey'])) $key = $relation['foreignKey'];

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
                                Log::warning("Form tries to fill guarded pivot table '$relation[table]' \
                                    for relation '$name'.");
                            }

                            break;
                        default:
                            throw new Exception(
                                "Error: Unkown relation type '{$relation[0]}' for model of type '{$modelClass}'."
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
     * @return object The controller object
     */
    protected function getControllerOrFail()
    {
        if (! $this->controller) {
            throw new Exception('Modelhandler: No controller instance has been set.');
        }

        return $this->controller;
    }
    
}