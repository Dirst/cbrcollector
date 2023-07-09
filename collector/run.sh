#!/bin/bash

cd /var/www
composer install

/docker-init/wait-for-it.sh -t 0 db:3306 -- echo "Database is ready!"
/docker-init/wait-for-it.sh -t 0 rabbit:5672 -- echo "Rabbit is ready!"

doctrine-migrations migrate -n

php ./bin/collector collect