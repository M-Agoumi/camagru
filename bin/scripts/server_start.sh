#!/bin/bash
if [ -z "$1" ]
  then
    php -S localhost:8000 -t public >> runtime/logs/server.log 2>&1 &
    _pid=$!
    echo "$_pid" > var/server-app.pid
  else
    php -S localhost:"$1" -t public >> runtime/logs/server.log 2>&1 &
    _pid=$!
    echo "$_pid" > var/server-app.pid
fi