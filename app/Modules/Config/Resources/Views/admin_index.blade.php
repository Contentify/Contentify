<div class="actions">
    {!! button(trans('app.object_diag'), url('admin/diag'), 'info') !!}&nbsp;
    {!! button(trans('config::button_info'), url('admin/config/info'), 'info') !!}&nbsp;
    {!! button(trans('config::button_optimize'), url('admin/config/optimize'), 'database') !!}&nbsp;
    {!! button(trans('config::button_dump'), url('admin/config/export'), 'database') !!}&nbsp;
    {!! button(trans('config::button_log'), url('admin/config/log'), 'file-text-o') !!}&nbsp;
</div>

{!! Form::errors($errors) !!}

{!! Form::model($settingsBag, ['route' => 'admin.config.update', 'method' => 'PUT']) !!}
    {!! Form::smartTextarea('app::analytics', trans('config::analytics'), false) !!} 
       
    {!! Form::smartCheckbox('auth::registration', trans('config::registration')) !!} 

    {!! Form::smartCheckbox('app::https', 'HTTPS') !!} 

    {!! Form::smartCheckbox('app::dbBackup', trans('config::db_backup')) !!} 

    {!! Form::smartText('app::twitchKey', 'Twitch API Key') !!}

    {!! Form::actions(['submit' => trans('app.update')], false) !!}
{!! Form::close() !!}