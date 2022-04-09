FROM node:16.14-alpine3.14 AS frontbuilder

WORKDIR /var/www
COPY . /var/www/
RUN npm install && \
    npm run prod


# Worker
FROM php:8.1-fpm

ARG user
ARG uid

# Prepare container
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP Extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Install Redis PHP Extension
RUN pecl install -o -f redis && \
    pecl install -o -f xdebug && \
    rm -rf /tmp/pear && \
    docker-php-ext-enable redis xdebug

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure App Directories
RUN useradd -G www-data,root -u $uid -d /home/$user $user

RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user && \
    rm -rf /var/www/html

WORKDIR /var/www
COPY . /var/www/

# Copy builded dependencies
COPY --from=frontbuilder /var/www/node_modules /var/www/node_modules
COPY --from=frontbuilder /var/www/public /var/www/public

# Change Owner 
RUN chown -R $user:$user /var/www

USER $user

# Install php composer dependencies
RUN touch .env && \
    composer install

