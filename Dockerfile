FROM richarvey/nginx-php-fpm:1.7.2

COPY . /var/www/html

COPY ./nginx.conf /etc/nginx/sites-available/default.conf

# Image config
ENV WEBROOT /public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

CMD ["/start.sh"]
