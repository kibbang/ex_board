<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


**Example board**

scribe
http://localhost:8080/docs

using laravel sail, laravel passport laravel scribe

**laravel sail**

composer require laravel/sail

php artisan sail:install

./vendor/bin/sail up

./vendor/bin/sail up -d (백그라운드 실행)

./vendor/bin/sail stop

터미널 진입

./vendor/bin/sail shell

**laravel passport**

composer require laravel/passport

php artisan migrate

php artisan passport:install

**laravel passport**

composer require --dev knuckleswtf/scribe

php artisan vendor:publish --tag=scribe-config (scribe.php 파일 생성)

config파일 수정 했을 경우 

php artisan scribe:generate 로 refresh

시드데이터 만들기

php artisan db:seed --class=UserTableSeeder

php artisan db:seed --class=PostTableSeeder

php artisan db:seed --class=CommentTableSeeder


