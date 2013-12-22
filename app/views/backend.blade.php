<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Running with Contentify CMS -->

	<meta charset="utf-8" />

	<title>{{ Config::get('app.title') }}</title>

	<meta name="generator" content="Contentify" />

	<link rel="shortcut icon" type="picture/x-icon" href="{{ asset('theme/favicon.png') }}" />

    <link rel="stylesheet" type="text/css" href="{{ asset('backend.css') }}" />
    <link rel="Stylesheet" type="text/css" href="{{ asset('libs/jqueryui/jquery-ui-1.8.11.custom.css') }}">
    <link rel="Stylesheet" type="text/css" href="{{ asset('libs/jquery.ui.timepicker.css') }}">
    
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script type="text/javascript" src="{{ asset('libs/jquery-1.10.2.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('libs/jquery-ui-1.8.11.custom.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('libs/quicktip.js') }}"></script>
	<script type="text/javascript" src="{{ asset('libs/ddaccordion.js') }}">
	//
	// Accordion Content script- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
	// Visit http://www.dynamicDrive.com for hundreds of DHTML scripts
	// This notice must stay intact for legal use
	//
	</script>
	<script type="text/javascript" src="{{ asset('libs/backend.js') }}"></script>
</head>
<body style="background-image: url('theme/backend/background.png'); background-color: white">
	<section id="pagecontainer" style="width: 986px; margin-left: auto; margin-right: auto">
	    <noscript><div id="nojavascript"><img src="icos/exclamation.png" width="16" height="16" alt="icon" /> JavaScript not enabled. Please enable JavaScript!</div></noscript>
		<header style="position: relative; width: 100%; height: 219px; background-image:url(theme/backend/header.png)">
        	<a id="headerlogo" href="{{ route('admin.dashboard') }}" title="Admin-Dashboard" style="position: absolute; background-image:url('theme/empty.png'); width: 270px; height: 92px"><!-- empty --></a>
            
            <a id="hnav1" class="hnav" href="{{ route('admin.dashboard') }}"><!-- empty --></a>
            <a id="hnav2" class="hnav" href="{{ route('home') }}"><!-- empty --></a>
            <a id="hnav3" class="hnav" href="admin.php?site=help"><!-- empty --></a>
            <a id="hnav4" class="hnav" href="admin.php?site=help&amp;action=info"><!-- empty --></a>
            <a id="hnav5" class="hnav" href="{{ route('logout') }}"><!-- empty --></a>
        
			<img src="{..$profilepic..}" alt="profileimage" style="position: absolute; display: block; left: 6px; top: 112px; width: 45px; height: 60px" />
            <img src="theme/backend/photo.png" width="45" height="60" alt="photooverlay" style="position: absolute; display: block; left: 6px; top: 112px; z-index: 2" />
			<a href="profile/{..$userid..}/" title="Show your profile" style="position: absolute; display: block; background-image:url('theme/empty.png'); left: 6px; top: 112px; width: 45px; height: 60px; z-index: 3"><!-- empty--></a>
            
            <div id="infobox" style="position: absolute; display: block; left: 72px; top: 120px; width: 180px; height: 50px; overflow: hidden; color: white; text-shadow: #404040 0px 1px 1px"><span style="display: block; margin-bottom: 10px">Welcome, {..$nickname..}!</span>{..$messagecode..}</div>
            
			<div style="position: absolute; display: block; left: 670px; top: 95px; width: 230px; color: white; font-size: 11px; text-align: center"><span id="datetime">{{ date('d/m/Y') }} – {{ date('H:i') }}</span> now. Version {{ Config::get('app.version') }}</div>
            
            <a id="tecslink" href="admin.php?site=help&amp;action=technologies" title="Show infos about the technologies used" style="position: absolute; background-image:url('theme/empty.png'); left: 931px; top: 92px; width: 15px; height: 20px"><!-- empty --></a>
            
            <a id="quicklink1" class="quicklink" href="editprofile/{..$userid..}/" title="Edit your Profile" style="left: 669px"><!-- empty --></a>
            <a id="quicklink2" class="quicklink" href="admin.php?site=help" title="Help" style="left: 689px"><!-- empty --></a>
            <a id="quicklink3" class="quicklink" href="logout/" title="Log out" style="left: 709px"><!-- empty --></a>
            
            <a id="quicklink4" class="quicklink" href="admin.php?site=news" title="News" style="left: 740px"><!-- empty --></a>
            <a id="quicklink5" class="quicklink" href="admin.php?site=pages" title="Pages" style="left: 760px"><!-- empty --></a>
            <a id="quicklink6" class="quicklink" href="admin.php?site=pictures" title="Pictures" style="left: 780px"><!-- empty --></a>
            <a id="quicklink7" class="quicklink" href="admin.php?site=downloads" title="Downloads" style="left: 800px"><!-- empty --></a>
            <a id="quicklink8" class="quicklink" href="admin.php?site=matches" title="Matches" style="left: 820px"><!-- empty --></a>
            
            <a id="quicklink9" class="quicklink" href="admin.php?site=users" title="Users" style="left: 851px"><!-- empty --></a>
            <a id="quicklink10" class="quicklink" href="admin.php?site=members" title="Members" style="left: 871px"><!-- empty --></a>
            <a id="quicklink11" class="quicklink" href="admin.php?site=settings" title="Settings" style="left: 891px"><!-- empty --></a>
        </header>
        <section id="contentwrapper" style="background-image:url('theme/backend/contentbg.png'); width: 100%; min-height: 785px; padding-bottom: 100px">
        	<aside id="sidebar" style="float: left; width: 237px; padding: 0px 15px 0px 20px">
            	<nav>
					<div class="applemenu">
						<div class="silverheader"><div class="menuhead" style="background-image:url('theme/backend/nav_header_daily.png')"><span class="hidden">Daily Content</span></div></div>
							<div class="submenu">
								<div class="menu1"><img src="icos/house.png" width="16" height="16" alt="icon" /><a href="admin.php?site=dashboard">Startseite</a></div>
                                
								<div class="menu2"><img src="icos/newspaper.png" width="16" height="16" alt="icon" /><a href="admin.php?site=news">News</a></div>
								<div class="menu2"><img src="icos/doc_offlice.png" width="16" height="16" alt="icon" /><a href="admin.php?site=pages">Pages</a></div>
								<div class="menu2"><img src="icos/blog.png" width="16" height="16" alt="icon" /><a href="admin.php?site=headlines">Headlines</a></div>
                                
								<div class="menu2"><img src="icos/date.png" width="16" height="16" alt="icon" /><a href="admin.php?site=events">Events</a></div>			
								<div class="menu2"><img src="icos/chart_pie.png" width="16" height="16" alt="icon" /><a href="admin.php?site=polls">Polls</a></div>
								<div class="menu2"><img src="icos/folder.png" width="16" height="16" alt="icon" /><a href="admin.php?site=downloads">Downloads</a></div>
								<div class="menu2"><img src="icos/picture.png" width="16" height="16" alt="icon" /><a href="admin.php?site=pictures">Pictures</a></div>
								<div class="menu3"><img src="icos/photo.png" width="16" height="16" alt="icon" /><a href="admin.php?site=galleries">Galleries</a></div>
							</div>
							<div class="menubottom"><!-- empty --></div>
						<div class="silverheader"><div class="menuhead" style="background-image:url('theme/backend/nav_header_perma.png')"><span class="hidden">Permanent Content</span></div></div>
							<div class="submenu">
								<div class="menu1"><img src="icos/newspaper.png" width="16" height="16" alt="icon" /><a href="admin.php?site=newscats">News-Categories</a></div>
								<div class="menu2"><img src="icos/color_swatch.png" width="16" height="16" alt="icon" /><a href="admin.php?site=coverages">Coverages</a></div>	
								<div class="menu2"><img src="icos/blog.png" width="16" height="16" alt="icon" /><a href="admin.php?site=headlinecats">Headline-Categories</a></div>
								<div class="menu2"><img src="icos/film.png" width="16" height="16" alt="icon" /><a href="admin.php?site=videos">Videos</a></div>
								<div class="menu2"><img src="icos/film_link.png" width="16" height="16" alt="icon" /><a href="admin.php?site=streams">Livestreams</a></div>	

								<div class="menu2"><img src="icos/folder.png" width="16" height="16" alt="icon" /><a href="admin.php?site=downloadcats">Download-Categories</a></div>				
								<div class="menu2"><img src="icos/coins.png" width="16" height="16" alt="icon" /><a href="admin.php?site=sponsors">Sponsors &amp; Partners</a></div>
								<div class="menu2"><img src="icos/money.png" width="16" height="16" alt="icon" /><a href="admin.php?site=adverts">Advertisement</a></div>
								<div class="menu2"><img src="icos/comment.png" width="16" height="16" alt="icon" /><a href="admin.php?site=forumcats">Forum-Categories</a></div>
								<div class="menu2"><img src="icos/comment.png" width="16" height="16" alt="icon" /><a href="admin.php?site=forumboards">Forum-Boards</a></div>
							</div>
							<div class="menubottom"><!-- empty --></div>
						<div class="silverheader"><div class="menuhead" style="background-image:url('theme/backend/nav_header_esport.png')"><span class="hidden">eSport Content</span></div></div>
							<div class="submenu">				
								<div class="menu1"><img src="icos/sport_soccer.png" width="16" height="16" alt="icon" /><a href="admin.php?site=matches">Matches</a></div>
								<div class="menu2"><img src="icos/award_star_gold_3.png" width="16" height="16" alt="icon" /><a href="admin.php?site=awards">Awards</a></div>				
								<div class="menu2"><img src="icos/server.png" width="16" height="16" alt="icon" /><a href="admin.php?site=gameservers">Gameservers</a></div>								
							
								<div class="menu2"><img src="icos/clan.png" width="16" height="16" alt="icon" /><a href="admin.php?site=clans">Clans</a></div>
								<div class="menu2"><img src="icos/joystick.png" width="16" height="16" alt="icon" /><a href="admin.php?site=leagues">Leagues</a></div>						
								<div class="menu2"><img src="icos/world.png" width="16" height="16" alt="icon" /><a href="admin.php?site=maps">Maps</a></div>
								<div class="menu3"><img src="icos/controller.png" width="16" height="16" alt="icon" /><a href="admin.php?site=games">Games</a></div>				
							</div>
							<div class="menubottom"><!-- empty --></div>
							
						<div class="silverheader"><div class="menuhead" style="background-image:url('theme/backend/nav_header_website.png')"><span class="hidden">Website</span></div></div>
							<div class="submenu">
								<div class="menu1"><img src="icos/user.png" width="16" height="16" alt="icon" /><a href="admin.php?site=users">Users</a></div>
								<div class="menu2"><img src="icos/group.png" width="16" height="16" alt="icon" /><a href="admin.php?site=members">Members</a></div>
								<div class="menu2"><img src="icos/flag_red.png" width="16" height="16" alt="icon" /><a href="admin.php?site=teams">Teams &amp; Groups</a></div>

								<div class="menu2"><img src="icos/cog.png" width="16" height="16" alt="icon" /><a href="admin.php?site=settings">Settings</a></div>
								<div class="menu2"><img src="icos/email.png" width="16" height="16" alt="icon" /><a href="admin.php?site=contact">Contact</a></div>
								<div class="menu2"><img src="icos/chart_bar.png" width="16" height="16" alt="icon" /><a href="admin.php?site=statistics">Statistics</a></div>

								<div class="menu3"><img src="icos/lock.png" width="16" height="16" alt="icon" /><a href="admin.php?site=roles">Roles</a></div>
							</div>
							<div class="menubottom"><!-- empty --></div>
					</div>
                </nav>
            </aside>

            <section id="page" style="width: 686px; margin-left: 275px;">
					@if (isset($page))
						{{ $page }}
					@endif
			</section>
        </section>
        <footer style="position: relative; background-image:url('theme/backend/footer.png'); width: 982px; height: 76px; margin-left: 2px">
        	<a href="#" title="Top" style="display: block; position: absolute; background-image:url('theme/empty.png'); left: 114px; top: 30px; width: 45px; height: 35px"><!-- empty --></a>
        </footer>
    </section>
</body>
</html>