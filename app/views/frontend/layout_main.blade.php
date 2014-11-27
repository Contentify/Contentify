<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Running with Contentify CMS -->

    <meta charset="utf-8">
    <meta name="generator" content="Contentify">
    <meta name="base-url" content="{{ url('/') }}">
    <meta name="asset-url" content="{{ asset('') }}">
    <meta name="csrf-token" content="{{ Session::get('_token') }}">
    <meta name="locale" content="{{ Config::get('app.locale') }}">
    <meta name="date-format" content="{{ trans('app.date_format') }}">
    {{ HTML::metaTags($metaTags) }}
    @if ($openGraph)
    {{ HTML::openGraphTags($openGraph) }}
    @endif

    {{ HTML::title($title) }}

    <link rel="icon" type="image/png" href="{{ asset('img/logo_180.png') }}" />{{-- Opera Speed Dial Icon --}}
    <link rel="shortcut icon" type="picture/x-icon" href="{{ asset('favicon.png') }}">
    <link rel="alternate" type="application/rss+xml" title="RSS News" href="{{ asset('rss/news.xml') }}">

    {{ HTML::style('frontend.css') }}

    <!--[if lt IE 9]>
    {{ HTML::script('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js') }}
    {{ HTML::script('https://oss.maxcdn.com/respond/1.4.2/respond.min.js') }}
    <![endif]-->
    {{ HTML::script('libs/jquery-1.10.2.min.js') }}
    {{ HTML::script('libs/contentify.js') }}
    {{ HTML::script('libs/frontend.js') }}
</head>
<body>
    <div id="page-container">
        <header id="header" onclick="window.location='{{ route('home') }}'">
            <h2>Contentify Testpage</h2>
        </header>

        <div id="mid-container" class="clearfix">
            <div id="content">
                @widget('Slides::Slides', ['categoryId' => 1])

                @if (Session::get('_message'))
                <div class="ui-message">
                    {{ Session::get('_message') }}
                </div>
                @endif

                {{-- Render JavaScript alerts here --}}
                <div class="alert-area"></div>                

                <section class="page page-{{ strtolower($controller) }} page-{{ pageClass() }}">
                    @if (isset($page))
                    {{ $page }}
                    @endif
                </section>
            </div>

            <aside id="sidebar">
                <h3>User Area:</h3>
                @widget('Auth::Login')

                <br>
                <h3>Navigation:</h3>
                <ul class="list-unstyled">
                    <li>{{ link_to('auth/registration/create', 'Registration') }}</li>
                    <!--<li>{{ link_to('news', 'Newsarchive') }}</li>-->
                    <!--<li>{{ link_to('contact', 'Contact') }}</li>-->
                    <li>{{ link_to('users', 'Users') }}</li>
                    <li>{{ link_to('articles', 'Articles') }}</li>
                    <li>{{ link_to('partners', 'Partners') }}</li>
                    <li>{{ link_to('teams', 'Teams') }}</li>
                    <li>{{ link_to('matches', 'Matches') }}</li>
                    <li>{{ link_to('streams', 'Streams') }}</li>
                    <li>{{ link_to('videos', 'Videos') }}</li>
                    <!--<li>{{ link_to('search', 'Search') }}</li>-->
                </ul>

                <br>
                <h3>Latest News:</h3>
                @widget('News::News')

                <br>
                <h3>Featured Match:</h3>
                @widget('Matches::FeaturedMatch')

                <br>
                <h3>Latest Matches:</h3>
                @widget('Matches::Matches')

                <br>
                <h3>Partners:</h3>
                @widget('Partners::Partners', ['categoryId' => 2])

                <br>
                <h3>Advert:</h3>
                @widget('Adverts::Advert', ['categoryId' => 1])

                <br>
                <h3>Visitors:</h3>
                @widget('Visitors::Visitors')

                                <br>
                <h3>Streams:</h3>
                @widget('Streams::Streams')
            </aside>
        </div>
    </div>
    {{ Config::get('app.analytics') }}
</body>
</html>