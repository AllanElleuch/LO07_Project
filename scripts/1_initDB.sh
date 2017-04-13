#! /bin/bash

echo "Purge de la base de données"
php bin/console doctrine:database:drop --force
echo "Création des tables"
php bin/console doctrine:database:create
