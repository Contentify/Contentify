This chapter deals with solutions for common errors and issues.

## Route Not Found

If a NotFoundHttpException is thrown please check that there is no unkown class and you did not miss to update the autoloader. The `php composer.phar dumpautoload` console command refreshes the autoloading classmap.

## Form Field Value Not Saved

If the value of a form field is not saved check if the name of the field is listed in the `fillable` array of the model.

## Installation: Execution time exceeded

If PHP cancels the database setup with a "Maximum execution time of 30 seconds exceeded" exception this means creating the database is too time-consuming. Unfortunately creating the database is **very** time-consuming. If it's running on systems with little computing power the script can exceed the execution time limit. There are two solutions:

* Try to increase the excution time: Open the `php.ini` config file and set [max_execution_time](http://php.net/manual/de/info.configuration.php#ini.max-execution-time) to an appropriate value (or simply set it to 0).
* Contact our support team. They will send you an SQL dump file with a database that's ready for use. Import this file, for example with [PHPMyAdmin](http://www.phpmyadmin.net/).

# Alpha I

## Bad Performance

Laravel 4.2 fails in handling a huge amount of routes. Since Contentify has roughly 300 routes performance is weak. Execute the `php artisan routes` command in your console to witness the madness. Laravel 5 offers route caching which we expect to be a solution for this problem. From the docs: "This is primarily useful on applications with 100+ routes and typically makes this portion of your code 50x faster. Literally!" (http://laravel.com/docs/)

Right now module route loading needs at least several hundreds of milliseconds.