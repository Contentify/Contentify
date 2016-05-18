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
    {!! HTML::style('css/frontend.css') !!}

    {!! HTML::jsTranslations() !!}
    <!--[if lt IE 9]>
        {!! HTML::script('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js') !!}
        {!! HTML::script('https://oss.maxcdn.com/respond/1.4.2/respond.min.js') !!}
    <![endif]-->
    {!! HTML::script('vendor/jquery/jquery-1.11.3.min.js') !!}
    {!! HTML::script('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js') !!}
    {!! HTML::script('vendor/contentify/contentify.js') !!}
    {!! HTML::script('vendor/contentify/frontend.js') !!}    
</head>
<body>
    <header id="header">
        <div class="container">
            <div class="top-bar">
                <a class="header-logo" href="{!! route('home') !!}">
                    {!! HTML::image(asset('img/header_logo.png')) !!}
                </a>
                <div class="right">
                    @widget('Auth::Login')
                </div>
            </div>
        </div>
        <nav>
            <div class="container">
                <ul>
                    <li class="icon">{!! HTML::fontIcon('bars') !!}</li>
                    <li>{!! link_to('/', trans('app.home'), ['class' => 'active']) !!}</li>
                    <li>{!! link_to('teams', trans('app.object_teams')) !!}</li>
                    <li>{!! link_to('partners', trans('app.object_partners')) !!}</li>
                    <li>{!! link_to('matches', trans('app.object_matches')) !!}</li>
                    <li>{!! link_to('streams', trans('app.object_streams')) !!}</li>
                    <li>{!! link_to('videos', trans('app.object_videos')) !!}</li>
                    <li>{!! link_to('forums', trans('app.object_forums')) !!}</li>
                </ul>
                <div class="right">
                    <a href="https://www.facebook.com/contentifycms" target="_blank">{!! HTML::fontIcon('facebook') !!}</a>
                    <a href="https://twitter.com/ContentifyCMS" target="_blank">{!! HTML::fontIcon('twitter') !!}</a>
                    <a href="https://www.youtube.com/channel/UC2gIIZzySdgxrQ3jM4jmoqQ" target="_blank">{!! HTML::fontIcon('youtube') !!}</a>
                </div>
            </div>
        </nav>
    </header>

    @widget('Slides::Slides', ['categoryId' => 1])

    <div class="divider"></div>
    <div class="container">
        @widget('Navigations::Navigation')
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
                        {{ trans('app.object_partners') }}
                        <a href="{{ url('partners') }}">{!! HTML::fontIcon('plus') !!}</a>
                    </h3>
                    @widget('Partners::Partners')

                    <br>    
                    <h3>
                        {{ trans('app.latest') }} {{ trans('app.object_matches') }}
                        <a href="{{ url('matches') }}" title="{{ trans('app.read_more') }}">{!! HTML::fontIcon('plus') !!}</a>
                    </h3>
                    @widget('Matches::Matches')

                    <br>
                    <h3>
                        {{ trans('app.object_streams') }}
                        <a href="{{ url('streams') }}" title="{{ trans('app.read_more') }}">{!! HTML::fontIcon('plus') !!}</a>
                    </h3>
                    @widget('Streams::Streams')
                </div>
            </aside>
        </div>
    </div>
    <footer id="footer">
        <div class="links">
            <div class="container">
                <nav>
                    <ul class="list-inline">
                        <li class="icon">{!! HTML::fontIcon('bars') !!}</li>
                        <li>{!! link_to('/', trans('app.home'), ['class' => 'active']) !!}</li>
                        
                        <li>{!! link_to('search', trans('app.object_search')) !!}</li>
                        <li>{!! link_to('servers', trans('app.object_servers')) !!}</li>
                        <li>{!! link_to('galleries', trans('app.object_galleries')) !!}</li>
                        <li>{!! link_to('awards', trans('app.object_awards')) !!}</li>
                        <li>{!! link_to('events', trans('app.object_events')) !!}</li>
                        <li>{!! link_to('downloads', trans('app.object_downloads')) !!}</li>
                        <li>{!! link_to('contact', trans('app.object_contact')) !!}</li>
                        <li>{!! link_to('impressum', 'Impressum') !!}</li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="more">
            <div class="container">
                <span class="info">&copy; {{ date('Y') }} by <a class="cms" href="http://www.contentify.org" target="_blank">Contentify.org</a></span>

                <span class="visitors-label">{{ trans('app.object_visitors') }}:&nbsp;&nbsp;</span>
                @widget('Visitors::Visitors')

                <div class="right">
                    <a href="https://www.facebook.com/{{ Config::get('app.facebook') }}" target="_blank" title="Facebook">{!! HTML::fontIcon('facebook') !!}</a>
                    <a href="https://twitter.com/{{ Config::get('app.twitter') }}" target="_blank" title="Twitter">{!! HTML::fontIcon('twitter') !!}</a>
                    <a href="https://www.youtube.com/channel/{{ Config::get('app.youtube') }}" target="_blank" title="YouTube">{!! HTML::fontIcon('youtube') !!}</a>
                </div>
            </div>
            </div>
        </div>
    </footer>
    
    {!! Config::get('app.analytics') !!}
</body>
</html>