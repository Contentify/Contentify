<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Contentify - Step {{ $step + 1 }}</title>

    <meta name="generator" content="Contentify">

    <link rel="shortcut icon" type="picture/x-icon" href="{{ asset('favicon.png') }}">

    <!--[if lt IE 9]>
    {{ HTML::script('http://html5shiv.googlecode.com/svn/trunk/html5.js') }}
    <![endif]-->
    {{ HTML::script('libs/jquery-1.10.2.min.js') }}
    
    <style type="text/css">
        @import url(http://fonts.googleapis.com/css?family=Open+Sans:400,700);

        body { margin: 0px; padding: 0px; background-color: white; font-family: 'Open Sans', Arial; font-size: 16px; color: #444 }
        a { color: #4082E3; text-decoration: none; }
        code { color: #666 }
        p { margin: 15px 0px }
        label { display: inline-block; width: 100px; cursor: pointer; }
        label:after { content: ':'; }
        input[type=text], input[type=email], input[type=password] { padding: 5px 10px; background-color: white; border: 1px solid silver; border-radius: 3px; font-family: 'Open Sans'; color: #4082E3 }
        input[type=text]:focus, input[type=email]:focus, input[type=password]:focus { border-color: #FF6100 }
        .form-group { margin-bottom: 20px }

        #page-container { width: 650px; min-height: 100px; margin-left: auto; margin-right: auto; }
        #logo { width: 100px; height: 100px; float: left }
        #content { margin-left: 200px; text-align: justify; }
        #content h1 { margin: 0px 0px 30px 0px; font-family: Arial; color: #444 }
        #content .warning { color: #FF6100 }
        .buttons { position: relative; margin-top: 100px }
        .navbut { position: absolute; top: 150%; font-family: 'Open Sans'; width: 100px; height: 24px; padding: 5px 20px; border: 2px solid #FF6100; border-radius: 5px; background-color: #FF6100; color: white; box-sizing: content-box; -moz-box-sizing: content-box; font-family: 'Open Sans'; font-size: 18px; box-shadow: 0px 4px 0px #d45100; vertical-align: top; text-align: center; }
        .navbut:hover { opacity: 0.9; filter: alpha(opacity = 0.9); }
        .navbut.left:hover { content: '<' }
        .navbut.right:hover { content: '>' }
        .navbut:active { margin-top: 4px; box-shadow: none; transition: all 0.08s linear; }
        .navbut.left { right: 154px; }
        .navbut.right { right: 0px; }
    </style>
</head>
<body data-base-url="{{ url('/') }}">
    <div id="page-container">
        <a href="http://contentify.it" target="_blank"><img id="logo" src="{{ url('theme/logo_180.png') }}" width="100" height="100" alt="Logo"></a>
        <div id="content">
            <h1>{{ $title }}</h1>
            <div class="text">
                {{ $content }}
            </div>

            <div class="buttons">
                @if($step > 0)
                <a class="navbut left" href="{{ url('install?step='.($step - 1)) }}">Previous</a>
                @endif

                @if($step < 5)
                <a class="navbut right" href="{{ url('install?step='.($step + 1)) }}">Next</a>
                @else
                <a class="navbut right" href="{{ url('/') }}">Website</a>
                @endif
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $(window).resize(function()
            {
                var paddingTop = 0.33 * ($(window).height() - $('#page-container').height());
                $('#page-container').css('paddingTop', paddingTop);
            });
            $(window).resize();

            if({{ $step }} == 4) {
                $('.navbut.right').click(function(event)
                {
                    event.preventDefault();
                    $('form').get(0).submit();
                });
            }

            $('.navbut.left').mousedown(function()
            {
                $fullWidth = $('html').width();
                $('body').animate({width: $fullWidth - 10}, {duration: 200, queue: true});
            });
            $('.navbut.right').mousedown(function()
            {
                $fullWidth = $('html').width();
                $('body').animate({width: $fullWidth + 10}, {duration: 200, queue: true});
            });
            $('.navbut.left, .navbut.right').mouseup(function()
            {
                $fullWidth = $('html').width();
                $('body').animate({width: $fullWidth}, {duration: 200, queue: true});
            });
        });
    </script>
</body>
</html>