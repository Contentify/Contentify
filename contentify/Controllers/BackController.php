<?php namespace Contentify\Controllers;

use Lang, DB, View;

abstract class BackController extends BaseController {

    /**
     * The layout that should be used for responses.
     * @var string
     */
    protected $layout = 'backend.layout_main';

    /**
     * The name of the controller icon. It's rendered with FontAwesome.
     * @var string
     */
    protected $icon = 'file';

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
                $userImage = asset('uploads/users/60/'.user()->image);
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
    
}