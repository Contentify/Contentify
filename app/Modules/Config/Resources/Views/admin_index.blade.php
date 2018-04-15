<div class="actions">
    {!! button(trans('app.object_diag'), url('admin/diag'), 'heartbeat') !!}
    {!! button(trans('config::button_info'), url('admin/config/info'), 'info-circle') !!}
    {!! button(trans('config::button_log'), url('admin/config/log'), 'file-alt') !!}
    {!! button(trans('config::button_optimize'), url('admin/config/optimize'), 'database') !!}
    {!! button(trans('config::button_dump'), url('admin/config/export'), 'database') !!}
    {!! button(trans('config::button_compile_less'), url('admin/config/compile-less'), 'code') !!}
    {!! button(trans('config::button_clear_cache'), url('admin/config/clear-cache'), 'trash') !!}
</div>

{!! Form::errors($errors) !!}

{!! Form::model($settingsBag, ['route' => 'admin.config.update', 'method' => 'PUT']) !!}
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#tab_general" aria-controls="tab_general" role="tab" data-toggle="tab">General</a></li>
        <li role="presentation"><a href="#tab_services" aria-controls="tab_services" role="tab" data-toggle="tab">Services</a></li>
        <li role="presentation"><a href="#tab_theme" aria-controls="tab_theme" role="tab" data-toggle="tab">Theme</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="tab_general">
            {!! Form::smartText('app::name', trans('config::website_name')) !!}

            <hr>

            {!! Form::smartCheckbox('auth::registration', trans('config::registration')) !!}

            {!! Form::smartCheckbox('app::https', 'HTTPS') !!}

            {!! Form::smartCheckbox('app::dbBackup', trans('config::db_backup')) !!}
        </div>
        <div role="tabpanel" class="tab-pane" id="tab_services">
            {!! Form::smartText('app::facebook', 'Facebook') !!}

            {!! Form::smartText('app::twitter', 'Twitter') !!}

            {!! Form::smartText('app::youtube', 'YouTube') !!}

            {!! Form::smartText('app::instagram', 'Instagram') !!}

            {!! Form::smartText('app::twitch', 'Twitch') !!}

            {!! Form::smartText('app::twitchKey', 'Twitch API Key') !!}

            {!! Form::smartText('app::discord', 'Discord') !!}

            <hr>

            {!! Form::smartTextarea('app::analytics', trans('config::analytics'), false) !!}
        </div>
        <div role="tabpanel" class="tab-pane" id="tab_theme">
            {!! Form::smartSelect('app::theme', trans('app.theme'), $themes) !!}

            {!! Form::smartCheckbox('app::theme_christmas', trans('app.theme_christmas')) !!}

            {!! Form::smartText('app::theme_snow_color', trans('app.theme_snow_color')) !!}
        </div>
    </div>

    {!! Form::actions(['submit' => trans('app.update')]) !!}
{!! Form::close() !!}