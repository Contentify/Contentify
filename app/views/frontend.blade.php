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
	<script type="text/javascript" src="{{ asset('libs/base.js') }}"></script>

	<style type="text/css">

	</style>
</head>
<body>
	<div id="page-container">
		<header id="header">

		</header>

		<div id="mid-container">
			<section id="content">
				@if (Session::get('message'))
					<div class="cms-message">
						{{ Session::get('message') }}
					</div>
				@endif

				<div id="page">
					@if (isset($page))
						{{ $page }}
					@endif
				</div>
			</section>

			<aside id="sidebar">

			</aside>

			<div class="clear"></div>
		</div>
	</div>
</body>
</html>