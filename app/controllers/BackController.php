<?php

abstract class BackController extends BaseController {
    /**
     * The layout that should be used for responses.
     * @var string
     */
    protected $layout = 'backend.layout_main';

    /**
     * The file identifier of the controller icon
     * @var string
     */
    protected $icon = 'page_white_text.png';

    public function __construct()
    {
        parent::__construct();

        $self = $this;
        View::composer('backend.layout_main', function($view) use ($self)
        { 
            /*
             * User profile picture
             */ 
            if (user()->image) {
                $userImage = asset('uploads/users/thumbnails/'.user()->image);
            } else {
                $userImage = asset('theme/user.png');
            }
            $view->with('userImage', $userImage);

            /*
             * Contact messages
             */
            $contactMessages = null;
            if (user()->hasAccess('contact', PERM_READ)) {
                $count = DB::table('contact_messages')->where('new', true)->count();
                if ($count > 0) {
                    $contactMessages = link_to('admin/contact', Lang::choice('app.new_messages', $count));
                } else {
                    $contactMessages = trans('app.no_messages');
                }
            }
            $view->with('contactMessages', $contactMessages);

            $view->with('module', $this->module);
            $view->with('controller', $this->controller);
            $view->with('controllerIcon', $this->icon);
        });
    }

    /**
     * CRUD: create entity
     */
    public function create()
    {
        if (! $this->checkAccessCreate()) return;

        $this->pageView(
            strtolower($this->module).'::'.$this->formTemplate,
            ['modelName' => $this->modelFullName]
        );
    }

    /**
     * CRUD: store entity
     */
    public function store()
    {
        if (! $this->checkAccessCreate()) return;

        $model = $this->modelFullName;

        $entity = new $model();
        $entity->creator_id = user()->id;
        $entity->updater_id = user()->id;
        $entity->fill(Input::all());
        $this->fillRelations($model, $entity);

        $okay = $entity->save();

        if (! $okay) {
            return Redirect::route('admin.'.strtolower($this->controller).'.create')
                ->withInput()->withErrors($entity->validationErrors);
        }

        /*
         * File (and image) handling
         */
        if (isset($model::$fileHandling) and sizeof($model::$fileHandling) > 0) {
            foreach ($model::$fileHandling as $fieldName => $fieldInfo) {
                if (Input::hasFile($fieldName)) {
                    $file = Input::file($fieldName);

                    if (strtolower($fieldInfo['type']) == 'image') {
                        $imgsize = getimagesize($file->getRealPath()); // Try to gather infos about the image 
                        if (! $imgsize[2]) {
                            return Redirect::route('admin.'.strtolower($this->controller).'.create')
                                ->withInput()->withErrors(['Invalid image']);
                        }
                    }

                    $extension          = $file->getClientOriginalExtension();
                    $filePath           = public_path().'/uploads/'.strtolower($this->controller);
                    $fileName           = $entity->id.'_'.$fieldName.'.'.$extension;
                    $uploadedFile       = $file->move($filePath, $fileName);
                    $entity->$fieldName = $fileName;
                    $entity->forceSave(); // Save entity again, without validation

                    /*
                     * Create thumbnails for images
                     */
                    if (isset($fieldInfo['thumbnails'])) {
                        $thumbnails = $fieldInfo['thumbnails'];
                        
                        // Ensure $thumbnails is an array:
                        if (! is_array($thumbnails)) $thumbnails = compact('thumbnails'); 

                        foreach ($thumbnails as $thumbnail) {
                            InterImage::make($filePath.'/'.$fileName)->resize($thumbnail, $thumbnail, true, false)
                                ->save($filePath.'/100/'.$fileName); 
                        }
                    }
                } else {
                    // TODO Ignore missing files for now
                }
            }
        }

        $this->messageFlash(trans('app.created', [$this->model]));
        if (Input::get('_form_apply')) {
            return Redirect::route('admin.'.strtolower($this->controller).'.edit', array($entity->id));
        } else {
            return Redirect::route('admin.'.strtolower($this->controller).'.index');
        }
    }

