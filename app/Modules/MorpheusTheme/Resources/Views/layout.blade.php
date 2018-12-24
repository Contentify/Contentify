<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Running with Contentify CMS -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="generator" content="Contentify">
    <meta name="base-url" content="{{ url('/') }}">
    <meta name="asset-url" content="{{ asset('') }}">
    <meta name="csrf-token" content="{{ Session::get('_token') }}">
    <meta name="locale" content="{{ Config::get('app.locale') }}">
    <meta name="date-format" content="{{ trans('app.date_format') }}">
    <meta name="author" content="{{ Config::get('app.author') }}">
    <meta name="keywords" content="{{ Config::get('app.keywords') }}">
    <meta name="description" content="{{ Config::get('app.description') }}">
    {!! HTML::metaTags($metaTags) !!}
    @if ($openGraph)
        {!! HTML::openGraphTags($openGraph) !!}
    @endif

    @if ($title)
        {!! HTML::title($title) !!}
    @else
        {!! HTML::title(trans_object($controllerName, $moduleName)) !!}
    @endif

    <link rel="icon" type="image/png" href="{!! asset('img/favicon_180.png') !!}">
    <link rel="shortcut icon" type="picture/x-icon" href="{!! asset('favicon.png') !!}">
    <link rel="alternate" type="application/rss+xml" title="RSS News" href="{!! asset('rss/news.xml') !!}">

    {!! HTML::style('vendor/font-awesome/css/all.min.css') !!}
    {!! HTML::style(HTML::versionedAssetPath('css/frontend.css')) !!}

    {!! HTML::jsTranslations() !!}
    {!! HTML::script('vendor/jquery/jquery-2.2.4.min.js') !!}
    {!! HTML::script('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js') !!}
    {!! HTML::script('vendor/contentify/contentify.js') !!}
    {!! HTML::script('vendor/contentify/frontend.js') !!}
    <script>{!! Config::get('app.frontend_js_code') !!}</script>
</head>
<body>
    @if (Config::get('app.theme_christmas'))
        @include('snow')
    @endif
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
                    @include('social_links')
                </div>
            </div>
        </nav>
    </header>

    @widget('Slides::Slides', ['categoryId' => 1])

    <div class="divider"></div>
    <div class="container">
        <div id="mid-container" class="row">
            <div id="content" class="col-md-8">
                @if (Session::get('_alert'))
                    @include('alert', ['type' => 'info', 'title' => Session::get('_alert')])
                @endif

                <!-- Render JavaScript alerts here -->
                <div class="alert-area"></div>

                <section class="page page-{!! kebab_case($controllerName) !!} {!! $templateClass !!}">
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

                    <br>
                    <h3>
                        {{ trans('app.object_cups') }}
                        <a href="{{ url('cups') }}" title="{{ trans('app.read_more') }}">{!! HTML::fontIcon('plus') !!}</a>
                    </h3>
                    @widget('Cups::FeaturedCup')

                    <br>
                    <h3>
                        Cup Control
                        <a href="{{ url('cups') }}" title="{{ trans('app.read_more') }}">{!! HTML::fontIcon('plus') !!}</a>
                    </h3>
                    @widget('Cups::CupsControl')

                    <br>
                    <div class="text-center">
                        @widget('Adverts::Advert')
                    </div>
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
                <span class="info">&copy; {{ date('Y') }} by <a class="home-link" href="{!! route('home') !!}">{!! Config::get('app.name') !!}</a></span>

                <span class="visitors-label">{{ trans('app.object_visitors') }}:&nbsp;&nbsp;</span>
                @widget('Visitors::Visitors')

                <div class="right">
                    @include('social_links')
                </div>
            </div>
        </div>
    </footer>

    @if (Config::get('app.gdpr'))
        <div id="gdpr-alert" class="hidden alert alert-info alert-dismissible">
            <strong>{{ trans('app.gdpr_alert') }} <em>{{ link_to('privacy-policy', trans('app.read_more')) }}</em></strong>
            <a href="#" class="btn btn-default" data-dismiss="alert" aria-label="close">{{ trans('app.confirm') }}</a>
        </div>
    @endif

    {!! Config::get('app.analytics') !!}
</body>
</html>
