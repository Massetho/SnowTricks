# Snow Tricks

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/1ed42ea222b940a5961601fe5ec1772e)](https://app.codacy.com/app/Massetho/TrickSlider?utm_source=github.com&utm_medium=referral&utm_content=Massetho/TrickSlider&utm_campaign=Badge_Grade_Dashboard)

Community website about snowboard tricks

## Prerequisites
Environment :
- PHP 7
- MySQL 14.14

You must have installed the following before install :
- [Git](https://git-scm.com/downloads)
- [Composer](https://getcomposer.org/)

## Installing
Cloning the project repository :
```
$ git clone https://github.com/Massetho/SnowTricks
```

Edit `.env.dist` by adding your *MAILER_URL* and **admin mail** in the parameters. Then rename the file `.env`.


Getting all dependencies :
```
$ composer install
```

Configure a *DATABASE_URL* constant, at the web server level, as explained [here](https://symfony.com/doc/current/configuration/external_parameters.html#configuring-environment-variables-in-production). Or edit the `.env` file and insert your database address :
```
DATABASE_URL=mysql://[db_user]:[password]@[IPaddress]:[port]/[db_name]
```

Setting up your database :
```
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
```

Loading fixture content : 
```
$ php bin/console doctrine:fixtures:load
```

The default account have the following credentials :
- **User** : "admin"
- **Email** : the email address provided by you in the *ADMIN_MAIL* environment constant.
- **Password** : "*pass1234*"

## Running the tests
First, make sure to edit the `phpunit.xml.dist` file to define the *DATABASE_URL* and *ADMIN_MAIL* constants.
Then, from the root folder of your project :
```
$ php vendor/bin/phpunit
```

## Built with
- [Symfony 4](https://symfony.com/) - PHP Framework
- [Composer](https://getcomposer.org/) - Dependency management

## Author
**Quentin Thomasset** - PHP developper