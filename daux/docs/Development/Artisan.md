Artisan is Laravel's command line tool (CLI). Open the console (`cmd.exe` under Windows), navigate to the application root directory and type in commands.

## List Of Commands

Type in `php artisan` to display a list of all commands that are available.

## Maintenance

Type in `php artisan down` to enable maintenance mode. Type in `php artisan up` to bring the application online again.

## Routes

Type in `php artisan route` or `php artisan routes` to see all routes.

## Generate Forms

Type in `php artisan generate:form <name> <table>` to generate a form template from a database table.

## Execute Jobs

Type in `php artisan route` to execute those jobs of the pool that do not need a cool down. `Jobs` is a PHP package that deals with job execution. Normally a cron job calls this command to trigger the execution of these jobs. One example for a job is the job that updates information about the livestreams that are managed by the `Streams` module.