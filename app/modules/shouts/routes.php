<?php

ModuleRoute::context(__DIR__);

ModuleRoute::post('shouts', 'ShoutsController@store');