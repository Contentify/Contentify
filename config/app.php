<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | The name (title) of this web application.
    |
    | @deprecated Will be stored in the database, not here in this file
    |
    */

    'name' => env('APP_NAME', 'Contentify'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */
    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', true),

    /*
    |--------------------------------------------------------------------------
    | CMS Version
    |--------------------------------------------------------------------------
    |
    | The CMS version.
    |
    */

    'version' => '2.6',

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost/contentify'),

    /*
    |--------------------------------------------------------------------------
    | Application Pagination Settings
    |--------------------------------------------------------------------------
    |
    | Items per page, etc.
    |
    */

    'frontItemsPerPage' => 15,
    'adminItemsPerPage' => 15,

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you should specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    | Example value for CET: 'Europe/Berlin'
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Currency
    |--------------------------------------------------------------------------
    |
    | Define the default currency of this website.
    |
    */

    'currency' => 'Euro',

    'currency_symbol' => '€',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY', '12345678901234567890123456789012'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog", "errorlog"
    |
    */

    'log' => env('APP_LOG', 'single'),

    'log_level' => env('APP_LOG_LEVEL', 'debug'),

    /*
    |--------------------------------------------------------------------------
    | Google ReCAPTCHA Secret
    |--------------------------------------------------------------------------
    |
    | If you use Google ReCAPTCHA to protect your website from bots,
    | this is the place to enter the server secret.
    |
    */

    'recaptcha_secret' => '',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        //Illuminate\Translation\TranslationServiceProvider::class, // Replaced by custom translation service provider
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */
        Laravel\Tinker\TinkerServiceProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        //App\Providers\AuthServiceProvider::class // We do not use Laravel's authentication
        //App\Providers\BroadcastServiceProvider::class, // Deactivated per Laravel's default
        App\Providers\ConfigServiceProvider::class, // Custom service provider
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

        /*
         * CMS service providers...
         */
        Contentify\ServiceProviders\HtmlServiceProvider::class,
        Contentify\ServiceProviders\TranslationServiceProvider::class,
        Contentify\ServiceProviders\HoverServiceProvider::class,
        Contentify\ServiceProviders\ModuleRouteServiceProvider::class,
        Contentify\ServiceProviders\ContentFilterServiceProvider::class,
        Contentify\ServiceProviders\CaptchaServiceProvider::class,
        Contentify\ServiceProviders\CommentsServiceProvider::class,
        Contentify\ServiceProviders\RatingsServiceProvider::class,
        Contentify\ServiceProviders\UserActivitiesServiceProvider::class,
        Contentify\ServiceProviders\ModelHandlerServiceProvider::class,

        /*
         * Vendor service providers...
         */
        ChrisKonnertz\Jobs\Integration\JobsServiceProvider::class,
        Caffeinated\Modules\ModulesServiceProvider::class,
        Cartalyst\Sentinel\Laravel\SentinelServiceProvider::class,
        Intervention\Image\ImageServiceProvider::class,
        Invisnik\LaravelSteamAuth\SteamServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App'                   => Illuminate\Support\Facades\App::class,
        'Artisan'               => Illuminate\Support\Facades\Artisan::class,
        'Auth'                  => Illuminate\Support\Facades\Auth::class,
        'Blade'                 => Illuminate\Support\Facades\Blade::class,
        'Broadcast'             => Illuminate\Support\Facades\Broadcast::class,
        'Bus'                   => Illuminate\Support\Facades\Bus::class,
        'Cache'                 => Illuminate\Support\Facades\Cache::class,
        //'Config                => Illuminate\Support\Facades\Config::class, // Replaced by custom config facade
        'Cookie'                => Illuminate\Support\Facades\Cookie::class,
        'Crypt'                 => Illuminate\Support\Facades\Crypt::class,
        'DB'                    => Illuminate\Support\Facades\DB::class,
        'Eloquent'              => Illuminate\Database\Eloquent\Model::class,
        'Event'                 => Illuminate\Support\Facades\Event::class,
        'File'                  => Illuminate\Support\Facades\File::class,
        'Gate'                  => Illuminate\Support\Facades\Gate::class,
        'Hash'                  => Illuminate\Support\Facades\Hash::class,
        'Input'                 => Illuminate\Support\Facades\Input::class, // "Unofficial" alias since Laravel 5.2
        'Lang'                  => Illuminate\Support\Facades\Lang::class,
        'Log'                   => Illuminate\Support\Facades\Log::class,
        'Mail'                  => Illuminate\Support\Facades\Mail::class,
        'Notification'          => Illuminate\Support\Facades\Notification::class,
        'Password'              => Illuminate\Support\Facades\Password::class,
        'Queue'                 => Illuminate\Support\Facades\Queue::class,
        'Redirect'              => Illuminate\Support\Facades\Redirect::class,
        'Redis'                 => Illuminate\Support\Facades\Redis::class,
        'Request'               => Illuminate\Support\Facades\Request::class,
        'Response'              => Illuminate\Support\Facades\Response::class,
        'Route'                 => Illuminate\Support\Facades\Route::class,
        'Schema'                => Illuminate\Support\Facades\Schema::class,
        'Session'               => Illuminate\Support\Facades\Session::class,
        'SoftDeletingTrait'     => Illuminate\Database\Eloquent\SoftDeletes::class,
        'Storage'               => Illuminate\Support\Facades\Storage::class,
        'Str'                   => Illuminate\Support\Str::class, // "Unofficial" alias since Laravel 5.0
        'URL'                   => Illuminate\Support\Facades\URL::class,
        'Validator'             => Illuminate\Support\Facades\Validator::class,
        'View'                  => Illuminate\Support\Facades\View::class,

        'Controller'            => App\Http\Controllers\Controller::class,
        'Form'                  => Collective\Html\FormFacade::class,
        'HTML'                  => Collective\Html\HtmlFacade::class,

        /*
         * CMS classes:
         */ 
        'FormGenerator'         => Contentify\FormGenerator::class,
        'ModuleInstaller'       => Contentify\ModuleInstaller::class,
        'MsgException'          => Contentify\MsgException::class,
        'Config'                => Contentify\Config::class,
        'Paginator'             => Contentify\LengthAwarePaginator::class,
        'ModuleRoute'           => Contentify\Facades\ModuleRoute::class,
        'Carbon'                => Contentify\Carbon::class,

        'DateAccessorTrait'     => Contentify\Traits\DateAccessorTrait::class,
        'ModelHandlerTrait'     => Contentify\Traits\ModelHandlerTrait::class,

        'InstallController'     => Contentify\Controllers\InstallController::class,
        'BaseController'        => Contentify\Controllers\BaseController::class,
        'FrontController'       => Contentify\Controllers\FrontController::class,
        'BackController'        => Contentify\Controllers\BackController::class,
        'ConfigController'      => Contentify\Controllers\ConfigController::class,
        'Widget'                => Contentify\Controllers\Widget::class,

        'BaseModel'             => Contentify\Models\BaseModel::class,
        'Comment'               => Contentify\Models\Comment::class,
        'StiModel'              => Contentify\Models\StiModel::class,
        'User'                  => Contentify\Models\User::class,
        'UserActivity'          => Contentify\Models\UserActivity::class,
        'ConfigBag'             => Contentify\Models\ConfigBag::class,
        'Raw'                   => Contentify\Raw::class,

        /*
         * Vendor classes:
         */
        'OpenGraph'             => ChrisKonnertz\OpenGraph\OpenGraph::class,
        'BBCode'                => ChrisKonnertz\BBCode\BBCode::class,
        'Jobs'                  => ChrisKonnertz\Jobs\Integration\JobsFacade::class,
        'AbstractJob'           => ChrisKonnertz\Jobs\AbstractJob::class,
        'Sentinel'              => Cartalyst\Sentinel\Laravel\Facades\Sentinel::class,
        'Activation'            => Cartalyst\Sentinel\Laravel\Facades\Activation::class,
        'Reminder'              => Cartalyst\Sentinel\Laravel\Facades\Reminder::class,
        'Rss'                   => Thujohn\Rss\RssFacade::class,
        'InterImage'            => Intervention\Image\Facades\Image::class,
        'ValidatingTrait'       => Watson\Validating\ValidatingTrait::class,
        'Module'                => Caffeinated\Modules\Facades\Module::class,
    ],

];
