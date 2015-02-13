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
    {{ HTML::metaTags($metaTags) }}

    {{ HTML::title($title) }}

    <link rel="shortcut icon" type="picture/x-icon" href="{{ asset('theme/favicon.png') }}">

    {{ HTML::style('css/font-awesome.min.css') }}
    {{ HTML::style('css/backend.css') }}
    {{ HTML::style('libs/datetime/picker.min.css') }}
    {{ HTML::style('libs/tags/bootstrap-tagsinput.css') }}
    
    <!--[if lt IE 9]>
        {{ HTML::script('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js') }}
        {{ HTML::script('https://oss.maxcdn.com/respond/1.4.2/respond.min.js') }}
    <![endif]-->
    {{ HTML::script('libs/jquery-1.10.2.min.js') }}
    {{ HTML::script('libs/datetime/picker.min.js') }}
    {{ HTML::script('libs/tags/bootstrap-tagsinput.js') }}
    {{ HTML::script('libs/ckeditor/ckeditor.js') }}
    {{ HTML::script('libs/contentify.js') }}
    {{ HTML::script('libs/backend.js') }}
</head>
<body>
    <div id="page-container">
        <noscript>
            {{ HTML::fontIcon('exclamation-circle') }} {{ trans('app.no_js') }}
        </noscript>

        <!-- Header Starts -->
        <header id="header">
            <div class="row">
                <div class="col-lg-2 header-logo">
                    <a href="{{ route('admin.dashboard') }}" title="{{ trans('app.admin_dashboard') }}">
                        {{ HTML::image(asset('theme/contentify_header_logo.png')) }}
                    </a>
                </div>
                <div class="col-lg-10 header-navigation">
                    <span class="fa fa-globe fa-fw"></span>Demoversion - Deutsch<span class="fa fa-chevron-down fa-fw"></span>
                    <nav>
                        <ul>
                            <li><a href="{{ url('admin/help') }}"><span class="fa fa-question-circle fa-fw"></span>Help</a></li>
                            <li><a href="{{ route('home') }}"><span class="fa fa-eye fa-fw"></span>Website</a></li>
                            <li><a href="{{ route('logout') }}"><span class="fa fa-sign-out fa-fw"></span>Logout</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
        <!-- Header Ends -->

        <!-- Menu Starts -->
        <aside id="menu">
            <div id="account-menu">
                <a id="profile-link" href="{{ url('users/'.user()->id) }}">
                    <div class="avatar">
                        @if (user()->image)
                            <div class="image" style="background-image: url('{{ asset('uploads/users/100/'.user()->image) }}')"></div>
                        @endif
                        <div class="welcome">Welcome</div>
                        {{ user()->username }}
                    </div>
                </a>                
            </div>
            <div id="navigation">
                <div class="side-menu dashboard active">
                    <a href="{{ route('admin.dashboard') }}"><span class="fa fa-dashboard fa-fw"></span>Dashboard</a>
                </div>
                <div class="side-menu structure">
                    <a href="{{ url('/admin/structure') }}"><span class="fa fa-sitemap fa-fw"></span>Structure</a>
                </div>
                <div class="side-menu modules">
                    <a href="{{ url('/admin/modules') }}"><span class="fa fa-th fa-fw"></span>Modules</a>
                </div>
                <div class="side-menu administration">
                    <a href="{{ url('/admin/administration') }}"><span class="fa fa-cog fa-fw"></span>Administration</a>
                </div>
            </div>
        </aside>
        <!-- Menu Ends -->
        
        <!-- Content Starts -->
        <section id="content">
            @if (Session::get('_message'))
                <div class="ui-message">
                    <div class="text">
                        {{ Session::get('_message') }}
                    </div>
                </div>
            @endif

            {{-- Render JavaScript alerts here --}}
            <div class="alert-area"></div>

            @if (isset($page))
                <div class="row page page-{{ strtolower($controllerName) }} page-{{ pageClass() }}">
                    <div class="col-lg-12">
                        {{ $page }}
                    </div>
                </div>
            @endif
        </section>
        <!-- Content Ends -->        

        <!--
                @if ($contactMessages)
                    {{ HTML::image(asset('icons/email.png'), 'Message') }} {{ $contactMessages }}
                @endif

                Version {{ Config::get('app.version') }}

                { HTML::renderBackendNav() }

                2009-{{ date('Y') }}

                {{ trans('app.top') }}

                <a class="form-head" href="{{ url('admin/'.strtolower($controllerName)) }}">
                                {{ HTML::fontIcon($controllerIcon) }}{{ $controllerName }}
                            </a>

                -->
    </div>
</body>
</html>