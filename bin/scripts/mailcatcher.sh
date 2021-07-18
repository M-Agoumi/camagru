#!/bin/bash
if [ -z "$1" ];then
    echo "sendmail_path = /usr/bin/env $(which catchmail) -f admin@admin.com" | sudo tee /etc/php/7.4/mods-available/mailcatcher.ini
    sudo phpenmod mailcatcher
else
    echo "sendmail_path = /usr/bin/env $(which catchmail) -f $1" | sudo tee /etc/php/7.4/mods-available/mailcatcher.ini
    sudo phpenmod mailcatcher
fi
