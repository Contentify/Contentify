# Server Requirements

* PHP 5.4.0
* MCrypt PHP Extension
* FileInfo PHP Extension

You will also need a MySQL Database and it's recommended to activate Apache's mod_rewrite module (or Nginx' HttpRewriteModule). To activate mod_rewrite on a Debian destribution, execute `sudo a2enmod rewrite`. Usually you also have to allow the `.htaccess` file to override standard website configs.

We strongly recommend to use webspace that you can configure. You should be able to create vhosts at least. And there is a command-line interface called [Artisan](http://laravel.com/docs/artisan). You should ensure you can run it. With Artisan you are ablte to [activate maintenance mode](http://laravel.com/docs/configuration#maintenance-mode).

We have added a simple script named `tester.php` that checks the requirements mentioned before. We recommend to run it once before starting the actual installer, because the installer depends on the CMS and therefore won't even start if the requirements are not fulfilled.

# Hosting

If you do not have a hosting service so far or it does not meet the requirements listed above we suggest to give [DigitalOcean](http://www.digitalocean.com) a try. They offer an SSD server with 512 MB RAM and 20 GB disk space for only 5 USD per month.

* Create a new droplet with Ubuntu. Choose to install the LAMP (Linux, Apache, MySQL and PHP) stack.
* Connect to your server using an SSH client (e. g. [PuTTY](http://www.putty.org) for Windows)
* Activate the mod_rewrite module: `sudo a2enmod rewrite`
* Open `/etc/apache2/sites-available/000-default.conf` and change line 12 `DocumentRoot /var/www/html` to `DocumentRoot /var/www/html/public`. In the next line paste this:

```
<Directory "/var/www/html/public/">
    Options Indexes FollowSymLinks MultiViews
    AllowOverride All
    Order allow,deny
    allow from all
</Directory>
```

Ofcourse you can change the name of the `html` directory to something more meaningful.
* Install phpMyAdmin: `sudo apt-get install phpmyadmin` It will ask you to tell it the MySQL password. You find it in `/etc/motd.tail`
* Open `/etc/apache2/apache2.conf` and add `Include /etc/phpmyadmin/apache.conf`
* Install the PHP mcrypt extension: `sudo php5enmod mcrypt`
* Restart Apache: `sudo service apache2 restart`
* Open phpMyAdmin (URL: `http://<ip>/phpmyadmin`, username: root) and create a new database. Name it `contentify`.

# Install Missing PHP Extensions

If PHP extensions are missing, you have to install them. The CMS cannot run without them being installed. Unfortunately installing PHP extesion can be exhausting. Therefore here are some hints.

The first step on your Windows environment is to open `php\php.ini` (if you are using XAMPP it's `<xammp>\php\php.ini`) and to search for the name of the extension like so: `;extension=php_fileinfo.dll` If you find this line remove the semicolon to activate the extension. If you don't find it, you need to download the extension first and then add it to the `php.ini`. Here is a guide for MCrypt: [Link](http://www.myoddweb.com/2010/11/18/install-mcrypt-for-php-on-windows/)

On Linux-based environments it depends on your distribution. On Ubuntu try `sudo php5enmod mcrypt` to install MCrypt.

# Get Contentify

Download the Contentify core files from one of the official sources. Visit our website [Contentify.org](http://contentify.org/) to get the files. Store them inside a folder so that only the subfolder `public` is accessible from the web. If only `public` is accessible from outside all other folders are protected from direct access. When on Apache you may want to [create a Virtual Host](http://laravel-recipes.com/recipes/25) for your project to achieve this. The aim is to have a URL like `http://localhost/contentify/` instead of `http://localhost/contentify/public/`. For testing or developing purposes it's okay to put the whole CMS folder inside the public web folder. But remember, this is not meant for production stage!

# Configuration

Config files live in `app/config`. Important config files are:

* *app.php*: Application settings such as title and encryption key
* *database.php*: Database settings such as connection setup. It's recommended to set `utf8_unicode_ci` as collation when you create the database. `utf8_general_ci` will work but [sorting will be inaccurate](http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci#766996).
* *mail.php*: Email settings such as name and address for all e-mails that are sent by your application.

# Installation

* (Download Contentify and configurate it)
* Set CHMOD 777 to these directories and their sub directories: `<contentify>/storage`, `<contentify>/public/uploads` and `<contentify>/public/rss`
* Run the installer. Example call: `http://localhost/contentify/install`

The official Laravel docs have a [chapter covering the installation](http://laravel.com/docs/installation).

# Something Is Going Wrong?

Installing Laravel can be a little tricky. If you experience problems take a look at the [Troubleshooting](Troubleshooting) chapter. If the problem isn't covered don't hesistate to contact us on [GitHub](https//github.com/Contentify/Contentify/issues).