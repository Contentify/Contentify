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

    {{ HTML::style('css/backend.css') }}
    {{ HTML::style('css/font-awesome.min.css') }}
    {{ HTML::style('libs/formstone/selecter.css') }}
    {{ HTML::style('libs/formstone/boxer.css') }}
    {{ HTML::style('libs/datetime/picker.min.css') }}
    {{ HTML::style('libs/tags/bootstrap-tagsinput.css') }}
    
    <!--[if lt IE 9]>
        {{ HTML::script('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js') }}
        {{ HTML::script('https://oss.maxcdn.com/respond/1.4.2/respond.min.js') }}
    <![endif]-->
    {{ HTML::script('libs/jquery-1.10.2.min.js') }}
    {{ HTML::script('libs/quicktip.js') }}
    <script src="{{ asset('libs/ddaccordion.js') }}">
    //
    // Accordion Content script- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
    // Visit http://www.dynamicDrive.com for hundreds of DHTML scripts
    // This notice must stay intact for legal use
    //
    </script>
    {{ HTML::script('libs/formstone/selecter.js') }}
    {{ HTML::script('libs/formstone/boxer.js') }}
    {{ HTML::script('libs/datetime/picker.min.js') }}
    {{ HTML::script('libs/tags/bootstrap-tagsinput.js') }}
    {{ HTML::script('libs/ckeditor/ckeditor.js') }}
    {{ HTML::script('libs/contentify.js') }}
    {{ HTML::script('libs/backend.js') }}
</head>
<body>
    <div id="page-container">
        <noscript><div id="nojavascript">{{ HTML::fontIcon('exclamation-circle') }} <img src="{{ asset('icons/exclamation.png') }}" width="16" height="16" alt="icon"> {{ trans('app.no_js') }}</div></noscript>
        <header id="header">
            <a id="header-logo" href="{{ route('admin.dashboard') }}" title="{{ trans('app.admin_dashboard') }}"><!-- empty --></a>
            
            <a id="hnav1" class="hnav" href="{{ route('admin.dashboard') }}"><!-- empty --></a>
            <a id="hnav2" class="hnav" href="{{ route('home') }}"><!-- empty --></a>
            <a id="hnav3" class="hnav" href="{{ url('admin/help') }}"><!-- empty --></a>
            <a id="hnav4" class="hnav" href="{{ url('admin/diag') }}"><!-- empty --></a>
            <a id="hnav5" class="hnav" href="{{ route('logout') }}"><!-- empty --></a>
        
            <img id="user-img" src="{{ $userImage }}" alt="User">
            <img id="user-img-overlay" src="{{ asset('theme/backend/photo.png') }}" width="45" height="60" alt="Overlay">
            <a id="profile-link" href="{{ url('users/'.user()->id) }}" title="Show your profile"><!-- empty--></a>
            
            <div id="info-box">
                <span>Welcome, {{ user()->username }}!</span> 
                @if ($contactMessages)
                    {{ HTML::image(asset('icons/email.png'), 'Message') }} {{ $contactMessages }}
                @endif
            </div>
            
            <div id="info-bar"><span id="datetime"></span> now. Version {{ Config::get('app.version') }}</div>
            
            <a id="tecslink" href="{{ url('admin/help/technologies') }}" title="{{ trans('app.tec_infos') }}"><!-- empty --></a>
            
            <a id="quicklink1" class="quicklink" href="{{ url('users/'.Sentry::getUser()->id.'/edit') }}" title="Edit your Profile" style="left: 669px"><!-- empty --></a>
            <a id="quicklink2" class="quicklink" href="{{ url('admin/help') }}" title="Help" style="left: 689px"><!-- empty --></a>
            <a id="quicklink3" class="quicklink" href="{{ url('auth/logout') }}" title="Log out" style="left: 709px"><!-- empty --></a>
            
            <a id="quicklink4" class="quicklink" href="{{ url('admin/news') }}" title="News" style="left: 740px"><!-- empty --></a>
            <a id="quicklink5" class="quicklink" href="{{ url('admin/pages') }}" title="Pages" style="left: 760px"><!-- empty --></a>
            <a id="quicklink6" class="quicklink" href="{{ url('admin/images') }}" title="Images" style="left: 780px"><!-- empty --></a>
            <a id="quicklink7" class="quicklink" href="{{ url('admin/downloads') }}" title="Downloads" style="left: 800px"><!-- empty --></a>
            <a id="quicklink8" class="quicklink" href="{{ url('admin/matches') }}" title="Matches" style="left: 820px"><!-- empty --></a>
            
            <a id="quicklink9" class="quicklink" href="{{ url('admin/users') }}" title="Users" style="left: 851px"><!-- empty --></a>
            <a id="quicklink10" class="quicklink" href="{{ url('admin/members') }}" title="Team-Members" style="left: 871px"><!-- empty --></a>
            <a id="quicklink11" class="quicklink" href="{{ url('admin/config') }}" title="Config" style="left: 891px"><!-- empty --></a>
        </header>
        <section id="content-wrapper" class="clearfix">
            <aside id="sidebar">
                <nav>
                    {{ HTML::renderBackendNav() }}
                </nav>
            </aside>

            <section id="main-content">
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
                        <div class="page page-{{ strtolower($controllerName) }} page-{{ pageClass() }}">
                            <a class="form-head" href="{{ url('admin/'.strtolower($controllerName)) }}">
                                {{ HTML::fontIcon($controllerIcon) }}{{ $controllerName }}
                            </a>

                            {{ $page }}
                        </div>
                    @endif
            </section>
        </section>
        <footer id="footer">
            <a href="#" title="{{ trans('app.top') }}"><!-- empty --></a>
            <span>&copy; 2009-{{ date('Y') }} design and code by contentify.org</span>
        </footer>
    </div>
</body>
</html>