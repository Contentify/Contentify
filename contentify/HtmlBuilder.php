<?php namespace Contentify;
 
use Collective\Html\HtmlBuilder as OriginalHtmlBuilder;
use OpenGraph, Session, URL;

class HtmlBuilder extends OriginalHtmlBuilder {

    /**
     * Renders a widget.
     *
     * @param string    $widgetName The class name of the widget. For module widgets it's <Module>::<Widget>
     * @param array     $parameters Array with parameters (name-value-pairs)
     * @return string
     */
    public function widget($widgetName, $parameters = null)
    { 
        if (! is_array($parameters)) $parameters = (array) $parameters;

        $path = explode('::', $widgetName);
        if (sizeof($path) < 2) {
            throw new Exception("Error: Invalid widget classname '{$widgetName}'. Module name missing?");
        }

        $className = 'App\Modules\\'.$path[0].'\Http\Controllers\\'.$path[1].'Widget';
        $widget = new $className();

        return $widget->render($parameters);
    }

    /**
     * Renders an image with a gravatar.
     *
     * @param string    $email      The email of the gravatar user
     * @param int       $size       The size of the image
     * @param string    $default    Default picture (if no picture is found)
     * @return string
     */
    public function gravatar($email, $size = 32, $default = 'mm')
    { 
        return '<img src="http://www.gravatar.com/avatar/'.md5(strtolower(trim($email))).
            '?s='.$size.'&d='.$default.'" alt="Avatar">';
    }

    /**
     * Renders meta tags.
     *
     * @param array $metaTags
     * @return string
     */
    public function metaTags($metaTags = array())
    { 
        $output = '';
        foreach ($metaTags as $name => $content) {
            $output .= '<meta name="'.$name.'" content="'.$content.'">';
        }

        return $output;
    }

    /**
     * Renders the title tag.
     *
     * @param string $title
     * @return string
     */
    public function title($title = null)
    { 
        if ($title) {
            $title .= ' - '.Config::get('app.title');
        } else {
            $title = Config::get('app.title');
        }

        return '<title>'.$title.'</title>';
    }

    /**
     * Renders Open Graph tags
     *
     * @param  OpenGraph The Open Graph instance
     * @return string
     */
    public function openGraphTags(OpenGraph $og)
    { 
        $output = $og->renderTags();

        return $output;
    }

