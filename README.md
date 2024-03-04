# About

# Stack
    - Laravel 10
    - MYSQL 8
    - Redis 

## Added linters, checkers and analyzators
- [CodeSniffer]([https://github.com/squizlabs/PHP_CodeSniffer]) : 
`./vendor/bin/phpcs --standard=PSR12 -s app` - checking app folder for psr-12 standart(feel free to choose standart)
`./vendor/bin/phpcbf --standard=PSR12 -s app` - fix issues in app folder for psr-12 standart
- [PHPstan](https://phpstan.org):
  `./vendor/bin/phpstan analyse app --memory-limit=-1` - analyze your code
- [SonarQube](https://docs.sonarsource.com/sonarqube/latest/setup-and-upgrade/install-the-server/installing-sonarqube-from-docker/)
- its a static code analyzator, please check the documentation of SonarQube

## php unit
- [Laravel](https://github.com/laravel/laravel)
