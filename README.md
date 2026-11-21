# Kopisthetics

Kopisthetics is a Laravel coffee shop storefront and order management application.

## Requirements

- PHP >= 8.3 with SQLite or MySQL extension
- Composer
- Node.js >= 18 and npm

## Installation

```bash
git clone https://github.com/zDiqim7/p4tk-web.git
cd p4tk-web
composer run setup
composer run dev
```

The application runs at `http://127.0.0.1:8000`.

## Configuration

Copy `.env.example` to `.env` and set the following variables:

| Variable | Type | Default | Description |
| --- | --- | --- | --- |
| `APP_ENV` | string | `local` | Application environment (`local`, `production`). |
| `APP_DEBUG` | boolean | `false` | Debug mode toggle. |
| `DB_CONNECTION` | string | `mysql` | Database driver (`sqlite`, `mysql`). |
| `DB_DATABASE` | string | `p4tk_db_dev` | Database name or SQLite file path. |
| `ADMIN_NAME` | string | None | Default administrator account display name. |
| `ADMIN_EMAIL` | string | None | Administrator login email. |
| `ADMIN_PASSWORD` | string | None | Administrator login password. |
| `SESSION_DRIVER` | string | `database` | Storage driver for user sessions. |

## Available Commands

| Command | Description |
| --- | --- |
| `composer run setup` | Installs dependencies, copies `.env`, runs migrations, and builds assets. |
| `composer run dev` | Runs the PHP development server and Vite asset bundler concurrently. |
| `composer run test` | Clears configuration cache and executes PHPUnit test suite. |
| `php artisan db:seed` | Seeds database records for admin credentials and initial catalog data. |

## Core Features

- **Storefront Catalog:** Product and beverage listings with category filters (`/menu`, `/products`).
- **Cart and Checkout:** Session cart with support for temperature, sugar level, and fulfillment options (`/cart`, `/checkout`).
- **Bean Profile Matcher:** Facet search for roast level, origin, and flavor attributes (`/jelajah`).
- **Admin Dashboard:** Order status pipeline (`pending` -> `processing` -> `ready` -> `completed`) and catalog management (`/admin`).
- **REST API:** JSON endpoints for catalog items and filter options (`/api/v1`).

## API Reference

### Catalog Endpoints

| Method | Endpoint | Description |
| --- | --- | --- |
| `GET` | `/api/v1/menu` | Returns paginated beverage items. |
| `GET` | `/api/v1/products` | Returns paginated packaged products. |
| `GET` | `/api/v1/categories` | Returns all catalog categories. |
| `GET` | `/api/v1/menu/filters` | Returns available filter facets for beverage menus. |
| `GET` | `/api/v1/products/filters` | Returns available filter facets for packaged goods. |

### Query Parameters

| Parameter | Type | Allowed Values | Description |
| --- | --- | --- | --- |
| `category` | string | Category slug | Filters products by category. |
| `sort` | string | `featured`, `popular`, `newest`, `price_high_low`, `price_low_high` | Sorts returned items. |
| `page` | integer | `>= 1` | Pagination page number. |
| `per_page` | integer | `1` to `12` | Items returned per page. |
| `roast_level` | string | Any string | Filters items by roast level attribute. |
| `origin` | string | Any string | Filters items by coffee origin attribute. |

## Testing

Execute PHPUnit feature and unit tests:

```bash
composer run test
```

## License

This project is open-source software licensed under the MIT license.
