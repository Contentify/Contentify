<?php namespace App\Modules\Servers\Http\Controllers;

use App\Modules\Servers\Server;
use Request, Config, URL, FrontController;

class ServersController extends FrontController {

    public function __construct()
    {
        $this->modelName = 'Server';

        parent::__construct();
    }

    public function index()
    {
        $perPage = Config::get('app.frontItemsPerPage');

        $servers = Server::paginate($perPage)->setPath(Request::url());

        $this->pageView('servers::index', compact('servers'));
    }

}