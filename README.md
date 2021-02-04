# on.Special

A supermarket products on special aggregator web application.

## Installation

1. Clone the repository:

    ```bash
    git clone git@gitlab.com:nisanov/onspecial.git .
    ```

2. Build the docker containers:

    ```bash
    docker-compose up [-d]
    ```

3. Install the composer dependencies:

    ```bash
    docker-compose exec php composer install -vvv
    ```

4. Perform the database migrations:

    https://book.cakephp.org/phinx/0/en/contents.html

    ```bash
    docker-compose exec php php ./vendor/bin/phinx migrate -e development
    ```

## Testing

- Run the complete test suite:

    ```bash
    docker-compose exec php ./vendor/bin/phpunit
    ```

## Commands

- Using psy shell to interact with the application:

    ```bash
    docker-compose exec php php console.php psysh
    ```

## Redis

1. Connecting to the redis server:

    ```bash
    docker-compose exec redis redis-cli -a [password]
    ```

2. Show the sessions stored in the redis server; once connected:

    ```bash
    SELECT 1
    KEYS *
    MGET key [key ...]
    ```

3. Show the model metadata stored in the redis server; once connected:

    ```bash
    SELECT 2
    KEYS *
    MGET key [key ...]
    ```

4. Show the cache stored in the redis server; once connected:

    ```bash
    SELECT 3
    KEYS *
    MGET key [key ...]
    ```

## Xdebug

- To enable xdebug (initially enabled):

    ```bash
    docker-compose exec php xdebug up
    ```

- To disable xdebug:

    ```bash
    docker-compose exec php xdebug down
    ```

- To check the status of xdebug:

    ```bash
    docker-compose exec php xdebug status
  ```

- Example of debugging psy shell at the command line:

    ```bash
    docker-compose exec php php -dxdebug.client_host=172.17.0.1 console.php psysh
    ```

## Testing

1. Run the phpunit test suite:

    ```bash
    docker-compose exec php ./vendor/bin/phpunit
    ```

## MailHog

- Access MailHog redirected emails via the following location:

    ```bash
    http://127.0.0.1:8025
    ```

## Docker Compose

- Updating a single container:

    ```bash
    docker-compose pull php
    ```

    ```bash
    docker-compose up -d --no-deps --build php
    ```

- Install and renew lets encrypt certificate:

    ```bash
    docker-compose exec nginx certbot --nginx -d specials.onthewifi.com
    ```
