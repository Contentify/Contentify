<?php namespace App\Providers;

use Config;
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

        /*
         * Only allows alpha numeric characters and spaces
         */
        Validator::extend('alpha_numeric_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\pM\pN\s_-]+$/u', $value);
        });

        /*
         * Ensures a valid(!) email does not use a restricted domain (=domain blacklist)
         */
        \Validator::extend('email_domain_allowed', function($attribute, $value, $parameters, $validator) {
            $forbiddenDomainsString = str_replace(' ', '', Config::get('app.forbidden_email_domains'));
            $forbiddenDomains = explode(',', $forbiddenDomainsString);
            return ! in_array(explode('@', $value)[1], $forbiddenDomains);
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
         * Helper. Renders a widget. @see \Contentify\Controllers\Widget
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
