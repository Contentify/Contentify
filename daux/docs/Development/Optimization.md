There are plenty of options to optimize the speed of your website. Call the diag admin page (`/diag`) to see all of them at at a glance.

## Optimized Class loader

Use `php composer.phar dumpautoload -o` to let Composer optimize its class loader or execute the `php artisan optimize` command to generate an even more optimized class loader. Note that Artisan checks if debug mode is on. Disable debug mode before you start the command or call `php artisan optimize --force` instead! Also note that Artisan performs `dumpautoload -o` as well. 

Since both commands need some time to execute they are they may be too cumbersome for development. But always use them when you deploy the website on you production server. 

# OPcache

OPcache "improves PHP performance by storing precompiled script bytecode in shared memory". OPcache is bundled with PHP 5.5 but also available for older version such as 5.4. Install the PHP OPcache extension and activate it. Read more on the [manual](http://php.net/manual/de/book.opcache.php).