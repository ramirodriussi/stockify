# Stockify

_Sistema de stock en el que podrás ingresar usuarios, permitiendo acceso al panel como administrador o empleado, agregar productos con sus características, agregar ventas asociadas a multiples productos y locales. El sistema está integrado con un generador de código de barras y lector de barras, para posteriormente agregar productos a las ventas de forma más rápida._

## Comenzando 🚀

_Estas instrucciones te permitirán obtener una copia del proyecto en funcionamiento en tu máquina local para propósitos de desarrollo y pruebas._

### Instalación API 🔧

_Una vez clonado el respositorio en tu máquina local, debés seguir los siguientes pasos:_

```
cd api
```

_Instalamos dependencias necesarias con Composer_

```
composer install
```

_Generamos archivo .env y completamos con nuestros datos_

```
cp .env.example .env
```

_Generamos key de Laravel_

```
php artisan key:generate
```
_Hacemos las migraciones correspondientes y ejecutamos los seeders_

```
php artisan migrate --seed
```
_Finaliza con un ejemplo de cómo obtener datos del sistema o como usarlos para una pequeña demo_

### Instalación SPA 🔧

_Una vez clonado el respositorio en tu máquina local, debés seguir los siguientes pasos:_

```
cd spa
```
_Instalamos dependencias:_

```
npm i
```
## Demo y credenciales de acceso 🔓

[Link](https://stockify-ramirodriussi.netlify.app/)

_Administrador:_
```
email: admin@example.com
password: password
```
_Empleado:_
```
email: empleado@example.com
password: password
```
## Construido con 🛠️

_Menciona las herramientas que utilizaste para crear tu proyecto_

* [Laravel 8](https://laravel.com/) - Framework PHP
* [Nuxt.js](https://nuxtjs.org/) - Framework Javascript
* [Composer](https://getcomposer.org/doc/) - Manejador de dependecias PHP
* [NPM](https://www.npmjs.com/) - Manejador de dependencias Javascript

## Autor ✒️

* **Ramiro Driussi**

