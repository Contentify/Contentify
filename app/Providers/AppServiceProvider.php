<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Thujohn\Rss\Rss;
use Jobs, Session, Validator, Blade, App, DB;

class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
        |--------------------------------------------------------------------------
        | Custom Validation Rules
        |--------------------------------------------------------------------------
        |
        | This is the right place for custom validators.
        |
        */

        Validator::extend('alpha_numeric_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\pM\pN\s_-]+$/u', $value);
        });

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
        Blade::directive('widget', function($expression) {
            return '<?php echo HTML::widget'.$expression.'; ?>';
        });
       
        /*
        |--------------------------------------------------------------------------
        | Jobs
        |--------------------------------------------------------------------------
        |
        | Register Jobs
        |
        */

        Jobs::addLazy('updateStreams', 'App\Modules\Streams\UpdateStreamsJob');
        Jobs::addLazy('deleteUserActivities', 'Contentify\Models\DeleteUserActivitiesJob');

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /*
         * The RSS package does not have a service provider for Laravel 5 (only for Laravel 4).
         * Therefore we have to register the rss service so the facade can use it.
         */
        $this->app['rss'] = $this->app->share(function($app)
        {
            return new Rss;
        });
    }

}
