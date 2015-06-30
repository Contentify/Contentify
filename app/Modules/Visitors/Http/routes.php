<?php

ModuleRoute::context('Visitors');

ModuleRoute::get('admin/visitors', 'AdminVisitorsController@index');
