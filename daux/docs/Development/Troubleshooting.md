This chapter deals with solutions for common errors and issues.

## Route Not Found

If a NotFoundHttpException is thrown please check that there is no unkown class and you did not miss to update the autoloader. The `php composer.phar dumpautoload` console command refreshes the autoloading classmap.

## Form Field Value Not Saved

If the value of a form field is not saved check if the name of the field is listed in the `fillable` array of the model.

## Installation: Execution time exceeded

If PHP cancels the database setup with a "Maximum execution time of 30 seconds exceeded" exception this means creating the database is too time-consuming. Unfortunately creating the database is **very** time-consuming. If it's running on systems with little computing power the script can exceed the execution time limit. There are two solutions:

* Try to increase the excution time: Open the `php.ini` config file and set [max_execution_time](http://php.net/manual/de/info.configuration.php#ini.max-execution-time) to an appropriate value (or simply set it to 0).
* Contact our support team. They will send you an SQL dump file with a database that's ready for use. Import this file, for example with [PHPMyAdmin](http://www.phpmyadmin.net/).