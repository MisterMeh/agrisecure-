# AgriSecure Static Site

This repository has been converted from Laravel to a plain static GitHub Pages site.

## What changed
- The active site is now a static frontend built with HTML, CSS, and JavaScript.
- The main static pages live under the `docs/` folder.
- Laravel backend code remains in the repository for reference, but it is no longer the hosted site.

## GitHub Pages setup
Use one of these configuration methods:
1. Set Pages source to `main` branch and `/docs` folder.
2. Or keep Pages source at the repository root; the root `index.html` now redirects to `docs/index.html`.

## Available static pages
- `docs/index.html` — login page
- `docs/register.html` — register page
- `docs/dashboard.html` — dashboard demo
- `docs/requests-management.html`
- `docs/user-management.html`
- `docs/user-file-management.html`
- `docs/admin-file-management.html`
- `docs/audit-logs.html`

## Notes
- Login/register are mocked for navigation only.
- No PHP, database, or server-side auth is supported on GitHub Pages.
