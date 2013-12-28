<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Running with Contentify CMS -->

	<meta charset="utf-8" />

	<title>Contentify</title>

	<meta name="generator" content="Contentify" />

	<link rel="stylesheet" href="{{ asset('frontend.css') }}" type="text/css" />

	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script type="text/javascript" src="{{ asset('libs/frontend.js') }}"></script>

	<style type="text/css">

	</style>
</head>
<body>
	<div id="page-container">
		<header id="header">
			<h2>Contentify Testpage</h2>
		</header>

		<div id="mid-container">
			<section id="content">
				@if (Session::get('_message'))
					<div class="cms-message">
						{{ Session::get('_message') }}
					</div>
				@endif

				<section id="page">
					@if (isset($page))
						{{ $page }}
					@endif
				</section>
			</section>

			<aside id="sidebar">
				<ul class="layout-v">
					<li>{{ link_to('auth/login', 'Login') }}</li>
					<li>{{ link_to('auth/logout', 'Logout') }}</li>
					<li>{{ link_to('auth/registration/create', 'Registration') }}</li>
					<li>{{ link_to('contact', 'Contact') }}</li>
					<li>{{ link_to('search', 'Search') }}</li>
					<li>{{ link_to('admin', 'Admin-Backend') }}</li>
				</ul>
			</aside>

			<div class="clear"></div>
		</div>
	</div>
</body>
</html>
