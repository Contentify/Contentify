<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Running with Contentify CMS -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="generator" content="Contentify">
    <meta name="base-url" content="{!! url('/') !!}">
    <meta name="asset-url" content="{!! asset('') !!}">
    <meta name="csrf-token" content="{!! Session::get('_token') !!}">
    <meta name="locale" content="{!! Config::get('app.locale') !!}">
    <meta name="date-format" content="{!! trans('app.date_format') !!}">
    {!! HTML::metaTags($metaTags) !!}
    @if ($openGraph)
        {!! HTML::openGraphTags($openGraph) !!}
    @endif

    @if ($title)
        {!! HTML::title($title) !!}
    @else
        {!! HTML::title(trans_object($controllerName, $moduleName)) !!}
    @endif

    <link rel="icon" type="image/png" href="{!! asset('img/favicon_180.png') !!}"><!-- Opera Speed Dial Icon -->
    <link rel="shortcut icon" type="picture/x-icon" href="{!! asset('favicon.png') !!}">
    <link rel="alternate" type="application/rss+xml" title="RSS News" href="{!! asset('rss/news.xml') !!}">

    {!! HTML::style('vendor/font-awesome/css/font-awesome.min.css') !!}
    {!! HTML::style(HTML::versionedAssetPath('css/frontend.css')) !!}

    {!! HTML::jsTranslations() !!}
    <!--[if lt IE 9]>
        {!! HTML::script('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js') !!}
        {!! HTML::script('https://oss.maxcdn.com/respond/1.4.2/respond.min.js') !!}
    <![endif]-->
    {!! HTML::script('vendor/jquery/jquery-1.12.4.min.js') !!}
    {!! HTML::script('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js') !!}
    {!! HTML::script('vendor/contentify/contentify.js') !!}
    {!! HTML::script('vendor/contentify/frontend.js') !!}    
