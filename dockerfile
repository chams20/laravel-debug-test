FROM node:lts as node
FROM php:8.1-apache

RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y git zip curl \
    && apt-get clean

RUN docker-php-ext-install pdo_mysql

# change the document root to /var/www/html/public
RUN sed -i -e "s/html/html\/public/g" \
    /etc/apache2/sites-enabled/000-default.conf

RUN a2enmod rewrite

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

# Get composer
COPY --from=composer:2.4.1 /usr/bin/composer /usr/bin/composer

COPY ./bootstrap /var/www/html/bootstrap
COPY ./storage /var/www/html/storage

ENV USER www-data
RUN chown ${USER}:${USER} -R /var/www/html/bootstrap
RUN chown ${USER}:${USER} -R /var/www/html/storage

# Get Node and yarn
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node /usr/local/bin/node /usr/local/bin/node
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

RUN npm install -g yarn
RUN npm config set cache /tmp --global

EXPOSE 80

CMD [ "apache2ctl", "-D", "FOREGROUND" ]
