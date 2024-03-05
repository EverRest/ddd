# Stack

    - Laravel 10
    - MYSQL 8
    - Redis 

# Set Up

- `git clone` - clone the repository
- `cp .env.example .env` - copy the .env file
- `php artisan key:generate` - generate the key
- `docker-compose up -d` - start the containers

## Linters and analyzators

- [CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) :
- `./vendor/bin/phpcs --standard=PSR12 -s app` - checking app folder for psr-12 standart(feel free to choose standart)
- `./vendor/bin/phpcbf --standard=PSR12 -s app` - fix issues in app folder for psr-12 standart
- [PHPstan](https://phpstan.org) :
  -`./vendor/bin/phpstan analyse app --memory-limit=-1` - analyze your code
- [SonarQube](https://docs.sonarsource.com/sonarqube/latest/setup-and-upgrade/install-the-server/installing-sonarqube-from-docker/)
- its a static code analyzator, please check the documentation of SonarQube

## Tests

- [Laravel](https://github.com/laravel/laravel)
- `./vendor/bin/phpunit` - run phpunit tests
- ` php vendor/bin/codecept run --steps` - run codeception tests
