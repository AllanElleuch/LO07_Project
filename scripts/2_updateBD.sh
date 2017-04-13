#! /bin/bash

php bin/console doctrine:schema:validate


php bin/console doctrine:generate:entities AppBundle/Entity/Cursus

php bin/console doctrine:generate:entities AppBundle/Entity/ElementFormation
php bin/console doctrine:generate:entities AppBundle

php bin/console doctrine:schema:update --force