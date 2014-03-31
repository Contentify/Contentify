<?php

/*
|--------------------------------------------------------------------------
| HTML Extensions
|--------------------------------------------------------------------------
|
| This is the right place to setup global HTML extensions (macros).
|
*/

HTML::macro('widget', 
    /**
     * Renders a widget.
     *
     * @param string    $widgetName The class name of the widget. For module widgets it's <Module>::<Widget>
     * @param array     $parameters Array with parameters (name-value-pairs)
     * @return string
     */
    function ($widgetName, $parameters = null)
    { 
        if (! is_array($parameters)) $parameters = (array) $parameters;

        $path = explode('::', $widgetName);
        if (sizeof($path) < 2) {
            throw new Exception("Error: Invalid widget classname '{$widgetName}'. Module name missing?");
        }

        $className = 'App\Modules\\'.$path[0].'\Controllers\\'.$path[1].'Widget';
        $widget = new $className();

        return $widget->render($parameters);
    }
);

HTML::macro('gravatar',
    /**
     * Renders an image with a gravatar.
     *
     * @param string    $email      The email of the gravatar user
     * @param int       $size       The size of the image
     * @param string    $default    Default picture (if no picture is found)
     * @return string
     */
    function ($email, $size = 32, $default = 'mm')
    { 
        return '<img src="http://www.gravatar.com/avatar/'.md5(strtolower(trim($email))).
            '?s='.$size.'&d='.$default.'" alt="Avatar">';
    }
);

HTML::macro('metaTags', 
    /**
     * Renders meta tags.
     *
     * @param array $metaTags
     * @return string
     */
    function ($metaTags = array())
    { 
        $output = '';
        foreach ($metaTags as $name => $content) {
            $output .= '<meta name="'.$name.'" content="'.$content.'">';
        }

        return $output;
    }
);

HTML::macro('title', 
    /**
     * Renders the title tag.
     *
     * @param string $title
     * @return string
     */
    function ($title = null)
    { 
        if ($title) {
            $title .= ' - '.Config::get('app.title');
        } else {
            $title = Config::get('app.title');
        }

        return '<title>'.$title.'</title>';
    }
);

HTML::macro('openGraphTags', 
    /**
     * Renders Open Graph tags
     *
     * @param  OpenGraph The Open Graph instance
     * @return string
     */
    function (OpenGraph $og)
    { 
        $output = $og->renderTags();

        return $output;
    }
);


HTML::macro('table',
    /**
     * Returns HTML code for a table.
     * 
     * @param array     $header          Array with the table header items (String-Array)
     * @param array     $rows            Array with all the table rows items (Array containing String-Arrays)
     * @param bool      $highlightFirst  Enable special look for the items in the first column? (true/false)
     * @return string
     */
    function ($header, $rows, $brightenFirst = true)
    {
        $code = '<table class="content-table">';

        /*
         * Table head
         */
        $code .= '<tr>';
        foreach ($header as $value) {
            $code .= '<th>';
            $code .= $value;
            $code .= '</th>';
        }
        $code .= '</tr>';

        /*
         * Table body
         */
        foreach ($rows as $row) {
            $code   .= '<tr>';
            $isFirst = true;
            foreach ($row as $value) {
                if ($isFirst and $brightenFirst) {
                    $code   .= '<td style="color: silver">';
                    $isFirst = false;
                } else {
                    $code .= '<td>';
                }
                $code .= $value;
                $code .= '</td>';
            }
            $code .= '</tr>';
        }

        $code .= '</table>';

        return $code;
    }
);
