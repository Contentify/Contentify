This chapter deals with solutions for common errors and issues.

## Route Not Found

If a NotFoundHttpException is thrown please check that there is no unkown class and you did not miss to update the autoloader. The `php composer.phar dumpautoload` console command refreshes the autoloading classmap.

## Form Field Value Not Saved

If the value of a form field is not saved check if the name of the field is listed in the `fillable` array of the model.