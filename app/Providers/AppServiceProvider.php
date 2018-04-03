<?php namespace App\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;
use Jobs;
use Thujohn\Rss\Rss;
use Validator;

class AppServiceProvider extends ServiceProvider
{

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
         * Helper. Renders a widget. @see Widget
         */
        Blade::directive('widget', function($expression) {
            return '<?php echo HTML::widget('.$expression.'); ?>';
        });
       
        /*
        |--------------------------------------------------------------------------
        | Jobs
        |--------------------------------------------------------------------------
        |
        | Register Jobs
        |
        */

        Jobs::addLazy('updateStreams', \App\Modules\Streams\Api\UpdateStreamsJob::class);
        Jobs::addLazy('deleteUserActivities', \Contentify\Jobs\DeleteUserActivitiesJob::class);
        Jobs::addLazy('backupDatabase', \Contentify\Jobs\BackupDatabaseJob::class);

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
         * Therefore we have to register the RSS service so the facade can use it.
         */
        $this->app->singleton('rss', function () {
            return new Rss;
        });
    }

}
