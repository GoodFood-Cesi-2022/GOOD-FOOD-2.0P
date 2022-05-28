# Worker
FROM php:8.1-fpm

ARG user
ARG uid

# Prepare container
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libjpeg-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP Extensions
RUN docker-php-ext-configure gd --enable-gd --with-jpeg
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
    mkdir -p /var/www && \
    chown -R $user:$user /var/www

USER $user

# Install Node
SHELL ["/bin/bash", "--login", "-i", "-c"]
RUN touch /home/${user}/.bashrc \
    && curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash \
    && source /home/${user}/.bashrc \
    && nvm install 16.14.0 
SHELL ["/bin/bash", "--login", "-c"]


WORKDIR /var/www
