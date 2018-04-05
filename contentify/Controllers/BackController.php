<?php

namespace Contentify\Controllers;

use DB;
use Lang;
use View;

abstract class BackController extends BaseController
{

    /**
     * The layout that should be used for responses.
     *
     * @var string
     */
    protected $layout = 'backend.layout_main';

    /**
     * The name of the controller icon. It's rendered with FontAwesome.
     *
     * @var string
     */
    protected $icon = 'file';

    public function __construct()
    {
        parent::__construct();

        $self = $this;
        View::composer('backend.layout_main', function($view) use ($self)
        { 
            /*
             * Count contact messages and if more than zero create link
             */
            $contactMessages = null;
            if (user()->hasAccess('contact', PERM_READ)) {
                $count = DB::table('contact_messages')->whereNull('deleted_at')->where('new', true)->count();
                
                if ($count > 0) {
                    $contactMessages = link_to('admin/contact', Lang::choice('app.new_messages', $count));
                }
            }

            /** @var \Illuminate\View\View $view */
            $view->with('contactMessages', $contactMessages);
            $view->with('moduleName', $this->moduleName);
            $view->with('controllerName', $this->controllerName);
            $view->with('controllerIcon', $this->icon);
        });
    }
    
}