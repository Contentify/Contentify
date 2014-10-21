<?php namespace App\Modules\Auth\Controllers;

use App\Modules\Auth\Models\AuthConfig;
use Redirect, Validator, Input, DB, View, BackController;

class AdminConfigController extends BackController {

    public function __construct()
    {
        $this->modelName = 'AuthConfig';

        parent::__construct();
    }

    public function edit($id = null)
    {
        if (! $this->checkAccessRead()) return;

        $model = new AuthConfig();
        $fillable = $model->getFillable();
        $namespace = $model->getNamespace();

        array_walk($fillable, function (&$value, $key) use ($namespace)
        {
            $value = $namespace.$value;
        });

        $rawSettings = DB::table('config')->whereIn('name', $fillable)->get();

        $pos = strlen($namespace);
        $settings = [];
        foreach ($rawSettings as $setting) {
            $setting = (array) $setting;
            $name = substr($setting['name'], $pos);
            $settings[$name] = $setting['value'];
        }

        $this->pageView('auth::admin_config_form', $settings);
    }

    public function update($id = null)
    {
        if (! $this->checkAccessRead()) return;
        
        $model = new AuthConfig();
        $fillable = $model->getFillable();
        $namespace = $model->getNamespace();

        $input = Input::only($fillable);
        $validator = Validator::make($input, $model->getRules());

        if ($validator->fails()) {
             return Redirect::to('admin/auth/config')
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
        return Redirect::to('admin/auth/config')->withInput();
    }

}