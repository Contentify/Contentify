<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Running with Contentify CMS -->

    <meta charset="utf-8">
    <meta name="generator" content="Contentify">
    <meta name="base-url" content="{{ Config::get('app.url') }}/public/">
    <meta name="csrf-token" content="{{ Session::get('_token') }}">
    <meta name="locale" content="{{ Config::get('app.locale') }}">
    <meta name="date-format" content="{{ trans('app.date_format') }}">
    {{ HTML::metaTags($metaTags) }}

    {{ HTML::title($title) }}

    <link rel="shortcut icon" type="picture/x-icon" href="{{ asset('theme/favicon.png') }}">

    {{ HTML::style('backend.css') }}
    {{ HTML::style('libs/formstone/selecter.css') }}
    {{ HTML::style('libs/formstone/boxer.css') }}
    {{ HTML::style('libs/datetime/picker.min.css') }}
    {{ HTML::style('libs/tags/bootstrap-tagsinput.css') }}
    
    <!--[if lt IE 9]>
    {{ HTML::script('http://html5shiv.googlecode.com/svn/trunk/html5.js') }}
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
    {{ HTML::script('libs/framework.js') }}
    {{ HTML::script('libs/backend.js') }}
</head>
<body>
    <div id="page-container">
        <noscript><div id="nojavascript"><img src="{{ asset('icons/exclamation.png') }}" width="16" height="16" alt="icon"> {{ trans('app.no_js') }}</div></noscript>
        <header id="header">
            <a id="header-logo" href="{{ route('admin.dashboard') }}" title="{{ trans('app.admin_dashboard') }}"><!-- empty --></a>
            
            <a id="hnav1" class="hnav" href="{{ route('admin.dashboard') }}"><!-- empty --></a>
            <a id="hnav2" class="hnav" href="{{ route('home') }}"><!-- empty --></a>
            <a id="hnav3" class="hnav" href="{{ url('admin/help') }}"><!-- empty --></a>
            <a id="hnav4" class="hnav" href="{{ url('admin/help/info') }}"><!-- empty --></a>
            <a id="hnav5" class="hnav" href="{{ route('logout') }}"><!-- empty --></a>
        
            <img id="user-img" src="{{ $userImage }}" alt="User">
            <img id="user-img-overlay" src="{{ asset('theme/backend/photo.png') }}" width="45" height="60" alt="Overlay">
            <a id="profile-link" href="{{ url('users/'.Sentry::getUser()->id) }}" title="Show your profile"><!-- empty--></a>
            
            <div id="info-box">
                <span>Welcome, {{ Sentry::getUser()->username }}!</span> 
                @if ($contactMessages)
                {{ HTML::image(asset('icons/email.png'), 'Message') }} {{ $contactMessages }}
                @endif
            </div>
            
            <div id="info-bar"><span id="datetime">{{ Carbon::now() }} – {{ date('H:i') }}</span> now. Version {{ Config::get('app.version') }}</div>
            
            <a id="tecslink" href="{{ url('admin/help/technologies') }}" title="{{ trans('app.tec_infos') }}"><!-- empty --></a>
            
            <a id="quicklink1" class="quicklink" href="{{ url('users/'.Sentry::getUser()->id.'/edit') }}" title="Edit your Profile" style="left: 669px"><!-- empty --></a>
            <a id="quicklink2" class="quicklink" href="{{ url('admin/help') }}" title="Help" style="left: 689px"><!-- empty --></a>
            <a id="quicklink3" class="quicklink" href="{{ url('auth/logout') }}" title="Log out" style="left: 709px"><!-- empty --></a>
            
            <a id="quicklink4" class="quicklink" href="{{ url('admin/news') }}" title="News" style="left: 740px"><!-- empty --></a>
            <a id="quicklink5" class="quicklink" href="admin.php?site=pages" title="Pages" style="left: 760px"><!-- empty --></a>
            <a id="quicklink6" class="quicklink" href="{{ url('admin/images') }}" title="Images" style="left: 780px"><!-- empty --></a>
            <a id="quicklink7" class="quicklink" href="admin.php?site=downloads" title="Downloads" style="left: 800px"><!-- empty --></a>
            <a id="quicklink8" class="quicklink" href="admin.php?site=matches" title="Matches" style="left: 820px"><!-- empty --></a>
            
            <a id="quicklink9" class="quicklink" href="{{ url('admin/users') }}" title="Users" style="left: 851px"><!-- empty --></a>
            <a id="quicklink10" class="quicklink" href="admin.php?site=members" title="Members" style="left: 871px"><!-- empty --></a>
            <a id="quicklink11" class="quicklink" href="{{ url('admin/config') }}" title="Settings" style="left: 891px"><!-- empty --></a>
        </header>
        <section id="content-wrapper">
            <aside id="sidebar">
                <nav>
                    <div class="applemenu">
                        <div class="silverheader"><div class="menu-head daily"><span class="hidden">Daily Content</span></div></div>
                            <div class="submenu">
                                <div class="menu1"><img src="{{ asset('icons/house.png') }}" width="16" height="16" alt="icon">{{ HTML::link('admin/dashboard', 'Dashboard') }}</div>
                                
                                <div class="menu2"><img src="{{ asset('icons/newspaper.png') }}" width="16" height="16" alt="icon"><a href="{{ route('admin.news.index') }}">News</a></div>
                                <div class="menu2"><img src="{{ asset('icons/doc_offlice.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=pages">Pages</a></div>
                                <div class="menu2"><img src="{{ asset('icons/blog.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=headlines">Headlines</a></div>
                                
                                <div class="menu2"><img src="{{ asset('icons/date.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=events">Events</a></div>
                                <div class="menu2"><img src="{{ asset('icons/chart_pie.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=polls">Polls</a></div>
                                <div class="menu2"><img src="{{ asset('icons/folder.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=downloads">Downloads</a></div>
                                <div class="menu2"><img src="{{ asset('icons/picture.png') }}" width="16" height="16" alt="icon">{{ HTML::link('admin/images', 'Images') }}</div>
                                <div class="menu3"><img src="{{ asset('icons/photo.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=galleries">Galleries</a></div>
                            </div>
                            <div class="menu-bottom"></div>
                        <div class="silverheader"><div class="menu-head perma"><span class="hidden">Permanent Content</span></div></div>
                            <div class="submenu">
                                <div class="menu1"><img src="{{ asset('icons/newspaper.png') }}" width="16" height="16" alt="icon">{{ HTML::link('admin/newscats', 'News-Categories') }}</div>
                                <div class="menu2"><img src="{{ asset('icons/color_swatch.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=slider">Slider</a></div>
                                <div class="menu2"><img src="{{ asset('icons/blog.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=headlinecats">Headline-Categories</a></div>
                                <div class="menu2"><img src="{{ asset('icons/film.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=videos">Videos</a></div>
                                <div class="menu2"><img src="{{ asset('icons/film_link.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=streams">Livestreams</a></div> 

                                <div class="menu2"><img src="{{ asset('icons/folder.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=downloadcats">Download-Categories</a></div>
                                <div class="menu2"><img src="{{ asset('icons/coins.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=sponsors">Sponsors &amp; Partners</a></div>
                                <div class="menu2"><img src="{{ asset('icons/money.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=adverts">Advertisement</a></div>
                                <div class="menu2"><img src="{{ asset('icons/comment.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=forumcats">Forum-Categories</a></div>
                                <div class="menu2"><img src="{{ asset('icons/comment.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=forumboards">Forum-Boards</a></div>
                            </div>
                            <div class="menu-bottom"></div>
                        <div class="silverheader"><div class="menu-head esports"><span class="hidden">eSports Content</span></div></div>
                            <div class="submenu">               
                                <div class="menu1"><img src="{{ asset('icons/sport_soccer.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=matches">Matches</a></div>
                                <div class="menu2"><img src="{{ asset('icons/award_star_gold_3.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=awards">Awards</a></div>
                                <div class="menu2"><img src="{{ asset('icons/server.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=gameservers">Gameservers</a></div>                                
                            
                                <div class="menu2"><img src="{{ asset('icons/clan.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=clans">Clans</a></div>
                                <div class="menu2"><img src="{{ asset('icons/joystick.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=leagues">Leagues</a></div>
                                <div class="menu2"><img src="{{ asset('icons/world.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=maps">Maps</a></div>
                                <div class="menu3"><img src="{{ asset('icons/controller.png') }}" width="16" height="16" alt="icon">{{ HTML::link('admin/games', 'Games') }}</div>
                            </div>
                            <div class="menu-bottom"></div>
                            
                        <div class="silverheader"><div class="menu-head website"><span class="hidden">Website</span></div></div>
                            <div class="submenu">
                                <div class="menu1"><img src="{{ asset('icons/user.png') }}" width="16" height="16" alt="icon"><a href="{{ route('admin.users.index') }}">Users</a></div>
                                <div class="menu2"><img src="{{ asset('icons/group.png') }}" width="16" height="16" alt="icon"><a href="admin.php?site=members">Members</a></div>
                                <div class="menu3"><img src="{{ asset('icons/flag_red.png') }}" width="16" height="16" alt="icon">{{ HTML::link('admin/teams', 'Teams') }}</div>

                                <div class="menu3"><img src="{{ asset('icons/cog.png') }}" width="16" height="16" alt="icon">{{ HTML::link('admin/config', 'Config') }}</div>  
                                <div class="menu2"><img src="{{ asset('icons/email.png') }}" width="16" height="16" alt="icon">{{ HTML::link('admin/contact', 'Contact') }}</div>
                                <div class="menu2"><img src="{{ asset('icons/chart_bar.png') }}" width="16" height="16" alt="icon">{{ HTML::link('admin/visitors', 'Visitors') }}</div>

                                <div class="menu3"><img src="{{ asset('icons/lock.png') }}" width="16" height="16" alt="icon">{{ HTML::link('admin/groups', 'Groups & Permissions') }}</div>
                            </div>
                            <div class="menu-bottom"></div>
                    </div>
                </nav>
            </aside>

            <section id="main-content">
                    @if (Session::get('_message'))
                    <div class="cms-message">
                        {{ Session::get('_message') }}
                    </div>
                    @endif
                
                    @if (isset($page))
                    <div class="page page-{{ strtolower($controller) }} page-{{ pageClass() }}">
                        <a class="form-head" href="{{ url('admin/'.strtolower($controller)) }}">
                            <img src="{{ asset('icons/'.$controllerIcon) }}" width="16" height="16" alt="Icon">{{ $controller }}
                        </a>

                        {{ $page }}
                    </div>
                    @endif
            </section>
        </section>
        <footer id="footer">
            <a href="#" title="{{ trans('app.top') }}"><!-- empty --></a>
        </footer>
    </section>
</body>
</html>