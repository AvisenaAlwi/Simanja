<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

***

<h1 align="center">SIMANJA</h1>

## Cara clone sampai siap jalan
1. Install aplikasi yang dibutuhkan <a href="#requirement">disini</a>
2. Buka command line atau terminal
3. Masuk ke folder root project. Contoh ``` cd "D:/website" ```
4. Ketik perintah ``` git clone https://github.com/AvisenaAlwi/Simanja ``` kalau muncul prompt login github langsung login pakai akun gitlab kalian
5. Masuk ke direktori project ``` cd "Simanja" ```. Btw nama folder bisa diubah
6. Jalankan perintah ``` composer install ``` di terminal. Tunggu sampai selesai
7. Jalankan perintah ``` npm install ``` di terminal. Tunggu sampai selesai
8. Duplikat file ```.env.example``` dan rename menjadi ```.env``` [dot env] saja
9. Jalankan perintah ``` php artisan key:generate ``` di terminal
10. Buka file ```.env```
11. Setting bagian database. Contoh sebagai berikut :

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=simanja
DB_USERNAME=root
DB_PASSWORD=
```

12. Pastikan service DB kalian sudah jalan dan sebelumnya telah membuat database dengan nama ```simanja``` pada contoh diatas
13. Setting hal lain pada file ```.env``` misal APP_NAME atau APP_URL
14. Jalankan perintah ```php artisan migrate --seed``` pada terminal dan pastikan masih didalam folder project kasirplus-web
15. Lalu untuk menjalankan laravel bisa melalui perintah ```php artisan serve --port=80```, maka host dapat diakses pada ```http://localhost```. Pastikan Xampp kalian berjalan pada port 81 atau lainya
16. Selain dari ```php artisan serve``` kita bisa pakai XAMPP, WAMPP, LAMPP atau yang lebih praktis kita bisa pakai laravel Valet, cara install laravel Valet bisa di cek di channel YouTube <b>IDStack</b>
17. Jalankan perintah ```php artisan storage:link```
18. Untuk development menggunakan laravel mix jalankan perintah ```npm run watch``` pada terminal [OPTIONAL]


<h2 id="requirement">Requirement</h2>

* PHP versi 7.1.*, pastikan file path php.exe sudah terdaftar pada PATH terminal atau cmd. Tutorial untuk windows <a target="_blank" href="https://john-dugan.com/add-php-windows-path-variable/">Disini</a>. Untuk linux atau mac os bisa install lewat terminal.
* Semua requiremnt yang dibutuhkan laravel, di web resminya
* Install git. Download aplikasinya di <a href="http://git-scm.com">sini</a>
* Install composer. Download aplikasinya di <a target="_blank" href="https://getcomposer.org/download/">sini</a>
*3.* Install nodejs. Download aplikasinya di <a target="_blank" href="https://nodejs.org/en/">sini</a>

## Struktur Laravel
```
|-- app (Otak atik disini)
|   |-- Console
|   |-- Events
|   |-- Exceptions
|   |-- Http
|   |   |-- Controllers (Otak atik disini)
|   |   |-- Middleware
|   |   `-- Requests
|   `-- Providers
|-- bootstrap
|   `-- cache
|-- config
|-- database
|   |-- factories
|   |-- migrations
|   `-- seeds
|-- public (Otak Atik disini)
|   |-- assets
|   |-- css
|   |-- img
|   |-- scss
|   |-- storage -> link ke folder root storage/app/public
|-- resources (Otak Atik disini)
|   |-- assets
|   |   `-- less
|   |-- lang
|   |   `-- en
|   `-- views (Otak atik disini)
|       |-- activity
|       |-- layouts
|       |...
|-- storage
|   |-- app
|   |-- framework
|   |   |-- cache
|   |   |-- sessions
|   |   `-- views
|   `-- logs
|-- tests
```

## License

The Laravel framework is open-sourced software licensed under the Apache 2.0.
