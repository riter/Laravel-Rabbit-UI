<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Download code

> git clone [https://github.com/riter/Laravel-Rabbit-UI.git](https://github.com/riter/Laravel-Rabbit-UI.git)

## Install Composer Dependencies

> composer install

## Install NPM Dependencies

> npm install

## Database

> create database pgs_proyect_final

> restore database from pgs_proyect_final.sql in mysql 

## Configure file .env

> open file .env

>change DB_DATABASE, DB_USERNAME, DB_PASSWORD by your  database credentials

## Run serve

> execute php artisan serve

> open url http://127.0.0.1:8000

> register your data and saved

## Main codes of the project

>Create, delete file and send messages to RabbitMQP (DSS-Storage and DSS-Mailing)  
>[https://github.com/riter/Laravel-Rabbit-UI/blob/master/app/Http/Controllers/HomeController.php](Manager Messages)

> Send Message to RabbitMQP
> [https://github.com/riter/Laravel-Rabbit-UI/blob/master/app/Http/Controllers/PublicityAMQP.php](Sended Message to RabbitMQP)

> Listen Messages
> [https://github.com/riter/Laravel-Rabbit-UI/blob/master/app/Http/Controllers/ManagerRabbitAMQP.php](Listen Messages)
