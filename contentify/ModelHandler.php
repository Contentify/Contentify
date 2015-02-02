<?php namespace Contentify;

use BaseController, UserActivities, Input, File, Redirect, InterImage, Exception;

class ModelHandler {

    protected $controller;

    public function controller($controller)
    {
        $this->controller = $controller;
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
        $controller->fillRelations($modelClass, $model);

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

                    if (in_array($extension, $this->evilFileExtensions)) {
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

        $controller->messageFlash(trans('app.created', [$controller->getModelName()]));
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
        $controller->fillRelations($modelClass, $model);

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

                    if (in_array($extension, $this->evilFileExtensions)) {
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

        $controller->messageFlash(trans('app.updated', [$controller->getModelName()]));
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

        $controller->messageFlash(trans('app.deleted', [$controller->getModelName()]));
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

        $controller->messageFlash(trans('app.restored', [$controller->getModelName()]));
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