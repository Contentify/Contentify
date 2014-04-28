<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Running with Contentify CMS -->

    <meta charset="utf-8">
    <meta name="generator" content="Contentify">
    <meta name="csrf-token" content="{{ Session::get('_token') }}">
    <meta name="base-url" content="{{ url('/') }}">
    <meta name="locale" content="{{ Config::get('app.locale') }}">
    <meta name="date-format" content="{{ trans('app.date_format') }}">
    {{ HTML::metaTags($metaTags) }}
    @if ($openGraph)
        {{ HTML::openGraphTags($openGraph) }}
    @endif

    {{ HTML::title($title) }}

    <link rel="shortcut icon" type="picture/x-icon" href="{{ asset('favicon.png') }}">
    <link rel="alternate" type="application/rss+xml" title="RSS News" href="{{ asset('rss/news.xml') }}">

    {{ HTML::style('frontend.css') }}

    <!--[if lt IE 9]>
    {{ HTML::script('http://html5shiv.googlecode.com/svn/trunk/html5.js') }}
    <![endif]-->
    {{ HTML::script('libs/jquery-1.10.2.min.js') }}
    {{ HTML::script('libs/framework.js') }}
    {{ HTML::script('libs/frontend.js') }}
</head>
<body>
    <div id="page-container">
        <header id="header" onclick="window.location='{{ route('home') }}'">
            <h2>Contentify Testpage</h2>
        </header>

        <div id="mid-container">
            <div id="content">
                @if (Session::get('_message'))
                    <div class="cms-message">
                        {{ Session::get('_message') }}
                    </div>
                @endif

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
                <ul class="layout-v">
                    <li>{{ link_to('auth/registration/create', 'Registration') }}</li>
                    <li>{{ link_to('news', 'Newsarchive') }}</li>
                    <li>{{ link_to('contact', 'Contact') }}</li>
                    <li>{{ link_to('users', 'Users') }}</li>
                    <li>{{ link_to('articles', 'Articles') }}</li>
                    <li>{{ link_to('partners', 'Partners') }}</li>
                    <li>{{ link_to('search', 'Search') }}</li>
                    <li>{{ link_to('admin', 'Admin-Backend') }}</li>
                </ul>

                <br>
                <h3>Latest News:</h3>
                @widget('News::News')

                <br>
                <h3>Partners:</h3>
                @widget('Partners::Partners')

                <br>
                <h3>Advert:</h3>
                @widget('Adverts::Advert')
            </aside>

            <div class="clear"></div>
        </div>
    </div>
    {{ Config::get('app.analytics') }}
</body>
</html>