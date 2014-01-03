<?php

class BackController extends BaseController {
    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'backend.index';

    /**
     * The file identifier of the controller icon
     */
    protected $icon = 'page_white_text.png';

    /**
     * Constructor call
     */
    public function __construct()
    {
        parent::__construct();

        /*
         * Set CRUD form default values
         */
        if (! $this->modelFullName) {
            if (str_contains($this->model, '\\')) {
                $this->modelFullName = $this->model;
            } else {
                $this->modelFullName = 'App\Modules\\'.$this->module.'\Models\\'.$this->model;
            }
        }
        if (! $this->formTemplate) {
            if ($this->module === str_plural($this->model)) {
                $this->formTemplate = 'form';
            } else {
                $this->formTemplate = strtolower($this->controller).'_form'; // If modelname and modulename differ, the form name should be e. g. "users_form"
            }
        }

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
                $contactMessages = link_to('admin/contact', $count.t(' new messages'));
            } else {
                $contactMessages = 'No new messages.';
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
        $this->pageView(strtolower($this->module).'::'.$this->formTemplate);
    }

    /**
     * CRUD: store entity
     */
    public function store()
    {
        $entity = new $this->modelFullName(Input::all());
        $entity->creator_id = user()->id;
        $entity->updater_id = user()->id;

        $okay = $entity->save();

        if (! $okay) {
            return Redirect::route('admin.'.strtolower($this->controller).'.create')->withInput()->withErrors($entity->validationErrors);
        }

        if (Input::hasFile('image') and array_key_exists('image', $entity->getAttributes())) {
            $file = Input::file('image');

            $imgsize = getimagesize($file->getRealPath()); // Try to gather infos about the image 
            if (! $imgsize[2]) {
                return Redirect::route('admin.'.strtolower($this->controller).'.create')->withInput()->withErrors(['x' => 'Invalid image']);
            }

            $extension      = $file->getClientOriginalExtension();
            $fileName       = $entity->id.'.'.$extension;
            $uploadedFile   = $file->move(public_path().'/uploads/'.strtolower($this->controller), $fileName);
            $entity->image  = $fileName;
            $entity->forceSave();
        }

        $this->messageFlash($this->form['model'].t(' created.'));
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
        $model = $this->modelFullName;
        $entity = $model::findOrFail($id);

        $entity->fill(Input::all());
        $entity->updater_id = user()->id;
        $okay = $entity->save();

        if (! $okay) {
            return Redirect::route('admin.'.strtolower($this->controller).'.create')->withInput()->withErrors($entity->validationErrors);
        }

        if (Input::hasFile('image') and array_key_exists('image', $entity->getAttributes())) {
            $file = Input::file('image');

            $imgsize = getimagesize($file->getRealPath()); // Try to gather infos about the image 
            if (! $imgsize[2]) {
                return Redirect::route('admin.'.strtolower($this->controller).'.create')->withInput()->withErrors(['x' => 'Invalid image']);
            }

            $oldImage = public_path().'/uploads/'.strtolower($this->controller).'/'.$entity->image;
            if (File::isFile($oldImage)) {
                File::delete($oldImage); // We need to delete the old file to ensure we never have something like "123.jpg" and "123.png"
            }

            $extension      = $file->getClientOriginalExtension();
            $fileName       = $entity->id.'.'.$extension;
            $uploadedFile   = $file->move(public_path().'/uploads/'.strtolower($this->controller), $fileName);
            $entity->image  = $fileName;
            $entity->forceSave();
        }    

        $this->messageFlash($this->form['model'].t(' updated.'));
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
        $model = $this->modelFullName;
        $model::destroy($id);

        $this->messageFlash($this->form['model'].t(' deleted.'));
        return Redirect::route('admin.'.strtolower($this->controller).'.index');
    }

    /**
     * Helper method for searching
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search()
    {
        return Redirect::route('admin.'.strtolower($this->controller).'.index')->withInput(Input::only('search'));
    }
}