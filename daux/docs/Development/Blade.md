Blade is Laravels template compiler ([Blade docs](http://laravel.com/docs/templates)). When working with templates in Contentify you can choose between PHP templates (\*.php) and Blade templates (\*.blade.php). Or juse a mix: Blade extends PHP templates, so you can use PHP inside Blade templates. Furthermore Blade compiles its template as PHP templates.

The official documentation covers Blade so here are only a few important control structures listed.

Comments:

    {{-- This is a blade comment. --}}

Echoing data:

    {{ $user->username }}

Echoing data using escaping (htmlentities):

    {{{ $user->username }}}

Three curly brackets are used to escape an expression. This will escape HTML entities. It's important to escape data that might contain dangerous HTML tags such as `<script>`. Users might try to use HTML tags that are not escaped to manipulate content, redirect the user to third party websites and so on.

If statements:

    @if(true)
        <p>Hello world!</p>
    @endif

Loops:

    @foreach ($users as $user)
        <p>This is user {{ $user->id }}</p>
    @endforeach

Blade transforms Blade syntax into PHP Syntax. So you can use PHP inside Blade expressions:

    {{ $unicorn->age + 1337 }}
    {{ Config::get('app.version') }}

> Contentify extends Blade and adds more awesomeness, for example widgets. You can write you own extensions, if you need even more features. Take a look into `app/blade_extensions.php` for examples.

## Important Helpers

Including a stylesheet:

    {{ HTML::style('stylesheet.css') }}
    <!-- <link media="all" type="text/css" rel="stylesheet" href="http://localhost/contentify/stylesheet.css"> -->

Including a JavaScript file:

    {{ HTML::script('script.js') }}
    <!-- <script src="http://localhost/contentify/script.js"></script> -->

You should use Laravel's helpers to create links. Create a link to a given URL:

    {{ link_to('http://www.google.de', 'Google') }}
    <!-- <a href="http://www.google.de">Google</a> -->

Generate a URL for an asset:

    <img src="{{ asset('img/example.png') }}" />
    <!-- <img src="http://localhost/contentify/img/example.png" /> -->

## Template Compiling

Blade is a template compiler, meaning it converts Blade templates to PHP templates. Blade will store compiled templates in `app/storage/views` as files without extensions. These files are PHP files. Compiling Blade templates and caching them makes Blade templates as fast as pure PHP templates. We recommend using Blade.

Example - Blade:

    {{ $expression }}

After compilation:

    <?php echo $expression; ?>

## Layouts

Templates may contain HTML code or HTML code snippets. Layouts are special templates: They contain complete HTML pages, including tags like `<head>` and `<body>`.

## Templates Versus Controllers

It's possible to use PHP inside Blade templates. However, try to use as few PHP code as possible inside templates. Only use PHP functions to influence the presentation of data. For example, use date functions to convert dates. But avoid writing algorithms inside templates! Business logic lives inside controllers or models. This is called "seperation of concerns" and the basic principle of the MVC (Model-View-Controller) design pattern Laravel uses.