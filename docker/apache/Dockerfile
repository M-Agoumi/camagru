	FROM ubuntu:20.04

## set our build arguments
ARG SMTP
ARG SMTP_POST
ARG SMTP_USERNAME
ARG SMTP_PASSWORD
ARG SMTP_MAIL

RUN echo hello
## set default timezone for zdate so it won't wait for input from user
ENV TZ=Africa/Casablanca
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

## some args for the permissions problems
# ARG uid=10000
# ARG user=magoumi

## install required packages for our server
RUN apt install &&  apt update && apt-get upgrade -y && apt-get install -y  git curl libpng-dev libonig-dev libxml2-dev  zip unzip jq wget software-properties-common

## install vim to help when debugging
RUN apt install -y vim

## some clean up
RUN apt clean && rm -rf /var/lib/apt/lists/*

## reupdate our repositories
RUN apt update -y && apt upgrade -y

## install php
RUN add-apt-repository ppa:ondrej/php -y && apt update
RUN apt install -y php8.1 php8.1-common php8.1-fpm php8.1-mysql php8.1-gmp php8.1-xml php8.1-xmlrpc php8.1-curl php8.1-mbstring php8.1-gd php8.1-dev php8.1-imap php8.1-opcache php8.1-readline php8.1-soap php8.1-zip php8.1-intl php8.1-cli libapache2-mod-php8.1

## install apache
RUN apt-get install -y apache2  libapache2-mod-php

## install postfix and config it
RUN rm /bin/sh && ln -s /bin/bash /bin/sh
RUN debconf-set-selections <<< "postfix postfix/mailname string admin@camagru.io"
RUN debconf-set-selections <<< "postfix postfix/main_mailer_type string 'Internet Site'"
RUN apt-get install -y postfix mailutils
RUN echo "${SMTP} ${SMTP_USERNAME}:${SMTP_PASSWORD}" > /etc/postfix/sasl_passwd
RUN chmod 600 /etc/postfix/sasl_passwd
RUN postmap /etc/postfix/sasl_passwd

## copy our config files. context project root
COPY ./docker/apache/apache2/apache2.conf /etc/apache2/
COPY ./docker/apache/sites-available/000-default.conf /etc/apache2/sites-available/
COPY ./docker/apache/php/php.ini /etc/php/8.1/apache2/
COPY ./docker/apache/postfix/main.cf /etc/postfix/

## enable rewrite mod for our mvc structure
RUN a2enmod rewrite

## copy an instance of the app for now
COPY ./ /var/www/camagru

## apply migration of our database
RUN cd /var/www/camagru && bin/console migrate:migrate

## fix permissions
# RUN useradd -G www-data,root -u $uid -d /home/$user $user
# RUN chown -R $user:$user /var/www/camagru

## expose our apache port 80
EXPOSE 80
EXPOSE 587

## switch to our user
## USER $user

## restart postfix with the new settings
RUN service postfix restart

## run our apache server
CMD service apache2 start && tail -F /var/log/apache2/error.log
