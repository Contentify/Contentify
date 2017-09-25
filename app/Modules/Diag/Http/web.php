<?php

ModuleRoute::context('Diag');

ModuleRoute::get('admin/diag', 'AdminDiagController@getIndex');