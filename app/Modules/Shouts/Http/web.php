<?php

ModuleRoute::context('Shouts');

ModuleRoute::post('shouts', 'ShoutsController@store');