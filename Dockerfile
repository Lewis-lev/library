FROM richarvey/nginx-php-fpm:1.7.2

COPY . /var/www/html
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

# Laravel ENV
ENV WEBROOT /public
ENV SKIP_COMPOSER 0
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel specific ENV
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

CMD ["/start.sh"]
