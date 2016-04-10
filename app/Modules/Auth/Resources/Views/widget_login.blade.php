<div class="widget widget-auth-login">
    <a class="btn btn-default" href="{{ url('auth/login') }}">{!! HTML::fontIcon('lock') !!} {{ trans('auth::login') }}</a>

    <a class="btn btn-default" href="{{ url('auth/registration/create') }}">{{ trans('auth::register') }}</a>
</div>