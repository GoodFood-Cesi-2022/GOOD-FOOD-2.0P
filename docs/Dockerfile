# Builder for doctum
FROM php:8.1-alpine as doctum-builder

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

# Builder for redoc an openapi viewer
FROM node:16-alpine as redoc-builder

RUN npm i -g redoc-cli

WORKDIR /redoc

COPY /.openapi/api.json /redoc/

RUN redoc-cli bundle api.json 

# Builder for Mkdocs
# Regroup all docs in the folder /docs
FROM python:alpine as mkdocs-builder

RUN pip install mkdocs
RUN pip install mkdocs-material

RUN mkdir /mkdocs
COPY /.doc /mkdocs/
RUN mkdir -p /mkdocks/redoc && \
    mkidr -p /mkdocks/doctum


WORKDIR /mkdocs

COPY --from=doctum-builder /opt/doctum/build /mkdocs/docs/doctum/
COPY --from=redoc-builder /redoc/ /mkdocs/docs/redoc/

RUN mkdocs build

