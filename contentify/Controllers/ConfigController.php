<?php

namespace Contentify\Controllers;

use Config;
use DB;
use Input;
use Redirect;
use Request;
use Validator;

abstract class ConfigController extends BackController
{

    protected $icon = 'cog';

    /**
     * Edit the config settings
     * $id is not used
     *
     * {@inheritdoc}
     */
    public function edit($id = null)
    {
        if (! $this->checkAccessRead()) {
            return;
        }

        $model = new $this->modelClass(); // This is a helper model to store settings
        $fillable = $model->getFillable();
        $namespace = $model->getNamespace();

        array_walk($fillable, function (&$value, $key) use ($namespace)
        {
            $value = $namespace.$value; // Add the namespace
        });

        $rawSettings = DB::table('config')->whereIn('name', $fillable)->get();

        $pos = strlen($namespace);
        $settings = [];
        foreach ($rawSettings as $setting) {
            $setting = (array) $setting; // Convert from stdClass to array
            $name = substr($setting['name'], $pos);
            $settings[$name] = $setting['value'];
        }

        $this->pageView($namespace.'admin_config_form', $settings);
    }

    /**
     * Update the config settings
     * $id is not used
     *
     * {@inheritdoc}
     */
    public function update($id = null)
    {
        if (! $this->checkAccessRead()) {
            return null;
        }
        
        $model = new $this->modelClass();
        $fillable = $model->getFillable();
        $namespace = $model->getNamespace();

        $input = Input::only($fillable);
        $validator = Validator::make($input, $model->getRules());

        if ($validator->fails()) {
             return Redirect::to(Request::url())->withInput()->withErrors($validator->messages());
        }

        DB::transaction(function() use ($input, $namespace)
        {
            foreach ($input as $name => $value) {

                $result = DB::table('config')->whereName($namespace.$name)
                    ->update(['value' => $value, 'updated_at' => DB::raw('NOW()')]);

                if ($result == 0) {
                    DB::table('config')->insert(array(
                        'name'          => $namespace.$name,
                        'value'         => $value, 
                        'updated_at'    => DB::raw('NOW()'))
                    );
                }

                Config::clearCache($namespace.$name);
            }
        });

        $this->alertFlash(trans('app.updated', [$this->controllerName]));
        return Redirect::to(Request::url())->withInput();
    }

}