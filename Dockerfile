FROM php:8.2-apache

# Install dependencies and extensions necessary for Laravel and PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    nodejs \
    npm \
    && docker-php-ext-install pdo_pgsql pgsql zip gd

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# Change Apache document root to Laravel's public directory
RUN sed -i -e 's/html/html\/public/g' /etc/apache2/sites-available/000-default.conf

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Since this is a dev environment, we assume the code is volume mounted.
# No need to copy code or run composer install in the Dockerfile itself,
# except for the initial setup.

EXPOSE 80
