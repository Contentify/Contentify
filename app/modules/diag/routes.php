<?php

$modulePath = 'App\Modules\Diag\Controllers\\';

Route::get('admin/diag', $modulePath.'DiagController@index');
