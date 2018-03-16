FROM php:5-apache

COPY . /var/www/html/

RUN apt-get update && \
	apt-get install -y zlib1g-dev && \
	docker-php-ext-install bcmath zip mysql pdo pdo_mysql tokenizer

WORKDIR /var/www/html/

RUN php composer.phar install && \
	a2enmod rewrite && \
	chmod -R 777 storage bootstrap/cache public