    /**
     * Retrieve values from inputs (usually select elements) that deal with foreign entities
     * 
     * @param  string   $model  Name of the model
     * @param  Model    $entity Object with this model type (by reference)
     * @return void
     */
    protected function fillRelations($model, &$entity)
    {
        $relations = $model::relations();

        foreach (Input::all() as $name => $value) {
            if (starts_with($name, '_relation_')) {
                $name = substr($name, 10); // Remove the prefix to get the name of the relation
                
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
                            $attribute = $name.'_'.$key;

                            if ($entity->isFillable($attribute)) {
                                $entity->$attribute = $value;
                            }

                            break;
                        case 'belongsToMany':
                            $sourceKey = class_basename(strtolower($model)).'_'.$entity->getKeyName();
                            DB::table($relation['table'])->where($sourceKey, '=', $entity->id)->delete();

                            $insertion = [];
                            foreach ($value as $id) {
                                if ($id) {
                                    $insertion[] = [
                                        $sourceKey => $entity->id,
                                        strtolower($foreignModel).'_'.$key => $id
                                    ];
                                }
                            }

                            if (sizeof($insertion) > 0) {
                                DB::table($relation['table'])->insert($insertion);
                            }

                            break;
                        default:
                            throw new Exception(
                                "Error: Unkown relation type '{$relation[0]}' for entity of type '{$model}'."
                            );
                    }
                } else {
                    Log::warn("Unknown relation '{$name}'."); // Just log it, don't throw an exception.
                }
            }
        }

    }

    /**
     * CRUD: edit entity
     * 
     * @param  int The id of the entitity
     */
    public function edit($id)
    {
        if (! $this->checkAccessUpdate()) return;

        $model = $this->modelFullName;
        $entity = $model::findOrFail($id);

        $this->pageView(
            strtolower($this->module).'::'.$this->formTemplate, 
            ['entity' => $entity, 'modelName' => $model]
        );
    }

    /**
     * CRUD: update entity
     * 
     * @param  int The id of the entitity
     */
    public function update($id)
    {
        if (! $this->checkAccessUpdate()) return;

        $model = $this->modelFullName;
        $entity = $model::findOrFail($id);

        $entity->updater_id = user()->id;
        $entity->fill(Input::all());
        $this->fillRelations($model, $entity);
        
        $okay = $entity->save();

        if (! $okay) {
            return Redirect::route('admin.'.strtolower($this->controller).'.edit')
                ->withInput()->withErrors($entity->validationErrors);
        }

        /*
         * File (and image) handling
         */
        if (isset($model::$fileHandling) and sizeof($model::$fileHandling) > 0) {
            foreach ($model::$fileHandling as $fieldName => $fieldInfo) {
                if (Input::hasFile($fieldName)) {
                    $file = Input::file($fieldName);

                    if (strtolower($fieldInfo['type']) == 'image') {
                        $imgsize = getimagesize($file->getRealPath()); // Try to gather infos about the image 
                        if (! $imgsize[2]) {
                            return Redirect::route('admin.'.strtolower($this->controller).'.create')
                                ->withInput()->withErrors(['Invalid image']);
                        }
                    }

                    $oldFile = public_path().'/uploads/'.strtolower($this->controller).'/'.$entity->$fieldName;
                    if (File::isFile($oldFile)) {
                        File::delete($oldFile); // We need to delete the old file or we can have "123.jpg" & "123.png"
                    }

                    $extension          = $file->getClientOriginalExtension();
                    $filePath           = public_path().'/uploads/'.strtolower($this->controller);
                    $fileName           = $entity->id.'_'.$fieldName.'.'.$extension;
                    $uploadedFile       = $file->move($filePath, $fileName);
                    $entity->$fieldName = $fileName;
                    $entity->forceSave(); // Save entity again, without validation

                    /*
                     * Create thumbnails for images
                     */
                    if (isset($fieldInfo['thumbnails'])) {
                        $thumbnails = $fieldInfo['thumbnails'];

                        // Ensure $thumbnails is an array:
                        if (! is_array($thumbnails)) $thumbnails = compact('thumbnails');

                        foreach ($thumbnails as $thumbnail) {
                            InterImage::make($filePath.'/'.$fileName)->resize($thumbnail, $thumbnail, true, false)
                                ->save($filePath.'/100/'.$fileName); 
                        }
                    }
                } else {
                    // TODO Ignore missing files for now
                }
            }
        }

        $this->messageFlash(trans('app.updated', [$this->model]));
        if (Input::get('_form_apply')) {
            return Redirect::route('admin.'.strtolower($this->controller).'.edit', [$id]);
        } else {
            return Redirect::route('admin.'.strtolower($this->controller).'.index');
        }
    }

    /**
     * CRUD: delete entity
     * 
     * @param  int The id of the entitity
     */
    public function destroy($id)
    {
        if (! $this->checkAccessDelete()) return;

        $model  = $this->modelFullName;
        $entity = $model::withTrashed()->find($id);

        /*
         * Delete related files even if it's only a soft deletion.
         */
        if (! $entity->trashed() and isset($model::$fileHandling) and sizeof($model::$fileHandling) > 0) {
            $filePath   = public_path().'/uploads/'.strtolower($this->controller);

            foreach ($model::$fileHandling as $fieldName => $fieldInfo) {
                File::delete($filePath.'/'.$entity->$fieldName);

                /*
                 * Delete image thumbnails
                 */
                if (strtolower($fieldInfo['type']) == 'image' and isset($fieldInfo['thumbnails'])) {
                    $thumbnails = $fieldInfo['thumbnails'];
                    if (! is_array($thumbnails)) $thumbnails = compact('thumbnails'); // Ensure $thumbnails is an array

                    foreach ($thumbnails as $thumbnail) {
                        $fileName = $filePath.'/'.$thumbnail.'/'.$entity->$fieldName;
                        if (File::isFile($fileName)) {
                            File::delete($fileName);
                        }
                    }
                }
            }
        }

        if (! $entity->trashed()) {
            $model::destroy($id); // Delete entity. If soft deletion is enable for this entity it's only a soft deletion
        } else {
            $entity->forceDelete(); // Finally delete this entity
        }

        $this->messageFlash(trans('app.deleted', [$this->model]));
        return Redirect::route('admin.'.strtolower($this->controller).'.index');
    }

    /**
     * CRUD-related: restore entity after soft deletion
     * 
     * @param  int The id of the entitity
     */
    public function restore($id)
    {
        if (! $this->checkAccessDelete()) return;

        $model  = $this->modelFullName;
        $entity = $model::withTrashed()->find($id);
        $entity->restore();

        $this->messageFlash(trans('app.restored', [$this->model]));
        return Redirect::route('admin.'.strtolower($this->controller).'.index');
    }

    /**
     * Helper action method for searching. All we do here is to redirect with the input.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search()
    {
        return Redirect::route('admin.'.strtolower($this->controller).'.index')->withInput(Input::only('search'));
    }
}