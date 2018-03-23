<div class="actions">
    {!! button(trans('app.object_diag'), url('admin/diag'), 'heartbeat') !!}&nbsp;
    {!! button(trans('config::button_info'), url('admin/config/info'), 'info-circle') !!}&nbsp;
    {!! button(trans('config::button_optimize'), url('admin/config/optimize'), 'database') !!}&nbsp;
    {!! button(trans('config::button_dump'), url('admin/config/export'), 'database') !!}&nbsp;
    {!! button(trans('config::button_clear_cache'), url('admin/config/clear-cache'), 'trash') !!}&nbsp;
</div>

{!! Form::errors($errors) !!}

{!! Form::model($settingsBag, ['route' => 'admin.config.update', 'method' => 'PUT']) !!}
    {!! Form::smartSelect('app::theme', trans('app.theme'), $themes) !!} 
    {!! Form::smartCheckbox('app::theme_christmas', trans('app.theme_christmas')) !!} 
    {!! Form::smartText('app::theme_snow_color', trans('app.theme_snow_color')) !!} 

    <hr>

    {!! Form::smartText('app::facebook', 'Facebook') !!} 

    {!! Form::smartText('app::twitter', 'Twitter') !!} 

    {!! Form::smartText('app::youtube', 'YouTube') !!} 

    {!! Form::smartText('app::twitchKey', 'Twitch API Key') !!} 

    <hr>
       
    {!! Form::smartCheckbox('auth::registration', trans('config::registration')) !!} 

    {!! Form::smartCheckbox('app::https', 'HTTPS') !!} 

    {!! Form::smartCheckbox('app::dbBackup', trans('config::db_backup')) !!} 

    <hr>   

    {!! Form::smartTextarea('app::analytics', trans('config::analytics'), false) !!} 

    {!! Form::actions(['submit' => trans('app.update')], false) !!}
{!! Form::close() !!}