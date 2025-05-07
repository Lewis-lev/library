FROM webdevops/php-nginx:8.2-alpine

WORKDIR /var/www/html

COPY . .

# Set the web root for nginx and php-fpm in this image
ENV WEB_DOCUMENT_ROOT=/var/www/html/public

# If desired, copy your custom nginx config:
# COPY ./nginx.conf /opt/docker/etc/nginx/vhost.conf

RUN composer install --no-dev --optimize-autoloader

RUN chown -R application:application storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

ENV APP_ENV=production
ENV APP_DEBUG=false

EXPOSE 80
