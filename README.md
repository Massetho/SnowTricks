# TrickSlider
Community website about snowboard tricks

## Prerequisites
Environment :
- PHP 7
- MySQL 14.14

You must have installed the following before install :
- [Git](https://git-scm.com/downloads)
- [Composer](https://getcomposer.org/)

Also, create a [Sendgrid](https://sendgrid.com/) account with a unique API key.

## Installing
Cloning the project repository :
```
$ git clone https://github.com/Massetho/TrickSlider
```

Edit `config/services_sample.yaml` by adding your **Sendgrid API key** and **admin mail** in the parameters. Then rename the file `services.yaml`.


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
- **Email** : the email address provided by you in `config/services.yaml`.
- **Password** : "*pass1234*"

## Running the tests
From the root folder of your project :
```
$ php vendor/bin/phpunit
```

## Built with
- [Symfony 4](https://symfony.com/) - PHP Framework
- [Composer](https://getcomposer.org/) - Dependency management

## Author
**Quentin Thomasset** - PHP developper