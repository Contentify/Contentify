<?php namespace Contentify\Controllers;

use Request, Input, Validator, Redirect, DB;

abstract class ConfigController extends BackController {

    protected $icon = 'cog';

    /*
     * Edit the config settings
     * $id is not used
     */
    public function edit($id = null)
    {
        if (! $this->checkAccessRead()) return;

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
            $setting = (array) $setting;
            $name = substr($setting['name'], $pos);
            $settings[$name] = $setting['value'];
        }

        $this->pageView($namespace.'admin_config_form', $settings);
    }

    /*
     * Update the config settings
     * $id is not used
     */
    public function update($id = null)
    {
        if (! $this->checkAccessRead()) return;
        
        $model = new $this->modelClass();
        $fillable = $model->getFillable();
        $namespace = $model->getNamespace();

        $input = Input::only($fillable);
        $validator = Validator::make($input, $model->getRules());

        if ($validator->fails()) {
             return Redirect::to(Request::url())
                ->withInput()->withErrors($validator->messages());
        }

        DB::transaction(function() use ($input, $namespace)
        {
            foreach ($input as $name => $value) {
                $res = DB::table('config')
                    ->whereName($namespace.$name)
                    ->update(['value' => $value]);
            }
        });

        $this->messageFlash(trans('app.updated', [$this->controller]));
        return Redirect::to(Request::url())->withInput();
    }

}