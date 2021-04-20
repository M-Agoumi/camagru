#!/bin/bash

FILE="var/server-app.pid"
if [ -f $FILE ];then
    _pid=$(cat $FILE);
    echo "stopping running server.."
    kill "$_pid"
    rm $FILE
else
    echo "$FILE No server Is running"
fi