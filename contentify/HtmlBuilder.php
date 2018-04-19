<?php

namespace Contentify;
 
use Cache;
use Collective\Html\HtmlBuilder as OriginalHtmlBuilder;
use Contentify\Controllers\Widget;
use Exception;
use Input;
use OpenGraph;
use Session;
use URL;

class HtmlBuilder extends OriginalHtmlBuilder
{

    /**
     * The cache key used in cachedAssetPath()
     */
    const ASSET_PATH_CACHE_KEY = 'asset.pathCache.timestamp';

    /**
     * Renders a widget.
     *
     * @param string $widgetName The class name of the widget. For module widgets it's <Module>::<Widget>
     * @param array  $parameters Array with parameters (name-value-pairs)
     * @return string
     * @throws Exception
     */
    public function widget($widgetName, array $parameters = null)
    { 
        if (! is_array($parameters)) {
            $parameters = (array) $parameters;
        }

        $path = explode('::', $widgetName);
        if (sizeof($path) < 2) {
            throw new Exception("Error: Invalid widget class name '{$widgetName}'. Module name missing?");
        }

        $className = 'App\Modules\\'.$path[0].'\Http\Controllers\\'.$path[1].'Widget';

        /** @var Widget $widget */
        $widget = new $className();

        return $widget->render($parameters);
    }

    /**
     * Renders an image with a gravatar.
     *
     * @param string $email   The email of the gravatar user
     * @param int    $size    The size of the image
     * @param string $default Default picture (if no picture is found)
     * @return string
     */
    public function gravatar($email, $size = 32, $default = 'mm')
    { 
        return '<img src="https://www.gravatar.com/avatar/'.md5(strtolower(trim($email))).
            '?s='.$size.'&d='.$default.'" alt="Avatar">';
    }

    /**
     * Renders meta tags.
     *
     * @param array $metaTags
     * @return string
     */
    public function metaTags(array $metaTags = array())
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
     * @param string|null $title
     * @return string
     */
    public function title($title = null)
    { 
        if ($title) {
            $title .= ' - '.Config::get('app.name');
        } else {
            $title = Config::get('app.name');
        }

        return '<title>'.$title.'</title>';
    }

    /**
     * Renders open graph tags
     *
     * @param OpenGraph $openGraph The OpenGraph instance
     * @return string
     */
    public function openGraphTags(OpenGraph $openGraph)
    { 
        $output = $openGraph->renderTags();

        return $output;
    }

    /**
     * Returns HTML code for a table.
     * 
     * @param array $header     Array with the table header items (String-Array)
     * @param array $rows       Array with all the table rows items (Array containing String-Arrays)
     * @param array $attributes Apply these HTML attributes to the table element
     * @return string
     */
    public function table($header, $rows, $attributes)
    {
        $attrs = self::attributes($attributes);
        $code = '<table class="table table-hover" '.$attrs.'>';

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

            foreach ($row as $value) {
                if (! $value instanceof Raw) {
                    $value = e($value); // Always auto escape except value is marked as raw
                }

                $code .= '<td>'.$value.'</td>';
            }

            $code .= '</tr>';
        }

        $code .= '</table>';

