<?php

ModuleRoute::context('Dashboard');


ModuleRoute::get('auth/dashboard', 'AdminDashboardController@getIndex');