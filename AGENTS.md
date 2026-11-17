# Project Guidelines — Kopisthetics (Laravel / PHP 8.5)

## Project Context & Source of Truth
- **Domain:** Kopisthetics is a full-featured online coffee shop (ready-to-drink menu, coffee beans, product detail with variants/options, cart, checkout, Coffee Matcher, and staff management for products & order statuses).
- **UI & Design System:** Responsive **Neobrutalism** (thick black borders, hard offset shadows, high-contrast primary accents—match existing Blade/CSS conventions seen in the layout[cite: 2]).
- **Reference Docs (Read Before Acting):**
  - `TASK.md` — Phased feature roadmap and current task acceptance criteria.
  - `PRODUCTS.md` — Rules and conventions for simulated product data (prices, ratings, bean origin/roast details).
- **Media & Storage Architecture:** Catalog images are stored in **Supabase Storage**. Database/seed records must store clean **object keys** matching actual files in the Supabase bucket—never hardcoded full URLs or broken local paths. Public URLs must be resolved dynamically via a configured Supabase storage base URL (e.g., `config/filesystems.php` or `config/services.php` / model accessor).

---

## Core Engineering Principles

### 1. Think Before Coding
- **No silent assumptions:** If a requirement, bucket path, or product schema is ambiguous, inspect `PRODUCTS.md`, `TASK.md`, or the database first—or ask before writing code.
- **Challenge complexity:** Do not introduce unnecessary service layers, repositories, or abstractions when a clean Eloquent accessor, config helper, or standard controller action solves the problem.
- **Plan in chat, not files:** For multi-file changes, outline affected files, edge cases, and verification steps briefly in chat before editing. Do not generate `docs/plan/` files unless explicitly requested.

### 2. Simplicity First
- Write the minimum code that cleanly satisfies the current task in `TASK.md`.
- Reuse existing Blade components, layouts, and Neobrutalism utility styles instead of creating duplicate markup.
- Only create documentation files when explicitly requested.

### 3. Surgical Changes
- Touch only the files and lines strictly required for the active task.
- Do not reformat, rename, or refactor unrelated code nearby.
- Clean up orphaned imports, unused variables, or obsolete seed/asset references when replacing them.

### 4. Goal-Driven Execution
- Define verifiable success criteria before implementing (e.g., all seeded products resolve valid Supabase image URLs; feature tests pass without broken image keys).
- Loop until tests pass and code formatting is clean.

---

## Stack & Hard Constraints

- **Environment:** Laravel on **PHP 8.5**. Verify package versions via `composer show --direct` or `package.json` before relying on version-specific APIs.
- **Structure & Dependencies:** Do not create new root/base directories or add/update Composer/NPM dependencies without explicit approval.
- **Context Discovery:** Check `.ai/rules/index.md` (if `.ai/rules` exists) and relevant domain skills in `**/skills/**` before creating or editing files.
- **Boost MCP Tools:** Prefer Laravel Boost MCP tools (`database-schema`, `database-query`, `search-docs`, `browser-logs`, `get-absolute-url`) over manual `tinker` commands or guessing Laravel syntax.

---

## Testing & Verification

- **Tests over Tinker:** Do not write ad-hoc verification scripts or use `tinker` to prove code works—write or update Feature/Unit tests using model factories.
- **Scope:** Add tests only for changed behavior and critical failure modes (skip for pure copy/styling edits).
- **Run narrowest tests:**
  ```bash
  php artisan test --compact --filter=TestName
  ```
- **Code Formatting (Mandatory after PHP edits):**
  ```bash
  vendor/bin/pint --dirty --format agent
  ```
- **Frontend Assets:** If UI changes do not appear or Vite throws a manifest error, run `npm run build` (or ask the user to run `npm run dev`).