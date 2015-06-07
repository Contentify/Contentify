<?php namespace App\Modules\Servers\Http\Controllers;

use App\Modules\Servers\Models\Server;
use Config, URL, FrontController;

class ServersController extends FrontController {

    public function __construct()
    {
        $this->modelName = 'Server';

        parent::__construct();
    }

    public function index()
    {
        $perPage = Config::get('app.frontItemsPerPage');

        $servers = Server::paginate($perPage);

        $this->pageView('servers::index', compact('servers'));
    }

}