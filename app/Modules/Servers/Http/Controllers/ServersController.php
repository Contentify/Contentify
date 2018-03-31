<?php 

namespace App\Modules\Servers\Http\Controllers;

use App\Modules\Servers\Server;
use Config;
use FrontController;

class ServersController extends FrontController
{

    public function __construct()
    {
        $this->modelClass = Server::class;

        parent::__construct();
    }

    public function index()
    {
        $perPage = Config::get('app.frontItemsPerPage');

        $servers = Server::published()->paginate($perPage);

        $this->pageView('servers::index', compact('servers'));
    }

}