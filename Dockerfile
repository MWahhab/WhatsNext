FROM php:8.2-fpm-alpine as build

WORKDIR /var/www
COPY . /var/www

RUN apk add --no-cache bash git nodejs npm && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --optimize-autoloader && \
    npm install && \
    npm run build

COPY ./laravel-site.conf /etc/apache2/conf.d/

FROM alpine:latest

RUN adduser -S www-data -G www-data

RUN apk add --no-cache apache2 apache2-utils apache2-proxy apache2-ssl \
     php82 php82-fpm php82-cli php82-json php82-mbstring php82-opcache php82-tokenizer \
    php82-pdo php82-pdo_mysql php82-mysqli php82-ctype php82-session mysql-client curl && \
    ln -s /usr/sbin/php-fpm82 /usr/sbin/php-fpm && \
    ln -s /usr/bin/php82 /usr/bin/php

WORKDIR /var/www

COPY --from=build /var/www /var/www
COPY --from=build /etc/apache2/conf.d/ /etc/apache2/conf.d/

RUN chown -R www-data:www-data /var/www /var/www/storage /var/www/bootstrap/cache && chmod -R 755 /var/www

RUN chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Set correct permissions for public directory
#RUN chmod -R 755 /var/www/public

EXPOSE 80

CMD ["sh", "-c", "php-fpm && httpd -D FOREGROUND"]
