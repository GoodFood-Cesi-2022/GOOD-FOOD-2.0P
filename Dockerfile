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
    chown -R $user:$user /home/$user

WORKDIR /var/www

USER $user