<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Running with Contentify CMS -->

	<meta charset="utf-8">

	<base href="{{ Config::get('application.baseurl') }}">

	<title>{{ Config::get('app.title') }}</title>

	<link rel="shortcut icon" type="picture/x-icon" href="{{ asset('theme/favicon.png') }}">

	{{ HTML::script('libs/jquery-1.10.2.min.js') }}

	<style>
		@font-face {
			font-family: 'Droid Sans';
			font-style: normal;
			font-weight: 400;
			src: local('Droid Sans'), local('DroidSans'), url(http://themes.googleusercontent.com/static/fonts/droidsans/v3/s-BiyweUPV0v-yRb-cjciBsxEYwM7FgeyaSgU71cLG0.woff) format('woff');
		}
		@font-face {
			font-family: 'Lato';
			font-style: normal;
			font-weight: 300;
			src: local('Lato Light'), local('Lato-Light'), url(http://themes.googleusercontent.com/static/fonts/lato/v6/KT3KS9Aol4WfR6Vas8kNcg.woff) format('woff');
		}

		body { margin: 0px; padding: 200px 0px 0px 0px; font-family: Verdana; font-size: 12px; color: silver; background-color: #111111 }

		#container { width: 400px; margin-left: auto; margin-right: auto; }
		
		.message-box { display: none; background-color: #070707; padding: 20px; border-radius: 10px; box-shadow: 0px 5px 5px 0px black }
		.message-box h1 { margin: 0px; font-family: 'Lato'; font-size: 20px; font-weight: normal; color: #ff6100; text-shadow: 0px 0px 1px #ff6100; text-transform: uppercase }
		.message-box p { margin: 20px 0px 0px 0px; font-family: 'Droid Sans'; font-size: 14px; color: #666; }
		.message-box hr { margin: 15px 0px; border: none; border-bottom: 1px dotted #333 }
		.message-box button { padding: 8px 16px; border: none; background-color: #3399ff; border-radius: 5px; font-family: 'Droid Sans'; font-size: 14px; color: white; text-transform: uppercase; cursor: pointer; }
	</style>
</head>
<body>
	<div id="container">
		@yield('content')
		<script>
			$(document).ready(function() {
				$('.message-box').css({'display': 'block', opacity: 0});
				$('.message-box').delay(200).animate({ marginTop: '-=10', opacity: 1 }, 400);
			});
		</script>
	</div>
</body>
</html>