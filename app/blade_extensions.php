<?php

/*
|--------------------------------------------------------------------------
| Blade Extensions
|--------------------------------------------------------------------------
|
| This is the right place to setup blade extensions
| that do not belong to modules.
|
*/

/*
 * Helper. Renders a widget.
 */
Blade::extend(function($view, $compiler) {
    $pattern = $compiler->createMatcher('widget');

    return preg_replace_callback($pattern, function ($matches)
    {
        $arguments = $matches[2];

        return '<?php echo HTML::widget'.$arguments.'; ?>';
    }, $view);
});