</head>
<body>
    @if(Config::get('app.theme_christmas'))
        @include('snow')
    @endif
    <header id="header">
        <div class="container">
            <div class="top-bar">
                <a class="header-logo" href="{!! route('home') !!}">
                    {!! HTML::image(asset('img/header_logo.png')) !!}
                </a>
                <div class="right">
                    <nav class="menu">
                        <ul>
                            <li>{!! link_to('/', trans('app.home'), ['class' => 'active']) !!}</li>
                            <li>{!! link_to('teams', trans('app.object_teams')) !!}</li>
                            <li>{!! link_to('matches', trans('app.object_matches')) !!}</li>
                            <li>{!! link_to('partners', trans('app.object_partners')) !!}</li>
                            <li>{!! link_to('videos', trans('app.object_videos')) !!}</li>
                            <li>{!! link_to('forums', trans('app.object_forums')) !!}</li>
                        </ul>
                    </nav>
                    <div class="user">
                        @if (user())
                            @if (user()->image)
                                <span class="image" style="background-image: url({{ asset('img/default/no_user.png') }})"></span>
                            @else
                                <span class="image" style="background-image: url({{ user()->uploadPath().'80/'.user()->image }})"></span>
                            @endif
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{user()->username }} <span class="caret"></span></button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>{!! link_to('users/'.user()->id.'/'.user()->slug, trans('app.profile')) !!}</li>
                                    <li>{!! link_to('users/'.user()->id.'/edit', trans('app.edit_profile')) !!}</li>
                                    <li>
                                        @if (user()->countMessages() > 0)
                                            {!! link_to('messages/inbox', user()->countMessages().' '.trans_object('messages')) !!}
                                        @else
                                            {!! link_to('messages/inbox', trans_object('messages')) !!}
                                        @endif
                                    </li>
                                    <li>{!! link_to('friends/'.user()->id, trans_object('friends')) !!}</li>
                                    @if (user()->hasAccess('backend', PERM_READ))
                                        <li>{!! link_to('admin', trans('auth::backend')) !!}</li>
                                    @endif
                                    <li>{!! link_to('auth/logout', trans('app.logout')) !!}</li>
                                </ul>
                            </div>
                                
                        @else
                            <a class="btn btn-default" href="{{ url('auth/login') }}" title="{{ trans('auth::login') }}">{!! HTML::fontIcon('unlock-alt') !!}</a>

                            <a class="btn btn-default" href="{{ url('auth/registration/create') }}" title="{{ trans('auth::register') }}">{!! HTML::fontIcon('plus') !!}</a>

                            <a class="btn btn-default" href="{{ url('auth/steam') }}" title="STEAM {{ trans('auth::login') }}">{!! HTML::fontIcon('steam') !!}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>

    @widget('Slides::Slides', ['categoryId' => 1])

    <div class="container">
        <div class="row">
            @widget('Partners::Partners')
        </div>
    </div>

    <div class="container">
        <div id="mid-container" class="row">
            <div id="content" class="col-md-8">
                @if (Session::get('_alert'))
                    @include('alert', ['type' => 'info', 'title' => Session::get('_alert')])
                @endif

                <!-- Render JavaScript alerts here -->
                <div class="alert-area"></div>                

                <section class="page page-{!! strtolower($controllerName) !!} {!! $templateClass !!}">
                    @if (isset($page))
                        {!! $page !!}
                    @endif
                </section>
            </div>

            <aside id="sidebar" class="col-md-4">
                <div class="border"> 
                    <h3>
                        {{ trans('app.latest') }} {{ trans('app.object_matches') }}
                        <a href="{{ url('matches') }}" title="{{ trans('app.read_more') }}">{!! HTML::fontIcon('plus-square') !!}</a>
                    </h3>
                    @widget('Matches::Matches')

                    <br>
                    <h3>
                        {{ trans('app.object_gallery') }}
                        <a href="{{ url('galleries') }}" title="{{ trans('app.read_more') }}">{!! HTML::fontIcon('plus-square') !!}</a>
                    </h3>
                    @widget('Galleries::Galleries', ['limit' => 6])

                    <br>
                    <h3>
                        {{ trans('app.object_teams') }}
                        <a href="{{ url('teams') }}" title="{{ trans('app.read_more') }}">{!! HTML::fontIcon('plus-square') !!}</a>
                    </h3>
                    @widget('Teams::Teams')
                </div>
            </aside>
        </div>
    </div>
    <footer id="footer">
        <div class="links container">
            <div class="row">
                <div class="col-md-4">
                    <a class="footer-logo" href="{!! route('home') !!}">
                        {!! HTML::image(asset('img/header_logo.png')) !!}
                    </a>
                </div>

                <div class="col-md-8">
                    <nav class="pull-right">
                        <ul class="list-inline">        
                            <li>{!! link_to('search', trans('app.object_search')) !!}</li>
                            <li>{!! link_to('servers', trans('app.object_servers')) !!}</li>
                            <li>{!! link_to('galleries', trans('app.object_galleries')) !!}</li>
                            <li>{!! link_to('awards', trans('app.object_awards')) !!}</li>
                            <li>{!! link_to('events', trans('app.object_events')) !!}</li>
                            <li>{!! link_to('downloads', trans('app.object_downloads')) !!}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="more container">
            <div class="row">
                <div class="col-md-4">
                    <span class="info">{{ date('Y') }} by <a class="cms" href="{!! route('home') !!}">{!! Config::get('app.title') !!}</a></span>
                </div>

                <div class="col-md-8">
                    <ul class="list-inline">
                        <li><a class="social-link" href="https://www.facebook.com/{{ Config::get('app.facebook') }}" target="_blank" title="Facebook">{!! HTML::fontIcon('facebook') !!}</a></li>
                        <li><a class="social-link" href="https://twitter.com/{{ Config::get('app.twitter') }}" target="_blank" title="Twitter">{!! HTML::fontIcon('twitter') !!}</a></li>
                        <li><a class="social-link" href="https://www.youtube.com/channel/{{ Config::get('app.youtube') }}" target="_blank" title="YouTube">{!! HTML::fontIcon('youtube') !!}</a></li>
                        <li>&nbsp;</li>
                        <li>{!! link_to('contact', trans('app.object_contact')) !!}</li>
                        <li>{!! link_to('impressum', 'Impressum') !!}</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    
    {!! Config::get('app.analytics') !!}
</body>
</html>