#!/bin/bash
while [ true ]
do
    ./vendor/bin/phpunit && ./vendor/bin/phpcs --standard=PSR2 --extensions=php src tests
    sleep 2
done