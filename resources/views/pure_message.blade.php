<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Running with Contentify CMS -->

    <meta charset="utf-8">

    <base href="{!! url('/') !!}">

    <title>{{ Config::get('app.title') }}</title>

    <link rel="shortcut icon" type="picture/x-icon" href="{!! asset('img/default/favicon.png') !!}">

    {!! HTML::script('vendor/jquery/jquery-2.2.4.min.js') !!}

    <style>
        @import url('https://fonts.googleapis.com/css?family=Open+Sans:400,700');
        @font-face {
            font-family: 'Lato';
            font-style: normal;
            font-weight: 300;
            src: local('Lato Light'), local('Lato-Light'), url(https://themes.googleusercontent.com/static/fonts/lato/v6/KT3KS9Aol4WfR6Vas8kNcg.woff) format('woff');
        }

        body { margin: 0; font-family: Verdana; font-size: 12px; color: silver; background-color: white }

        #container { position: absolute; top: 50%; transform: translateY(-50%); width: 100% }
        #content { max-width: 400px; margin-left: auto; margin-right: auto; }
        
        .message-box { display: none; margin-top: 20px; padding: 20px; border: 2px solid #ddd; outline: solid 2px #eee; background-color: #eee; }
        .message-box h1 { margin: 0; font-family: 'Lato'; font-size: 20px; font-weight: normal; color: #ff6100; text-transform: uppercase }
        .message-box p { margin: 20px 0 0 0; font-family: 'Open Sans'; font-size: 14px; color: #666; }
        .message-box p a { color: #999; text-decoration: none }
        .message-box hr { margin: 15px 0; border: none; border-bottom: 1px dashed #ccc }
        .message-box button { padding: 8px 16px; border: none; background-color: #139cdc; border-radius: 5px; font-family: 'Open Sans'; font-size: 14px; color: white; text-transform: uppercase; cursor: pointer; }
    </style>
</head>
<body>
    <div id="container">
        <div id="content">
            @yield('content')
        </div>
        
        <script>
            $(document).ready(function() {
                $('.message-box').css({'display': 'block', opacity: 0});
                $('.message-box').animate({ marginTop: '-=20', opacity: 1 }, 400);
            });
        </script>
    </div>
</body>
</html>