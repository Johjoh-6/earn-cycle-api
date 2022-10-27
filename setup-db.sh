#!/bin/bash
composer install
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate

echo "Create admin user and persist data"
php bin/console doctrine:fixtures:load

echo "Done!"