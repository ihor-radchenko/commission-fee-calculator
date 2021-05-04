# Requirements

1. PHP 7.3
2. Composer

# Installation

1. Copy .env.example to .env
2. Set value for **EXCHANGERATESAPI_KEY**
3. Install composer dependencies:
    ```shell
    composer install
    ```

# Run script

```shell
php bin/application.php storage/input.csv
```

# Run tests

```shell
composer test
```
