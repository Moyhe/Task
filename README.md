## Installation

1.clone the project

    git@github.com:Moyhe/Task.git

2.Run composer install

    composer install

3.Copy .env.example into .env

    cp .env.example .env

4.Run migrations

    php artisan migrate --seed

5.Set encryption key

    php artisan key:generate --ansi

6.to start the project run

    php artisan serve

7.to test the api run

    php artisan test

## Postman Collection

You can download and import the Postman collection [here](/Task.postman_collection.json).
