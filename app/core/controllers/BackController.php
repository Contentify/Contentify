<?php namespace Contentify\Controllers;

use Lang, DB, Log, Input, File, View, Redirect, InterImage, Exception;

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

    /**
     * Array with "evil" file extensions
     * @var array
     */
    protected $evilFileExtensions = ['php'];

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
     * CRUD: create model
     */
    public function create()
    {
        if (! $this->checkAccessCreate()) return;

        $this->pageView(
            strtolower($this->module).'::'.$this->formTemplate,
            ['modelClass' => $this->modelClass]
        );
    }

    /**
     * CRUD: store model
     */
    public function store()
    {
        if (! $this->checkAccessCreate()) return;

        $modelClass = $this->modelClass;
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
            return Redirect::route('admin.'.strtolower($this->controller).'.create')
                ->withInput()->withErrors($model->getErrors());
        }

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
                        $imgsize = getimagesize($file->getRealPath()); // Try to gather infos about the image
                        if (! $imgsize[2]) {
                            $error = trans('app.invalid_image');
                        }
                    }

                    if (in_array($extension, $this->evilFileExtensions)) {
                        $error = trans('app.bad_extension', [$extension]);
                    }

                    if ($error !== false) {
                        $model->delete(); // Delete the invalid model
                        return Redirect::route('admin.'.strtolower($this->controller).'.create')
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
                            InterImage::make($filePath.'/'.$fileName)->resize($thumbnail, $thumbnail, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->save($filePath.$thumbnail.'/'.$fileName); 
                        }
                    }
                } else {
                    // TODO Ignore missing files for now
                }
            }
        }

        $this->messageFlash(trans('app.created', [$this->modelName]));
        if (Input::get('_form_apply') !== null) {
            return Redirect::route('admin.'.strtolower($this->controller).'.edit', array($model->id));
        } else {
            return Redirect::route('admin.'.strtolower($this->controller).'.index');
        }
    }

    /**
     * Retrieve values from inputs (usually select elements) that deal with foreign models
     * 
     * @param  string   $modelClass  Full name of the model class
     * @param  Model    $model       Object with this model type (by reference)
     * @return void
     */
    protected function fillRelations($modelClass, &$model)
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
                            $attribute = $name.'_'.$key;

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
                                Log::warning("Form tries to fill guarded pivot table '$relation[table]' for relation '$name'.");
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
     * CRUD: edit model
     * 
     * @param  int The id of the model
     */
    public function edit($id)
    {
        if (! $this->checkAccessUpdate()) return;

        $modelClass = $this->modelClass;
        $model      = $modelClass::findOrFail($id);

        if (! $model->modifiable()) {
            throw new Exception("Error: Model $modelClass is not modifiable.");
        }

        $this->pageView(
            strtolower($this->module).'::'.$this->formTemplate, 
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
        if (! $this->checkAccessUpdate()) return;

        $modelClass = $this->modelClass;
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
            return Redirect::route('admin.'.strtolower($this->controller).'.edit', ['id' => $model->id])
                ->withInput()->withErrors($model->getErrors());
        }

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
                        $imgsize = getimagesize($file->getRealPath()); // Try to gather infos about the image
                        if (! $imgsize[2]) {
                            $error = trans('app.invalid_image');
                        }
                    }

                    if (in_array($extension, $this->evilFileExtensions)) {
                        $error = trans('app.bad_extension', [$extension]);
                    }

                    if ($error !== false) {
                        return Redirect::route('admin.'.strtolower($this->controller).'.edit', ['id' => $model->id])
                                ->withInput()->withErrors([$error]);
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
                            InterImage::make($filePath.'/'.$fileName)->resize($thumbnail, $thumbnail, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->save($filePath.$thumbnail.'/'.$fileName); 
                        }
                    }
                } else {
                    // TODO Ignore missing files for now
                }
            }
        }

        $this->messageFlash(trans('app.updated', [$this->modelName]));
        if (Input::get('_form_apply') !== null) {
            return Redirect::route('admin.'.strtolower($this->controller).'.edit', [$id]);
        } else {
            return Redirect::route('admin.'.strtolower($this->controller).'.index');
        }
    }

    /**
     * CRUD: delete model
     * 
     * @param  int The id of the model
     */
    public function destroy($id)
    {
        if (! $this->checkAccessDelete()) return;

        $modelClass = $this->modelClass;
        $model      = $modelClass::withTrashed()->find($id);

        if (! $model->modifiable()) {
            throw new Exception("Error: Model $modelClass is not modifiable.");
        }

        /*
         * Delete related files even if it's only a soft deletion.
         */
        if (! $model->trashed() and isset($modelClass::$fileHandling) and sizeof($modelClass::$fileHandling) > 0) {
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

        if (! $model->trashed()) {
            $modelClass::destroy($id); // Delete model. If soft deletion is enabled for this model it's a soft deletion
        } else {
            $model->forceDelete(); // Finally delete this model
        }

        $this->messageFlash(trans('app.deleted', [$this->modelName]));
        return Redirect::route('admin.'.strtolower($this->controller).'.index');
    }

    /**
     * CRUD-related: restore model after soft deletion
     * 
     * @param  int The id of the model
     */
    public function restore($id)
    {
        if (! $this->checkAccessDelete()) return;

        $modelClass = $this->modelClass;
        $model      = $modelClass::withTrashed()->find($id);
        $model->restore();

        $this->messageFlash(trans('app.restored', [$this->modelName]));
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