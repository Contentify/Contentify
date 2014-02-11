<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Running with Contentify CMS -->

    <meta charset="utf-8">

    <title>Contentify</title>

    <meta name="generator" content="Contentify">

    <link rel="alternate" type="application/rss+xml" title="RSS News" href="{{ asset('rss/news.xml') }}">

    {{ HTML::style('frontend.css') }}

    <!--[if lt IE 9]>
    {{ HTML::script('http://html5shiv.googlecode.com/svn/trunk/html5.js') }}
    <![endif]-->
    {{ HTML::script('libs/jquery-1.10.2.min.js') }}
    {{ HTML::script('libs/frontend.js') }}
</head>
<body data-base-url="{{ Config::get('app.url') }}/public/">
    <div id="page-container">
        <header id="header" onclick="window.location='{{ route('home') }}'">
            <h2>Contentify Testpage</h2>
        </header>

        <div id="mid-container">
            <section id="content">
                @if (Session::get('_message'))
                    <div class="cms-message">
                        {{ Session::get('_message') }}
                    </div>
                @endif

                <section class="page page-{{ strtolower($controller) }}">
                    @if (isset($page))
                        {{ $page }}
                    @endif
                </section>
            </section>

            <aside id="sidebar">
                <h3>Navigation:</h3>
                <ul class="layout-v">
                    <li>{{ link_to('auth/login', 'Login') }}</li>
                    <li>{{ link_to('auth/logout', 'Logout') }}</li>
                    <li>{{ link_to('auth/registration/create', 'Registration') }}</li>
                    <li>{{ link_to('news', 'Newsarchive') }}</li>
                    <li>{{ link_to('contact', 'Contact') }}</li>
                    <li>{{ link_to('search', 'Search') }}</li>
                    <li>{{ link_to('admin', 'Admin-Backend') }}</li>
                </ul>

                <br />
                <h3>Latest News:</h3>
                @widget('News::News')
            </aside>

            <div class="clear"></div>
        </div>
    </div>
</body>
</html>