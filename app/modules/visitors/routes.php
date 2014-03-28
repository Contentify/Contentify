<?php

ModuleRoute::context(__DIR__);

ModuleRoute::get('admin/visitors', 'AdminVisitorsController@index');
