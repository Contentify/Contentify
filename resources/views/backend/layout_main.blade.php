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
    <meta name="date-format" content="{{ trans('app.date_format_alt') }}">
    {{ HTML::metaTags($metaTags) }}

    @if ($title)
        {{ HTML::title($title) }}
    @else
        {{ HTML::title($controllerName) }}
    @endif

    <link rel="shortcut icon" type="picture/x-icon" href="{{ asset('theme/favicon.png') }}">

    {{ HTML::style('vendor/font-awesome/css/font-awesome.min.css') }}
    {{ HTML::style('css/backend.css') }}
    {{ HTML::style('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.css') }}
    {{ HTML::style('vendor/bootstrap-tagsinput/bootstrap-tagsinput.css') }}
    
    <!--[if lt IE 9]>
        {{ HTML::script('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js') }}
        {{ HTML::script('https://oss.maxcdn.com/respond/1.4.2/respond.min.js') }}
    <![endif]-->
    {{ HTML::script('vendor/jquery/jquery-1.11.2.min.js') }}
    {{ HTML::script('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js') }}
    {{ HTML::script('vendor/moment/moment.js') }}
    {{ HTML::script('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.js') }}
    {{ HTML::script('vendor/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}
    {{ HTML::script('vendor/ckeditor/ckeditor.js') }}
    {{ HTML::script('vendor/contentify/contentify.js') }}
    {{ HTML::script('vendor/contentify/backend.js') }}
</head>
<body>
    <div id="page-container">
        <noscript>
            {{ HTML::fontIcon('exclamation-circle') }} {{ trans('app.no_js') }}
        </noscript>

        <header id="header">
            <div class="row">
                <a class="header-logo" href="{{ route('admin.dashboard') }}" title="{{ trans('app.admin_dashboard') }}">
                    {{ HTML::image(asset('theme/header_logo.png')) }}
                </a>
                <div class="header-navigation">
                    @if ($contactMessages)
                    <span class="msg">{{ HTML::fontIcon('envelope') }} {{ $contactMessages }}</span>
                    @endif

                    <nav>
                        <ul class="list-inline">
                            <li><a href="http://github.com/Contentify/Contentify/issues" title="Help">{{ HTML::fontIcon('question-circle') }} <span class="text">Help</span></a></li>
                            <li><a href="{{ route('home') }}" title="Website">{{ HTML::fontIcon('eye') }} <span class="text">Website</span></a></li>
                            <li><a href="{{ route('logout') }}" title="Logout">{{ HTML::fontIcon('sign-out') }} <span class="text">Logout</span></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <aside id="sidebar">
            <a class="hamburger" href="#">{{ HTML::fontIcon('navicon') }}</a>
            <div class="account">
                <a id="profile-link" href="{{ url('users/'.user()->id) }}">
                    <div class="avatar">
                        @if (user()->image)
                            <div class="image" style="background-image: url('{{ asset('uploads/users/80/'.user()->image) }}')"></div>
                        @endif
                        <div class="welcome">Welcome, <span>{{ user()->username }}</span></div>
                    </div>
                </a>                
            </div>
            
            {{ HTML::renderBackendNav() }}
        </aside>

        <section id="content" class="clearfix">
            @if (Session::get('_alert'))
                @include('alert', ['type' => 'info', 'title' => Session::get('_alert')])
            @endif

            {{-- Render JavaScript alerts here --}}
            <div class="alert-area"></div>

            @if (isset($page))
                <a class="page-head" href="{{ url('admin/'.strtolower($controllerName)) }}">
                        {{ HTML::fontIcon($controllerIcon) }}{{ $controllerName }}
                </a>

                <div class="page page-{{ strtolower($controllerName) }} {{ $templateClass }}">
                    {{ $page }}
                </div>
            @endif
        </section>

        <footer id="footer">
            <span class="version">Version {{ Config::get('app.version') }}</span>
            <a class="top" href="#">{{ trans('app.top') }}</a>
        </footer>
    </div>
</body>
</html>