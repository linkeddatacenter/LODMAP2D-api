FROM composer:1

COPY ./ /app
RUN composer install --no-dev

EXPOSE 8000
CMD php -S "0.0.0.0:8000" index.php
