# PIKOBAR Tes Masif COVID-19

[![Build Status](https://travis-ci.org/jabardigitalservice/pikobar-rapidtest-api.svg?branch=master)](https://travis-ci.org/jabardigitalservice/pikobar-rapidtest-api)
[![Maintainability](https://api.codeclimate.com/v1/badges/fae1475c143f7a532884/maintainability)](https://codeclimate.com/github/jabardigitalservice/pikobar-rapidtest-api/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/fae1475c143f7a532884/test_coverage)](https://codeclimate.com/github/jabardigitalservice/pikobar-rapidtest-api/test_coverage)

Backend API untuk Aplikasi Pendaftaran dan Undangan Tes Masif COVID-19 PIKOBAR. 

## Petunjuk development
1. Clean Code, ikuti standard style FIG PSR-12 dengan menggunakan PHP Code Sniffer.
2. Clean Architecture.
3. Maksimalkan penggunaan fitur-fitur built-in Laravel.
4. Thin Controller.
5. Gunakan Single Action Controller.

## Bagaimana cara memulai development?
Clone Repository terlebih dahulu:
```
$ git clone https://github.com/jabardigitalservice/pikobar-rapidtest-api
```

Copy file config dan sesuaikan konfigurasinya:
```
$ copy .env-example .env
```

Install dependencies menggunakan Composer"
```
$ composer install
```

Jalankan Artisan untuk migrasi dan seed database:
```
$ php artisan migrate:fresh --seed
```

Jalan Artisan Local Development Server:
```
$ php artisan serve
```
