<?php

ModuleRoute::context('Dashboard');

ModuleRoute::get('admin/dashboard', 'AdminDashboardController@getIndex');