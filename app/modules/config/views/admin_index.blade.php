{{ button(trans('config::button_info'), url('admin/config/info'), asset('icons/information.png')) }}&nbsp;
{{ button(trans('config::button_optimize'), url('admin/config/optimize'), asset('icons/database_save.png')) }}&nbsp;
{{ button(trans('config::button_dump'), url('admin/config/export'), asset('icons/database_save.png')) }}&nbsp;
{{ button(trans('config::button_log'), url('admin/config/log'), asset('icons/page_white_text.png')) }}&nbsp;

{{ Form::errors($errors) }}

{{ Form::model($settingsBag, ['route' => 'admin.config.update', 'method' => 'PUT']) }}
    {{ Form::smartTextarea('app::analytics', trans('config::analytics'), false) }} 
       
    {{ Form::smartCheckbox('auth::registration', trans('config::registration')) }} 

    {{ Form::actions(['submit' => trans('app.update')], false) }}
{{ Form::close() }}