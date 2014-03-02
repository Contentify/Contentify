<?php namespace App\Modules\Users\Controllers;

use Redirect, Input, User, FrontController;

class UsersController extends FrontController {

    public function __construct()
    {
        $this->model = '\User';

        parent::__construct();
    }

    public function index()
    {
        $this->buildIndexPage([
            'buttons'   => null,
            'tableHead' => [
                t('ID')             => 'id', 
                t('Username')       => 'username',
                t('Name')           => 'first_name',
                t('Registration')   => 'created_at',
                t('Last Login')     => 'last_login',
            ],
            'tableRow' => function($user)
            {
                return [
                    $user->id,
                    link_to('users/'.$user->id.'/'.slug($user->username), $user->username),
                    $user->first_name.' '.$user->last_name,
                    $user->created_at->toDateString(),
                    $user->last_login->toDateString()
                ];            
            },
            'actions'   => null,
            'searchFor' => 'username'
        ], 'front');
    }

    public function show($id)
    {
        $user = User::whereId($id)->whereActivated(true)->first();

        $user->access_counter++;
        $user->save();

        $this->pageView('users::show', compact('user'));
    }

    public function edit($id)
    {
        if ((! user()) or (user()->id != $id and (! $this->checkAccessUpdate()))) return;

        $user = User::findOrFail($id);

        $this->pageView('users::form', compact('user'));
    }

    public function update($id)
    {
        if ((! user()) or (user()->id != $id and (! $this->checkAccessUpdate()))) return;

        $user = User::findOrFail($id);

        $user->fill(Input::all());

        if (! $user->validate()) {
            return Redirect::route('users.edit', [$id])
                ->withInput()->withErrors($user->validatorMessages());
        }

        $user->save();

        /*
         * File (and image) handling
         */
        // if (isset($model::$fileHandling) and sizeof($model::$fileHandling) > 0) {
        //     foreach ($model::$fileHandling as $fieldName => $fieldInfo) {
        //         if (Input::hasFile($fieldName)) {
        //             $file = Input::file($fieldName);

        //             if (strtolower($fieldInfo['type']) == 'image') {
        //                 $imgsize = getimagesize($file->getRealPath()); // Try to gather infos about the image 
        //                 if (! $imgsize[2]) {
        //                     return Redirect::route('admin.'.strtolower($this->controller).'.create')
        //                         ->withInput()->withErrors(['Invalid image']);
        //                 }
        //             }

        //             $oldFile = public_path().'/uploads/'.strtolower($this->controller).'/'.$entity->$fieldName;
        //             if (File::isFile($oldFile)) {
        //                 File::delete($oldFile); // We need to delete the old file or we can have "123.jpg" & "123.png"
        //             }

        //             $extension          = $file->getClientOriginalExtension();
        //             $filePath           = public_path().'/uploads/'.strtolower($this->controller);
        //             $fileName           = $entity->id.'_'.$fieldName.'.'.$extension;
        //             $uploadedFile       = $file->move($filePath, $fileName);
        //             $entity->$fieldName = $fileName;
        //             $entity->forceSave(); // Save entity again, without validation

        //             /*
        //              * Create thumbnails for images
        //              */
        //             if (isset($fieldInfo['thumbnails'])) {
        //                 $thumbnails = $fieldInfo['thumbnails'];

        //                 // Ensure $thumbnails is an array:
        //                 if (! is_array($thumbnails)) $thumbnails = compact('thumbnails');

        //                 foreach ($thumbnails as $thumbnail) {
        //                     InterImage::make($filePath.'/'.$fileName)->resize($thumbnail, $thumbnail, true, false)
        //                         ->save($filePath.'/100/'.$fileName); 
        //                 }
        //             }
        //         } else {
        //             // TODO Ignore missing files for now
        //         }
        //     }
        // }

        $this->messageFlash(trans('app.updated', ['Profile']));
        return Redirect::route('users.edit', [$id]);
    }

    public function globalSearch($subject)
    {
        $usersCollection = User::where('username', 'LIKE', '%'.$subject.'%')->get();

        $results = array();
        foreach ($usersCollection as $users) {
            $results[$users->username] = URL::to('users/'.$user->id.'/show');
        }

        return $results;
    }
}