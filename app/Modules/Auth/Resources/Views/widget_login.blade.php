<div class="widget widget-auth-login">
    <a class="btn btn-default" href="{{ url('auth/login') }}">{!! HTML::fontIcon('unlock-alt') !!} {{ trans('auth::login') }}</a>

    <a class="btn btn-default" href="{{ url('auth/registration/create') }}">{{ trans('auth::register') }}</a>

    @if (Config::get('steam-auth.api_key'))
        <a class="btn btn-default" href="{{ url('auth/steam') }}" title="STEAM {{ trans('auth::login') }}">{!! HTML::fontIcon('steam') !!}</a>
    @endif
</div>