    /**
     * Returns HTML code for a table.
     * 
     * @param array     $header          Array with the table header items (String-Array)
     * @param array     $rows            Array with all the table rows items (Array containing String-Arrays)
     * @param bool      $brightenFirst   Enable special look for the items in the first column? (true/false)
     * @return string
     */
    public function table($header, $rows, $brightenFirst = true)
    {
        $code = '<table class="table table-hover">';

        /*
         * Table head
         */
        $code .= '<thead><tr>';
        foreach ($header as $value) {
            $code .= '<th>'.$value.'</th>';
        }
        $code .= '</tr></thead>';

        /*
         * Table body
         */
        foreach ($rows as $row) {
            $code    .= '<tr>';
            $isFirst = true;
            foreach ($row as $value) {
                if (! is_a($value, 'Raw')) {
                    $value = e($value); // Always auto escape except value is marked as raw
                }

                if ($isFirst and $brightenFirst) {
                    $code    .= '<td style="color: silver">';
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

    /**
     * Returns HTML code of a "image link" - a link with an image (and maybe a text).
     * If $image has no extension, the extension will be ".png".
     * If $image does not contain a path, the path "icons" will be used.
     * If $image has does not start with "http://" an asset link will be created.
     * 
     * @param  string  $image       The link image
     * @param  string  $title       The link title
     * @param  string  $url         The link URL
     * @param  boolean $showTitle   Show the title text?
     * @param  array   $attributes  Apply these HTML attributes to the link element
     * @return string
     */
    public function imageLink($image, $title, $url, $showTitle = false, $attributes = array())
    {
        $imageUrl = get_image_url($image);
        $image = self::image($imageUrl, $title);

        if ($showTitle) {
            $titleText = ' '.$title;
        } else {
            $titleText = '';
        }

        /* 
         * We have to create our Link without self::link(), because
         * that method will not allow to use HTML code.
         */ 
        $attrs = self::attributes($attributes);
        $link = '<a class="image-link" href="'.$url.'" title="'.$title.'" '.$attrs.'>'.$image.$titleText.'</a>';

        return $link;
    }

    /**
     * Returns HTML code of an "icon link" - a link with an icon (and maybe a text).
     * An icon font will be used to render the icon.
     * 
     * @param  string  $icon        The name of the icon
     * @param  string  $url         The link URL
     * @param  string  $title       The link title
     * @param  boolean $showTitle   Show the title text?
     * @param  array   $attributes  Apply these HTML attributes to the link element
     * @return string
     */
    public function iconLink($icon, $title, $url, $showTitle = false, $attributes = array())
    {
        $icon = self::fontIcon($icon);

        $titleText = '';
        if ($showTitle) {
            $titleText = ' '.$title;
        }

        /* 
         * We have to create our Link without self::link(), because
         * that method will not allow to use HTML code.
         */ 
        $attributes = self::attributes($attributes);
        $link = '<a class="icon-link" href="'.$url.'" title="'.$title.'" '.$attributes.'>'.$icon.$titleText.'</a>';

        return $link;
    }

    /**
     * Returns HTML code for a button element. It may include an icon element and a title text.
     * 
     * @param  string $title        The button title text
     * @param  string $url          The URL the button is targeting at
     * @param  string $icon         The name of the icon. It's rendered by an icon font.
     * @param  array  $attributes   Apply these HTML attributes to the link element
     * @return string
     */
    public function button($title, $url, $icon = '', $attributes = array())
    {
        $action = 'onclick="document.location.href=\''.$url.'\'"';

        if ($icon) {
            $icon = self::fontIcon($icon).' ';
        }

        if (! array_key_exists('class', $attributes)) {
            $attributes['class'] = 'btn btn-default';
        }

        $attributes = self::attributes($attributes);
        
        $button = '<button type="button" '.$action.' '.$attributes.'>'.$icon.$title.'</button>';

        return $button;
    }
    
    /**
     * Returns HTML code for a sort switcher (asc / desc).
     *
     * @param string $sortby    Attribute of the model, e.g. "id"
     * @param string $order     Current sorting order, "asc" or "desc"
     * @return string
     */
    public function sortSwitcher($sortby, $order = null, $search = null)
    {
        if ($order == 'asc') {
            $order  = 'desc';
            $icon   = 'caret-up';
        } else {
            $order  = 'asc';
            $icon   = 'caret-down';
        }

        $url = URL::current().'?sortby='.$sortby.'&order='.$order;
        if ($search) $url .= '&search='.urlencode($search);

        $caption = trans('app.sorting').': '.self::fontIcon($icon);
        return '<a class="sort-switcher" href="'.$url.'">'.$caption.'</a>';
    }

    /**
     * Returns HTML code for a button enabling/disabling recycle bin mode
     * (to restore soft deleted entities).
     * 
     * @return string
     */
    public function recycleBinButton()
    {
        $enabled    = (bool) Session::get('recycleBinMode');
        $url        = URL::current().'?binmode='.(1 - $enabled);

        if ($enabled) {
            $class = 'enabled';
        } else {
            $class = 'disabled';
        }

        return '<a class="recycle-bin-button '.$class.'" href="'.$url.'">'.trans('app.recycle_bin').'</a>';
    }

    /**
     * Helper method. Just passes through the HTML code
     * BackNavGen::get() returns.
     * 
     * @return string
     */
    public function renderBackendNav()
    {
        $backNavGen = new BackNavGen();
        return $backNavGen->get();
    }

    /**
     * Returns HTML code for a font icon.
     *
     * @param string $icon  The name of the icon
     * @param string $color The color of the icon (HTML color name or code)
     * @return string
     */
    public function fontIcon($icon, $color = null)
    {
        if ($color) {
            $color = ' style="color: '.$color.'"';
        }

        return '<i class="fa fa-'.$icon.'"'.$color.'></i>';
    }

    /**
     * Returns JS code that creates a variable that contains a (JSON) object
     * with the translations of the app namespace. This allows us to access 
     * translations in the frontend (via JS).
     * 
     * @return string
     */
    public function jsTranslations()
    {
        $translator = app('translator');
        $items = $translator->get('app');

        return '<script>var contentifyTranslations = '.json_encode($items).';</script>';
    }

}