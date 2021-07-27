FROM ubuntu

## set default timezone for zdate so it won't wait for input from user
ENV TZ=Africa/Casablanca
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

## some args for the permissions problems
# ARG uid=10000
# ARG user=sqli

## install required packages for our server
RUN apt install &&  apt update && apt install -y  git curl libpng-dev libonig-dev libxml2-dev  zip unzip

## some clean up
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

## reupdate our repositories
RUN apt update -y && apt upgrade -y

## install php
RUN apt install -y php-common php-mysql php-cli

## install apache
RUN apt install -y apache2  libapache2-mod-php

## copy our config files. context project root
COPY ./docker/apache/apache2/apache2.conf /etc/apache2/
COPY ./docker/apache/sites-available/000-default.conf /etc/apache2/sites-available/
COPY ./docker/apache/php/php.ini /etc/php/7.4/apache2/

## enable rewrite mod for our mvc structure
RUN a2enmod rewrite

## copy an instance of the app for now
COPY ./ /var/www/camagru

## apply migration of our database
# RUN cd /var/www/camagru && bin/console migrate:migrate

## fix permissions
# RUN useradd -G www-data,root -u $uid -d /home/$user $user
# RUN chown -R $user:$user /var/www/camagru

## expose our apache port 80
EXPOSE 80

## switch to our user
## USER $user

## run our apache server
CMD service apache2 start && tail -F /var/log/apache2/error.log