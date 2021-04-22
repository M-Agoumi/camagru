#!/bin/bash

FILE="var/server-app.pid"

if [ -f $FILE ];then
    _pid=$(cat $FILE);
    _ps=$(ps -p "$_pid" | wc -l)
    if [ "$_ps" = 2 ]; then
      ss -tnlp | grep "$_pid" | cut -d ":" -f2 | cut -d " " -f1
      #ss -tnlp | grep "$_pid" | sed "s/$_pid //" | tr -d ' ' | cut -b 22-25
    else
      echo "0";
    fi
else
    echo "0"
fi