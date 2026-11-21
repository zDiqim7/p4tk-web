# Kopisthetics Project Context

Last verified: 2026-11-17

## Project purpose

Kopisthetics is a Laravel coffee-shop storefront and staff-management app. Its main goal is to let customers browse ready-to-serve menu items and packaged products, customize items, place a simulated checkout order, and discover coffee profiles. Staff can manage catalog records and advance order statuses.

The public experience uses Indonesian copy and a responsive Neobrutalism visual direction. Coffee product descriptions and tasting metadata follow the sample-data guidance in [PRODUCTS.md](./PRODUCTS.md), [MENU.md](./MENU.md). Those prices, ratings, origins, and reviews are design/demo data unless verified separately.

## Expect User flows

### Customer

1. Browse the home page, `/menu`, or `/products`.
2. Filter, sort, and paginate catalog results. Catalog pages request up to 12 items per API page.
3. Open a menu or product detail page.
4. Choose item-specific options and add the item to the session cart.
5. Submit checkout. The app creates an order and guest customer record; payment is simulated, not processed online.
6. Use `/jelajah` and Coffee Matcher to filter and compare items by available profile attributes.

Menu items support temperature, sugar level, add-ons, and pickup or instant delivery. Products support grind size, pack size, and regular shipping. A cart currently permits one fulfillment type at a time.

### Staff

Staff sign in through `/admin/login`. Authenticated admin users can manage categories and products, review orders, and advance order status through `pending`, `processing`, `ready`, and `completed`.

## Architecture

This is a server-rendered Laravel application, not a separate frontend SPA.

- **Web layer:** `routes/web.php` maps public pages, cart, checkout, and admin routes.
- **API layer:** `routes/api.php` exposes versioned catalog endpoints under `/api/v1`.
- **Page controller:** `PageController` returns Blade catalog, detail, explore, and about pages.
- **Catalog API:** API controllers query Eloquent models, validate filters, paginate results, and serialize products through API resources.
- **Views:** Blade templates compose the shared public layout, navigation, footer, cart drawer, catalog listings, and admin pages.
- **Browser behavior:** Vanilla JavaScript handles catalog fetching/filtering, cart interactions, theme controls, scroll effects, and Coffee Matcher.
- **Styling:** Project CSS lives mainly in `public/assets/css`. The repository also has a Tailwind/Vite source setup.

The current app layout loads Tailwind and other frontend libraries from CDNs while most page styles and scripts load from `public/assets`. `vite.config.js` also defines a Vite build for `resources/css/app.css` and `resources/js/app.js`. Treat these as two existing asset paths; check which one a page uses before changing frontend build behavior.

## Data model

- `Category` groups catalog records.
- `Product` represents both menus and products. `catalog_type` separates `menu` from `product`; `catalog_attributes` stores profile/facet metadata as JSON.
- `Order` stores customer, fulfillment, payment-method label, status, and totals.
- `OrderItem` stores product, quantity, unit price, and JSON customization.
- `User` supports regular and guest checkout records and an `is_admin` flag.
- `Contact` stores contact-page submissions.

The shopping cart is stored in the Laravel session. Checkout writes an order and its items inside a database transaction, then clears the session cart. There is no separate persistent cart model.

## Important routes

| Purpose | Route |
| --- | --- |
| Home | `/` |
| Menu list and detail | `/menu`, `/menu/{slug}` |
| Product list and detail | `/products`, `/products/{slug}` |
| Coffee Matcher and discovery | `/jelajah` |
| About | `/tentang-kami` |
| Cart and checkout | `/cart`, `/checkout` |
| Admin | `/admin` |
| Catalog API | `/api/v1/menu`, `/api/v1/products`, category and filter endpoints |

`/produk` and `/jelajahi` are legacy routes that redirect or map to current destinations. Use named routes when generating links.

## Tech stack

