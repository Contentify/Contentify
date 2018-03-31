<?php

namespace App\Modules\CashFlows\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Lang;
use View;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::register('App\Modules\CashFlows\Providers\RouteServiceProvider');

        View::addNamespace('cash_flows', realpath(__DIR__ . '/../Resources/Views'));
    }

}