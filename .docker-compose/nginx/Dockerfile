FROM nginx:1.17-alpine

ARG user
ARG uid

# Copy nginx goodfood conf
COPY ./.docker-compose/nginx/goodfood.conf /etc/nginx/conf.d/default.conf
COPY ./.docker-compose/nginx/oauthprovider.conf /etc/nginx/conf.d/oauthprovider.conf
COPY ./.docker-compose/nginx/cdn.conf /etc/nginx/conf.d/cdn.conf

# Configure App Directories
RUN adduser -G www-data -G root -u $uid -H -D $user

WORKDIR /var/www

RUN chown -R www:www-data /var/www
RUN chmod -R ug+rw /var/www 


