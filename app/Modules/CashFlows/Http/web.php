<?php

ModuleRoute::context('CashFlows');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::get('admin/cash-flows/export', 'AdminCashFlowsController@export');
    ModuleRoute::resource('admin/cash-flows', 'AdminCashFlowsController');
    ModuleRoute::get(
        'admin/cash-flows/{id}/restore',
        ['as' => 'cashflows.restore', 'uses' => 'AdminCashFlowsController@restore']
    );
    ModuleRoute::post('admin/cash-flows/search', 'AdminCashFlowsController@search');
});