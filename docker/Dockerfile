FROM composer:1 as build-stage

COPY ./ /app
RUN composer install --no-dev

FROM php:7.3-apache

WORKDIR /app

COPY --from=build-stage /app /app
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN mkdir -p /app/cache && \
	chown -R www-data:www-data /app && \
	a2enmod rewrite cache cache_disk
	
CMD service apache-htcacheclean start && apache2-foreground
