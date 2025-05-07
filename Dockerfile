
FROM webdevops/php-nginx:8.2-alpine

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy nginx config if you have a custom one
# COPY ./nginx.conf /etc/nginx/nginx.conf

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions (make sure storage and bootstrap/cache are writable)
RUN chown -R application:application storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Environment variables as needed
ENV APP_ENV=production
ENV APP_DEBUG=false

# Expose web port
EXPOSE 80

# Entrypoint and command are set by the base image
