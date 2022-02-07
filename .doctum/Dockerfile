# FROM php:8.1-cli as builder
FROM php:8.1-alpine as builder

# Prepare Container
RUN apk update && \
    apk --no-cache add curl && \
    mkdir -p /code && \
    mkdir -p /opt/doctum/build && \
    mkdir -p /opt/doctum/cache

# Copy codes
COPY ./app /code

# Install Doctum
RUN curl -O https://doctum.long-term.support/releases/latest/doctum.phar && \
    chmod +x doctum.phar && \
    mv doctum.phar /usr/local/bin/doctum

# Copy doctum conf
COPY /.doctum/doctum.conf.php /opt/doctum/doctum.conf.php

# Generate Doctum Doc API
RUN /usr/local/bin/doctum update /opt/doctum/doctum.conf.php -v

FROM nginx:1.17-alpine

COPY --from=builder /opt/doctum/build /var/www/

COPY /.docker-compose/nginx/doctum.conf /etc/nginx/conf.d/default.conf