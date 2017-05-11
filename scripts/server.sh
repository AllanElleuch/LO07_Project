#! /bin/bash

# S'il n'y a pas d'argument
if [[ $# -eq 0 ]]; then
    echo "Saisir start, stop ou restart"
    read action
else
    if [[ $1 == "start" || $1 == "stop" || $1 == "restart" ]]; then
        echo "[...] php bin/console server:$1"
        php bin/console server:$1
    else
        echo "Saisir start, stop ou restart"
        read action
    fi
fi
