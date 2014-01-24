<?php

/*
|--------------------------------------------------------------------------
| Custom Validation Rules
|--------------------------------------------------------------------------
|
| Custom rules created in app/validators.php
|
*/

Validator::extend('alpha_spaces', function($attribute, $value)
{
    return preg_match('/^[\pL\s]+$/u', $value);
});