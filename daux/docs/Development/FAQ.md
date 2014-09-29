## Will You Update To Laravel 5.0?

Yes, as soon as possible once a stable release has been published. It also depends on how long vendor packages need to update to Laravel 5.0.

## How Can I Fight Spambots?

Per default users that register are activated right away. You can change this behaviour: Open `app/modules/auth/controllers/RegistrationController.php` and set `AUTO_ACTIVATE` to false. If set to false, an admin has to activate new users manually. To activate a user, switch to the admin interface, open the users module page, find the user, click on the "edit" button and set the "activated" checkbox on true.

## How Can I Add Social Logins?

Social logins are not built-in (at least not for now, this may change in a future release). Nevertheless the CMS is well prepared. You may require the Cartalyst\SentrySocial or Laravel\Socialite package to get third party authentication support. Take a look at the official docs and/orwatch this very helpful video tutorial: https://laracasts.com/series/whats-new-in-laravel-5/episodes/9