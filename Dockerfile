FROM php:7-apache

COPY . /var/www/html/

RUN apt-get update && \
	apt-get install -y zlib1g-dev libzip-dev libpng-dev libjpeg-dev libjpeg62-turbo-dev libgd-dev libfreetype6-dev && \
	docker-php-ext-install bcmath zip mysqli pdo pdo_mysql tokenizer && \
	docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
	docker-php-ext-install -j$(nproc) gd && \
	a2enmode rewrite

RUN chown -R www-data:www-data /var/www/html/ && \
	chmod -R 777 storage bootstrap/cache public && \
	sed -i 's/\/var\/www\/html/\/var\/www\/html\/public/' /etc/apache2/sites-available/000-default.conf
WORKDIR /var/www/html/
USER www-data
RUN php composer.phar install
USER root