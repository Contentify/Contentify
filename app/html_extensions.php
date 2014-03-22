<?php

/*
|--------------------------------------------------------------------------
| HTML Extensions
|--------------------------------------------------------------------------
|
| This is the right place to setup global HTML extensions (macros).
|
*/

/*
 * Renders a widget.
 */
HTML::macro('widget', function($widgetName, $parameters = null)
{ 
    if (! is_array($parameters)) $parameters = (array) $parameters;

    $path = explode('::', $widgetName);
    if (sizeof($path) < 2) {
        throw new Exception("Error: Invalid widget classname '{$widgetName}'. Module name missing?");
    }

    $className = 'App\Modules\\'.$path[0].'\Controllers\\'.$path[1].'Widget';
    $widget = new $className();

    return $widget->render($parameters);
});

/*
 * Renders an image with a gravatar.
 */
HTML::macro('gravatar', function($email, $size = 32, $default = 'mm')
{ 
    return '<img src="http://www.gravatar.com/avatar/'.md5(strtolower(trim($email))).
        '?s='.$size.'&d='.$default.'" alt="Avatar">';
});

/*
 * Renders meta tags.
 */
HTML::macro('metaTags', function($metaTags = array())
{ 
    $output = '';
    foreach ($metaTags as $name => $content) {
        $output .= '<meta name="'.$name.'" content="'.$content.'">';
    }

    return $output;
});

/*
 * Renders the title tag.
 */
HTML::macro('title', function($title = null)
{ 
    if ($title) {
        $title .= ' - '.Config::get('app.title');
    } else {
        $title = Config::get('app.title');
    }

    return '<title>'.$title.'</title>';
});

/*
 * Renders Open Graph tags
 */
HTML::macro('openGraphTags', function(OpenGraph $og)
{ 
    $output = $og->renderTags();

    return $output;
});