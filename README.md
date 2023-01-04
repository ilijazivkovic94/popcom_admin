# Metronic 7 + Laravel 8

### Introduction

This is the SaaS cloud software that serves as the administration portal for POPCOM vending software.
This constitutes platform admin and tenant (machine owner) roles; as well as the delegated tenant (tenants grouped by governing brand) role.

This repo DOES NOT contain any APIs to serve the POS. However, it connects to the same database as the APIs and focuses on web-based reporting and administration for the entire platform.

For the API, please see [this git repo](https://gitlab.com/big-kitty-labs/popcom-2021/popcom-2021-api)


### Installation

Laravel has a set of requirements in order to ron smoothly in specific environment. Please see [requirements](https://laravel.com/docs/7.x#server-requirements) section in Laravel documentation.

Metronic similarly uses additional plugins and frameworks, so ensure You have [Composer](https://getcomposer.org/) and [Node](https://nodejs.org/) installed on Your machine.

Assuming your machine meets all requirements - let's process to installation of Metronic Laravel integration (skeleton).

1. Open in cmd or terminal app and navigate to this folder
2. Run following commands

```bash
composer install
```

```bash
cp .env.example .env
```

_After this step you must EDIT the env file. Ask for final version from the dev team. This file contains credentials and is not committed to git for security reasons._

```bash
php artisan key:generate
```

```bash
npm install
```

```bash
npm run dev
```

```bash
php artisan serve
```

And navigate to generated server link (http://127.0.0.1:8000) or if on a webserver, point your browser to the domain.


### Built With

- [Laravel 8](https://laravel.com/docs/8.x/releases)
- [Laravel Cashier for Stripe](https://laravel.com/docs/8.x/billing)
- [Metronic 7 for Laravel](https://keenthemes.com/metronic/)
- Various open source jQuery bundles
- ![love](https://img.icons8.com/cotton/64/000000/like--v1.png)
