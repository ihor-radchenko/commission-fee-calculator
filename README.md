#Requirements

1. PHP 7.3
2. Composer

#Installation
Copy .env.example to .env

set **EXCHANGERATESAPI_KEY** value

Install composer dependencies

```shell
composer install
```

#Run script

```shell
php bin/application.php storage/input.csv
```

#Run tests

```shell
composer test
```
