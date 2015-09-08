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
    <header id="header" onclick="window.location='{!! route('home') !!}'">
        <div class="container">
            <h2>Contentify Testpage</h2>
        </div>
    </header>
    <div class="container">       
        <div id="mid-container" class="row">
            <div id="content" class="col-md-8">
                @widget('Slides::Slides', ['categoryId' => 1])

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
                <h3>{{ trans('app.object_user') }} {{ trans('app.links') }}</h3>
                @widget('Auth::Login')

                <br>
                <h3>{{ trans('app.object_navigation') }}</h3>
                <ul class="list-unstyled">
                    <li>{!! link_to('auth/registration/create', trans('app.object_registration')) !!}</li>
                    <li>{!! link_to('users', trans('app.object_users')) !!}</li>
                    <li>{!! link_to('awards', trans('app.object_awards')) !!}</li>
                    <li>{!! link_to('contact', trans('app.object_contact')) !!}</li>
                    <li>{!! link_to('downloads', trans('app.object_downloads')) !!}</li>
                    <li>{!! link_to('galleries', trans('app.object_galleries')) !!}</li>
                    <li>{!! link_to('partners', trans('app.object_partners')) !!}</li>
                    <li>{!! link_to('teams', trans('app.object_teams')) !!}</li>
                    <li>{!! link_to('matches', trans('app.object_matches')) !!}</li>
                    <li>{!! link_to('streams', trans('app.object_streams')) !!}</li>
                    <li>{!! link_to('videos', trans('app.object_videos')) !!}</li>
                    <li>{!! link_to('forums', trans('app.object_forums')) !!}</li>
                </ul>

                <br>
                <h3>{{ trans('app.featured') }} {{ trans('app.object_match') }}</h3>
                @widget('Matches::FeaturedMatch')

                <br>
                <h3>{{ trans('app.latest') }} {{ trans('app.object_matches') }}</h3>
                @widget('Matches::Matches')

                <br>
                <h3>{{ trans('app.latest') }} Threads</h3>
                @widget('Forums::LatestThreads')

                <br>
                <h3>{{ trans('app.object_partners') }}</h3>
                @widget('Partners::Partners', ['categoryId' => 1])

                <br>
                <h3>{{ trans('app.object_servers') }}</h3>
                @widget('Servers::Servers')

                <br>
                <h3>{{ trans('app.object_advert') }}</h3>
                @widget('Adverts::Advert', ['categoryId' => 1])

                <br>
                <h3>{{ trans('app.object_visitors') }}</h3>
                @widget('Visitors::Visitors')

                <br>
                <h3>{{ trans('app.object_streams') }}</h3>
                @widget('Streams::Streams')
            </aside>
        </div>
    </div>
    
    {!! Config::get('app.analytics') !!}
</body>
</html>