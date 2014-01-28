<?php

class BackController extends BaseController {
    /**
     * The layout that should be used for responses.
     * @var string
     */
    protected $layout = 'backend.index';

    /**
     * The file identifier of the controller icon
     * @var string
     */
    protected $icon = 'page_white_text.png';

    /**
     * Constructor call
     */
    public function __construct()
    {
        parent::__construct();

        $self = $this;
        View::composer('backend.index', function($view) use ($self)
        { 
            /*
             * User profile picture
             */ 
            if (user()->image) {
                $userImage = asset('uploads/users/thumbnails/'.Sentry::getUser()->image);
            } else {
                $userImage = asset('theme/user.png');
            }
            $view->with('userImage', $userImage);

            /*
             * Contact messages
             */
            $count = DB::table('contact_messages')->where('new', true)->count();
            if ($count > 0) {
                $contactMessages = link_to('admin/contact', Lang::choice('app.new_messages', $count));
            } else {
                $contactMessages = trans('app.no_messages');
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

        $this->pageView(strtolower($this->module).'::'.$this->formTemplate);
    }

    /**
     * CRUD: store entity
     */
    public function store()
    {
        if (! $this->checkAccessCreate()) return;

        $model = $this->modelFullName;
        $entity = new $model(Input::all());
        $entity->creator_id = user()->id;
        $entity->updater_id = user()->id;

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
                     * Create thumbnails
                     */
                    if (isset($fieldInfo['thumbnails'])) {
                        $thumbnails = $fieldInfo['thumbnails'];
                        if (! is_array($thumbnails)) $thumbnails = compact('thumbnails'); // Ensure $thumbnails is an array

                        foreach ($thumbnails as $thumbnail) {
                            InterImage::make($filePath.'/'.$fileName)->resize($thumbnail, 100, true, false)
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
            array('entity' => $entity)
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

        $entity->fill(Input::all());
        $entity->updater_id = user()->id;
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

                    $oldImage = public_path().'/uploads/'.strtolower($this->controller).'/'.$entity->image;
                    if (File::isFile($oldImage)) {
                        File::delete($oldImage); // We need to delete the old file or we can have "123.jpg" and "123.png"
                    }

                    $extension          = $file->getClientOriginalExtension();
                    $filePath           = public_path().'/uploads/'.strtolower($this->controller);
                    $fileName           = $entity->id.'_'.$fieldName.'.'.$extension;
                    $uploadedFile       = $file->move($filePath, $fileName);
                    $entity->$fieldName = $fileName;
                    $entity->forceSave(); // Save entity again, without validation

                    /*
                     * Create thumbnails
                     */
                    if (isset($fieldInfo['thumbnails'])) {
                        $thumbnails = $fieldInfo['thumbnails'];
                        if (! is_array($thumbnails)) $thumbnails = compact('thumbnails'); // Ensure $thumbnails is an array

                        foreach ($thumbnails as $thumbnail) {
                            InterImage::make($filePath.'/'.$fileName)->resize($thumbnail, 100, true, false)
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
            return Redirect::route('admin.'.strtolower($this->controller).'.edit', array($id));
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

        $model = $this->modelFullName;

        if (isset($model::$fileHandling) and sizeof($model::$fileHandling) > 0) {
            $entity = $model::find($id);

            foreach ($model::$fileHandling as $fieldName => $fieldInfo) {
                File::delete(public_path().'/uploads/'.strtolower($this->controller).'/'.$entity->$fieldName);
            }
        }

        $model::destroy($id);

        $this->messageFlash(trans('app.deleted', [$this->model]));
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