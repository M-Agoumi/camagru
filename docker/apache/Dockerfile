FROM ubuntu

## set default timezone for zdate so it won't wait for input from user
ENV TZ=Africa/Casablanca
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

## some args for the permissions problems
# ARG uid=10000
# ARG user=sqli

## install required packages for our server
RUN apt install &&  apt update && apt install -y  git curl libpng-dev libonig-dev libxml2-dev  zip unzip

## install vim to help when debugging
RUN apt install -y vim

## some clean up
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

## reupdate our repositories
RUN apt update -y && apt upgrade -y

## install php
RUN apt install -y php-common php-mysql php-cli

## install apache
RUN apt install -y apache2  libapache2-mod-php

## install postfix and config it
RUN rm /bin/sh && ln -s /bin/bash /bin/sh
RUN debconf-set-selections <<< "postfix postfix/mailname string admin@camagru.io"
RUN debconf-set-selections <<< "postfix postfix/main_mailer_type string 'Internet Site'"
RUN apt install -y postfix mailutils
RUN echo "${smtp} ${username}:${username}" > /etc/postfix/sasl_passwd
RUN chmod 600 /etc/postfix/sasl_passwd
RUN postmap /etc/postfix/sasl_passwd
RUN service postfix restart

## copy our config files. context project root
COPY ./docker/apache/apache2/apache2.conf /etc/apache2/
COPY ./docker/apache/sites-available/000-default.conf /etc/apache2/sites-available/
COPY ./docker/apache/php/php.ini /etc/php/7.4/apache2/
COPY ./docker/apache/postfix/main.cf /etc/postfix/

## restart postfix with the new settings
RUN service postfix restart

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
EXPOSE 587

## switch to our user
## USER $user

## run our apache server
CMD service apache2 start && tail -F /var/log/apache2/error.log
