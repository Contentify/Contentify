This chapter deals with solutions for common errors and issues.

## Route Not Found

If a NotFoundHttpException is thrown please check that there is no unkown class and you just missed to update the autoloader. The `php composer.phar dumpautoload` console command refreshes the autoloading classmap.