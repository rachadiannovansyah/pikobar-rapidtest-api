FROM jabardigitalservice/phpfpm-nginx

# Switch to root user
USER root

# Install additional packages
RUN apk --no-cache add php-pdo_mysql php-mysqli php-gd php-iconv php-bcmath php-gmp php-zip

# Copy configurations
COPY ./docker/nginx.conf /etc/nginx/nginx.conf

# Switch to use a non-root user from here on
USER nobody

# Copy sources
COPY --chown=nobody . /var/www/html
COPY --chown=nobody .env-example /var/www/html/.env

# Install composer from the official image
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Run composer install to install the dependencies
RUN composer install --no-cache --prefer-dist --optimize-autoloader --no-interaction --no-progress

RUN cd /var/www/html && \
    php artisan optimize && \
    php artisan route:cache
    # php artisan config:cache
