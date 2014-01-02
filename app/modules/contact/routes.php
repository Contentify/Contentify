<?php

ModuleRoute::context(__DIR__);

ModuleRoute::controller('contact', 'ContactController');
ModuleRoute::resource('admin/contact', 'AdminContactController');