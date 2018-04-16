<?php

ModuleRoute::context('Languages');

ModuleRoute::get('languages/{code}', 'LanguagesController@set');