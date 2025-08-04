<h1 align="center">DateRangePicker Demo</h1>

This project serves as a demo for a custom-made DateRangePicker component. It was made using Laravel 8, Vue3, InertiaJS and momentjs.

## Requirements

-   Git
-   Docker

## Installation

-   Clone the project

-   Create a _.env_ file, a _.env.example_ has been included to help you get started

```shell
cp .env.example .env
```

-   Run the containers

```shell
docker compose up -d
```

-   Enter the _web_ container

```shell
docker container ls # Get container ID
docker exec -it {CONTAINER_ID} bash
```

-   Install dependencies & build assets

```shell
composer install
yarn install
yarn dev
```

-   Add application key and generate storage link

```shell
php artisan key:generate
php artisan storage:link
```

-   Migrate and seed the database

```shell
php artisan migrate --seed
```

The application should now be up and running at

## Services

After running the docker application, several services are available to use :

-   Port 80 : DRP-Demo application
-   Port 8080 : phpMyAdmin for easy database administration
-   Port 3306 : MariaDB instance

## Usage

In order to access the DateRangePicker and the events CRUD, you will first have to open the app, then login. A test user is automatically created during the installation phase :

-   test@test.com
-   password

Once logged in, click on the _Events_ tab. Here you will find all the requested functionality.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
