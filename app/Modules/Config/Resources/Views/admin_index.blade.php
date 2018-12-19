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
        <li role="presentation" class="active"><a href="#tab_general" aria-controls="tab_general" role="tab" data-toggle="tab">{{ trans('app.general') }}</a></li>
        <li role="presentation"><a href="#tab_services" aria-controls="tab_services" role="tab" data-toggle="tab">{{ trans('app.services') }}</a></li>
        <li role="presentation"><a href="#tab_code" aria-controls="tab_meta" role="tab" data-toggle="tab">{{ trans('app.code') }}</a></li>
        <li role="presentation"><a href="#tab_theme" aria-controls="tab_theme" role="tab" data-toggle="tab">{{ trans('app.theme') }}</a></li>
        <li role="presentation"><a href="#tab_meta" aria-controls="tab_meta" role="tab" data-toggle="tab">{{ trans('app.metainfo') }}</a></li>
        <li role="presentation"><a href="#tab_gdpr" aria-controls="tab_gdpr" role="tab" data-toggle="tab">{{ trans('app.gdpr') }}</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="tab_general">
            {!! Form::smartText('app::name', trans('config::website_name')) !!}

            <hr>

            {!! Form::smartCheckbox('auth::registration', trans('config::registration')) !!}

            {!! Form::smartCheckbox('app::https', 'HTTPS') !!}

            {!! Form::smartCheckbox('app::dbBackup', trans('config::db_backup')) !!}

            <hr>

            {!! Form::smartTextarea('app::forbidden_email_domains', trans('config::forbidden_email_domains')) !!}

            <hr>

            {!! Form::smartTextarea('app::short_biography', trans('config::short_biography')) !!}
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
        <div role="tabpanel" class="tab-pane" id="tab_code">
            {!! Form::smartTextarea('app::frontend_less_code', trans('config::frontend_less_code'), false) !!}

            {!! Form::smartTextarea('app::frontend_js_code', trans('config::frontend_js_code'), false) !!}

            <hr>

            {!! Form::smartTextarea('app::backend_less_code', trans('config::backend_less_code'), false) !!}

            {!! Form::smartTextarea('app::backend_js_code', trans('config::backend_js_code'), false) !!}
        </div>
        <div role="tabpanel" class="tab-pane" id="tab_theme">
            {!! Form::smartGroupOpen('app::theme', trans('app.theme')) !!}
            @foreach($themes as $themeName => $theme)
                <div class="theme-tile">
                    <label>
                        {!! Form::radio('app::theme', $themeName, $theme['activeTheme']) !!}
                        <div class="preview-img" style="background-image: url({!! $theme['preview'] !!})"></div>
                        <span>{{ $theme['slug'] }}</span>
                    </label>
                </div>
            @endforeach

            {!! Form::smartGroupClose() !!}

            {!! Form::smartCheckbox('app::theme_christmas', trans('app.theme_christmas')) !!}

            {!! Form::smartText('app::theme_snow_color', trans('app.theme_snow_color')) !!}
        </div>
        <div role="tabpanel" class="tab-pane" id="tab_meta">
            {!! Form::smartText('app::author', trans('config::meta_author'), false) !!}
            <p class="help-block  text-right">{!! trans('config::meta_author_info') !!}</p>

            <hr>

            {!! Form::smartText('app::keywords', trans('config::meta_keywords'), false) !!}
            <p class="help-block  text-right">{!! trans('config::meta_keywords_info') !!}</p>

            <hr>

            {!! Form::smartTextarea('app::description', trans('config::meta_description'), false) !!}
            <p class="help-block  text-right">{!! trans('config::meta_description_info') !!}</p>
        </div>
        <div role="tabpanel" class="tab-pane" id="tab_gdpr">
            {!! Form::smartCheckbox('app::gdpr', trans('app.enabled')) !!}

            {!! Form::smartTextarea('app::privacy_policy', trans('app.privacy_policy'), true)  !!}
        </div>
    </div>

    {!! Form::actions(['submit' => trans('app.update')]) !!}
{!! Form::close() !!}
