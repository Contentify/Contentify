{{ button('PHP-Info', URL::to('admin/config/info'), asset('icons/information.png')) }}&nbsp;
{{ button('Optimize Database', URL::to('admin/config/optimize'), asset('icons/database_save.png')) }}&nbsp;
{{ button('Export Database', URL::to('admin/config/export'), asset('icons/database_save.png')) }}&nbsp;
{{ button('Log-File', URL::to('admin/config/log'), asset('icons/page_white_text.png')) }}&nbsp;

{{ Form::errors($errors) }}

{{ Form::model($settingsBag, ['route' => 'admin.config.update', 'method' => 'PUT']) }}
    {{ Form::smartText('app_analytics', 'Analytics Code') }}    

    {{ Form::actions(['submit' => 'Update'], false) }}
{{ Form::close() }}