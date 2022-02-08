FROM node:16-alpine as builder

RUN npm i -g redoc-cli

WORKDIR /redoc

COPY /.openapi/api.json /redoc/

RUN redoc-cli bundle api.json 

FROM nginx:1.17-alpine

COPY --from=builder /redoc/ /var/www

COPY /.docker-compose/nginx/openapi.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www