| Layer | Current stack |
| --- | --- |
| Backend | PHP 8.5 runtime; Laravel Framework 13.38.0 installed |
| Dependency constraints | `composer.json` requires PHP `^8.3` and Laravel `^13.17` |
| Database | MySQL for local application development; PHPUnit config uses SQLite `:memory:` |
| Frontend | Blade, vanilla JavaScript, CSS, Tailwind CSS 4 and Vite configuration |
| Build tools | Node.js 24.11.0, npm 11.6.1, Vite 8 |
| Tests | PHPUnit 12.5.40 with Laravel feature and unit tests |
| Catalog image storage | Supabase Storage public bucket, configured through S3-compatible filesystem settings |

Installed package versions were read from Composer and local runtime commands on the verification date. Confirm versions again before relying on version-specific behavior.

## Images and Supabase Storage

Catalog `image_path` values should be relative object keys, not full URLs. For example:

```text
menu/food/pancake.jpg
products/coffee-beans/alpine-signal.jpg
```

The public storage base URL should end at the bucket, for example:

```text
https://<project-ref>.supabase.co/storage/v1/object/public/catalog-images
```

The expected image URL is the base URL plus the object key. The bucket must be public for direct browser access, and each object key must exactly match an existing file. Local page art and the fallback image can remain under `public/assets/img`.

Relevant implementation:

- `config/filesystems.php` defines the S3-compatible disk and reads `AWS_URL`.
- `Product::image_url` resolves a public URL and returns a local placeholder for empty paths.
- `ProductResource` exposes both `image_path` and `image_url`.
- Catalog JavaScript and Blade templates render product images.
- `ProductSeeder` currently defines eight menu/product records with object-key-style paths.

### Storage status and known risks

- The code and seed data use object keys, but this repository snapshot does not verify that those keys exist in the Supabase bucket.
- The live database contents were not queried while writing this document. Old rows may still contain local paths; changing a seeder does not update the database until that seeder runs.
- The seeder's object names must be checked against the actual bucket. The user-provided public URL confirms one example key, `menu/food/pancake.jpg`; it does not confirm the other keys.
- Keep `AWS_URL` as a bucket-level base URL. Do not put an individual filename in it.
- After changing environment values, clear Laravel's cached configuration before testing.
- Do not copy secrets, access keys, database passwords, or private environment values into documentation or chat.

## Current implementation state

### Present in the codebase

- Public home, catalog, product/menu detail, discovery, and about pages.
- Catalog API with categories, facets, filtering, sorting, and pagination.
- Session cart with validated menu/product options and fulfillment rules.
- Simulated checkout that persists orders and order items.
- Admin login, category/product CRUD, and order-status progression.
- Coffee Matcher driven by catalog API data and product attributes.
- Product image URL accessor, API image URL field, and image fallbacks.
- PDP similar-product section and an empty review state.

### Incomplete or requiring verification

- Task 10 is not fully proven complete. Verify seeded object keys and database rows against real Supabase objects, then verify image requests in a browser.
- The home showcase's menu cards query the database, but its product preview still uses a hardcoded array in `resources/views/components/menu.blade.php`.
- Product reviews have no dedicated model, table, or persisted review workflow in the current schema. The PDP reads optional review-shaped data; treat displayed ratings as simulation, not customer evidence.
- The current `ProductSeeder` contains eight records. `PRODUCTS.md` describes a larger sample catalog and is guidance, not proof that all entries are seeded.
- Admin product forms currently accept an image path. Confirm upload behavior before assuming files are uploaded to Supabase.
- Run `php artisan test` for a broad baseline when needed. Focused feature tests cover public pages, catalog APIs, cart, checkout, and admin flows.

## Source of truth and working rules

Read these before changing project behavior:

- [AGENTS.md](../AGENTS.md) for project constraints, coding conventions, and verification rules.
- [TASK.md](./TASK.md) for task acceptance criteria and roadmap.
- [PRODUCTS.md](./PRODUCTS.md) for simulated coffee-product content and metadata.
- `routes/web.php`, `routes/api.php`, models, requests, and tests for actual behavior.

The roadmap marks Tasks 1-9 done, Task 10 active, and Task 11 as subsequent admin-panel work. Confirm current implementation and tests rather than treating roadmap labels as verification.

At the time this document was written, the worktree contained many existing uncommitted changes. Inspect `git status` before editing, preserve unrelated work, and do not assume all current files are committed or deployed.