        return $code;
    }

    /**
     * Returns HTML code of a "image link" - a link with an image (and maybe a text).
     * If $image has no extension, the extension will be ".png".
     * If $image has does not start with "http://" an asset link will be created.
     * 
     * @param  string  $image      The name of the link image
     * @param  string  $title      The link title
     * @param  string  $url        The link URL
     * @param  bool    $printTitle Print the title text after the image?
     * @param  array   $attributes Apply these HTML attributes to the link element
     * @return string
     */
    public function imageLink($image, $title, $url, $printTitle = false, $attributes = array())
    {
        $imageUrl = get_image_url($image);
        $image = self::image($imageUrl, $title);

        if ($printTitle) {
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
     * @param  string  $icon       The name of the icon
     * @param  string  $url        The link URL
     * @param  string  $title      The link title
     * @param  boolean $showTitle  Show the title text?
     * @param  array   $attributes Apply these HTML attributes to the table element
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
     * Returns HTML code for a button element that does not need a form but works via JavaScript.
     * It may include an icon element and a title text.
     *
     * @see Form::button()
     * 
     * @param  string $title      The button title text
     * @param  string $url        The URL the button is targeting at
     * @param  string $icon       The name of the icon. It's rendered by an icon font.
     * @param  array  $attributes Apply these HTML attributes to the button element
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
     * @param string      $url    The URL to call - usually URL::current()
     * @param string      $title  The text/ title of the link
     * @param string      $sortBy Name of the sort switch, usually the name of a model attribute
     * @param string|null $order  Current sorting order, "asc" or "desc"
     * @param string|null $search Current search term
     * @param bool|null   $active Is this switcher active? Null = auto decide
     * @return string
     */
    public function sortSwitcher($url, $title, $sortBy, $order = null, $search = null, $active = null)
    {
        if ($order === 'asc') {
            $order  = 'desc';
            $class  = 'sorting-asc';
        } else {
            $order  = 'asc';
            $class  = 'sorting-desc';
        }

        if ($active === false or (Input::get('sortby') !== $sortBy)) {
            $class = '';
        }

        $url = $url.'?sortby='.$sortBy.'&order='.$order;
        if ($search) {
            $url .= '&search='.urlencode($search);
        }

        return '<a class="sort-switcher '.$class.'" href="'.$url.'">'.$title.'</a>';
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
            $icon = self::fontIcon('check');
        } else {
            $class = 'disabled';
            $icon = self::fontIcon('times');
        }

        return '<a class="recycle-bin-button '.$class.'" href="'.$url.'">'.trans('app.recycle_bin').':&nbsp'.$icon.'</a>';
    }

    /**
     * Helper method. Just passes through the HTML code BackendNavGenerator::get() returns.
     * 
     * @return string
     */
    public function renderBackendNavigation()
    {
        $backendNavGenerator = new BackendNavGenerator();

        return $backendNavGenerator->get();
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

    /**
     * Pass the relative file name of an asset to this function, for example "css/style.css".
     * It will return something like "css/style.css?v=123456789".
     * If the cache key self::ASSET_PATH_CACHE_KEY is cleared it will create 
     * a new version number and thus browsers are forced to reload the asset.
     * 
     * @param  string $assetPath Unversioned asset path
     * @return string
     */
    public function versionedAssetPath($assetPath)
    {
        if (strpos($assetPath, '?') === false) {
            $queryChar = '?';
        } else {
            $queryChar = '&';
        }

        $key = self::ASSET_PATH_CACHE_KEY;
        if (Cache::has($key) and ! Config::get('app.debug')) {
            $version = Cache::get($key);
        } else {
            $version = time();
            Cache::forever($key, $version);
        }

        return $assetPath.$queryChar.'v='.$version;
    }

    /**
     * Removes the cache entry for asset path versioning,
     * thus forcing a refresh for the cached paths.
     * 
     * @return void
     */
    public function refreshAssetPaths()
    {
        Cache::forget(self::ASSET_PATH_CACHE_KEY);
    }

    /**
     * Returns HTML code for a font icon.
     *
     * @param string      $icon     The name of the icon
     * @param string|null $color    The color of the icon (HTML color name or code)
     * @param string|null $class    Use this parameter to add additional CSS classes
     * @param string|null $category The name of the category (solid: fas, regular: far/fa, light: fal, brands: fab)
     * @return string
     */
    public function fontIcon($icon, $color = null, $class = null, $category = null)
    {
        if ($color) {
            $color = ' style="color: '.$color.'"';
        }

        if (! $category) {
            $category = 'fas';

            // Brand icons have there own category class. They only way to find out which class
            // we have to use is to maintain a list with the names of all brand icons and then
            // to check if the current icon name is in this list.
            if (in_array($icon, $this->getBrandIconNames())) {
                $category = 'fab';
            }
        }

        return '<i class="'.$category.' fa-'.$icon.' '.$class.'"'.$color.'></i>';
    }

    /**
     * Returns an array with the names of all FontAwesome icons
     * that are in the "brand" category.
     *
     * @return string[]
     */
    public function getBrandIconNames()
    {
        $iconNames = [
            'accessible-icon',
            'accusoft',
            'adn',
            'adversal',
            'affiliatetheme',
            'algolia',
            'amazon',
            'amilia',
            'android',
            'angellist',
            'angrycreative',
            'angular',
            'app-store',
            'app-store-ios',
            'apper',
            'apple',
            'apple-pay',
            'asymmetrik',
            'audible',
            'autoprefixer',
            'avianex',
            'aviato',
            'aws',
            'bandcamp',
            'behance',
            'behance-square',
            'bimobject',
            'bitbucket',
            'bitcoin',
            'bity',
            'black-tie',
            'blackberry',
            'blogger',
            'blogger-b',
            'bluetooth',
            'bluetooth-b',
            'btc',
            'buromobelexperte',
            'buysellads',
            'cc-amex',
            'cc-apple-pay',
            'cc-diners-club',
            'cc-discover',
            'cc-jcb',
            'cc-mastercard',
            'cc-paypal',
            'cc-stripe',
            'cc-visa',
            'centercode',
            'chrome',
            'cloudscale',
            'cloudsmith',
            'cloudversify',
            'codepen',
            'codiepie',
            'connectdevelop',
            'contao',
            'cpanel',
            'creative-commons',
            'css3',
            'css3-alt',
            'cuttlefish',
            'd-and-d',
            'dashcube',
            'delicious',
            'deploydog',
            'deskpro',
            'deviantart',
            'digg',
            'digital-ocean',
            'discord',
            'discourse',
            'dochub',
            'docker',
            'draft2digital',
            'dribbble',
            'dribbble-square',
            'dropbox',
            'drupal',
            'dyalog',
            'earlybirds',
            'edge',
            'ember',
            'empire',
            'envira',
            'erlang',
            'etsy',
            'expeditedssl',
            'facebook',
            'facebook-f',
            'facebook-messenger',
            'facebook-square',
            'firefox',
            'first-order',
            'firstdraft',
            'flickr',
            'fly',
            'font-awesome',
            'font-awesome-alt',
            'font-awesome-flag',
            'fonticons',
            'fonticons-fi',
            'fort-awesome',
            'fort-awesome-alt',
            'forumbee',
            'foursquare',
            'free-code-camp',
            'freebsd',
            'get-pocket',
            'gg',
            'gg-circle',
            'git',
            'git-square',
            'github',
            'github-alt',
            'github-square',
            'gitkraken',
            'gitlab',
            'gitter',
            'glide',
            'glide-g',
            'gofore',
            'goodreads',
            'goodreads-g',
            'google',
            'google-drive',
            'google-play',
            'google-plus',
            'google-plus-g',
            'google-plus-square',
            'google-wallet',
            'gratipay',
            'grav',
            'gripfire',
            'grunt',
            'gulp',
            'hacker-news',
            'hacker-news-square',
            'hire-a-helper',
            'hooli',
            'hotjar',
            'houzz',
            'html5',
            'hubspot',
            'imdb',
            'instagram',
            'internet-explorer',
            'ioxhost',
            'itunes',
            'itunes-note',
            'jenkins',
            'joget',
            'joomla',
            'js',
            'js-square',
            'jsfiddle',
            'keycdn',
            'kickstarter',
            'kickstarter-k',
            'laravel',
            'lastfm',
            'lastfm-square',
            'leanpub',
            'less',
            'line',
            'linkedin',
            'linkedin-in',
            'linode',
            'linux',
            'lyft',
            'magento',
            'maxcdn',
            'medapps',
            'medium',
            'medium-m',
            'medrt',
            'meetup',
            'microsoft',
            'mix',
            'mixcloud',
            'mizuni',
            'modx',
            'monero',
            'napster',
            'nintendo-switch',
            'node',
            'node-js',
            'npm',
            'ns8',
            'nutritionix',
            'odnoklassniki',
            'odnoklassniki-square',
            'opencart',
            'openid',
            'opera',
            'optin-monster',
            'osi',
            'page4',
            'pagelines',
            'palfed',
            'patreon',
            'paypal',
            'periscope',
            'phabricator',
            'phoenix-framework',
            'pied-piper',
            'pied-piper-alt',
            'pied-piper-pp',
            'pinterest',
            'pinterest-p',
            'pinterest-square',
            'playstation',
            'product-hunt',
            'pushed',
            'python',
            'qq',
            'quora',
            'ravelry',
            'react',
            'rebel',
            'red-river',
            'reddit',
            'reddit-alien',
            'reddit-square',
            'rendact',
            'renren',
            'replyd',
            'resolving',
            'rocketchat',
            'rockrms',
            'safari',
            'sass',
            'schlix',
            'scribd',
            'searchengin',
            'sellcast',
            'sellsy',
            'servicestack',
            'shirtsinbulk',
            'simplybuilt',
            'sistrix',
            'skyatlas',
            'skype',
            'slack',
            'slack-hash',
            'slideshare',
            'snapchat',
            'snapchat-ghost',
            'snapchat-square',
            'soundcloud',
            'speakap',
            'spotify',
            'stack-exchange',
            'stack-overflow',
            'staylinked',
            'steam',
            'steam-square',
            'steam-symbol',
            'sticker-mule',
            'strava',
            'stripe',
            'stripe-s',
            'studiovinari',
            'stumbleupon',
            'stumbleupon-circle',
            'superpowers',
            'supple',
            'telegram',
            'telegram-plane',
            'tencent-weibo',
            'themeisle',
            'trello',
            'tripadvisor',
            'tumblr',
            'tumblr-square',
            'twitch',
            'twitter',
            'twitter-square',
            'typo3',
            'uber',
            'uikit',
            'uniregistry',
            'untappd',
            'usb',
            'ussunnah',
            'vaadin',
            'viacoin',
            'viadeo',
            'viadeo-square',
            'viber',
            'vimeo',
            'vimeo-square',
            'vimeo-v',
            'vine',
            'vk',
            'vnv',
            'vuejs',
            'weibo',
            'weixin',
            'whatsapp',
            'whatsapp-square',
            'whmcs',
            'wikipedia-w',
            'windows',
            'wordpress',
            'wordpress-simple',
            'wpbeginner',
            'wpexplorer',
            'wpforms',
            'xbox',
            'xing',
            'xing-square',
            'y-combinator',
            'yahoo',
            'yandex',
            'yandex-international',
            'yelp',
            'yoast',
            'youtube',
        ];

        return $iconNames;
    }

}