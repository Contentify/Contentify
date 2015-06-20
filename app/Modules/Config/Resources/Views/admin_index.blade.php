<div class="actions">
    {!! button(trans('Diagnostics'), url('admin/diag'), 'info') !!}&nbsp;
    {!! button(trans('config::button_info'), url('admin/config/info'), 'info') !!}&nbsp;
    {!! button(trans('config::button_optimize'), url('admin/config/optimize'), 'database') !!}&nbsp;
    {!! button(trans('config::button_dump'), url('admin/config/export'), 'database') !!}&nbsp;
    {!! button(trans('config::button_log'), url('admin/config/log'), 'file-text-o') !!}&nbsp;
</div>

{!! Form::errors($errors) !!}

{!! Form::model($settingsBag, ['route' => 'admin.config.update', 'method' => 'PUT']) !!}
    {!! Form::smartTextarea('app::analytics', trans('config::analytics'), false) !!} 
       
    {!! Form::smartCheckbox('auth::registration', trans('config::registration')) !!} 

    {!! Form::actions(['submit' => trans('app.update')], false) !!}
{!! Form::close() !!}