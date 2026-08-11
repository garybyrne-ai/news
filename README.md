# Villa Andie — one-page website + CMS

A premium, futuristic one-page website for **Villa Andie**, a private-pool
holiday villa in Puerto del Carmen (Tías, Lanzarote) — built on a
self-contained **PHP 8 + SQLite CMS** with no external dependencies,
frameworks or build step.

## Quick start

```bash
php -S localhost:8080 router.php
```

Open http://localhost:8080 — the SQLite database (`data/cms.sqlite`) is
created and seeded with the villa's real content on the first request.

On Apache, point the document root at this directory; `.htaccess` handles
clean URLs and protects `data/`, `app/` and `templates/`. Requirements:
PHP ≥ 8.1 with `pdo_sqlite` (standard on virtually all shared hosting).

## Admin

- URL: `/admin/`
- Default password: `change-me-now` — **change it immediately** in
  Settings → Change admin password.

The CMS controls every section of the homepage:

| Feature | Where |
| --- | --- |
| Edit any section's content | Sections → Edit |
| Draft → Preview → Publish workflow | Save draft, then Preview (desktop/tablet/mobile), then Save & publish |
| Enable / disable / reorder / duplicate / delete sections | Sections dashboard |
| Per-section background style, anchor ID, custom CSS class | Section → Section settings |
| Business info, hours, status chip, socials | Settings |
| SEO title/description, OpenGraph, schema.org toggle | Settings |
| Theme colours (all 9 CSS variables) | Settings |
| Contact-form leads | Leads |

## Content policy

Seeded content uses only verified facts from the villa's listing
(3 bedrooms, 2 bathrooms, private pool, distances, amenities). The
**Guest Reviews section ships disabled** with a clearly marked placeholder —
add real reviews in the CMS before enabling it. Phone/email are blank until
filled in Settings; the UI hides empty values instead of showing fakes.

## Architecture

```
index.php              Front controller: /, /privacy, /cookies, /terms,
                       /accessibility, POST /enquiry (lead capture)
router.php             Dev server router (php -S)
app/                   Bootstrap, PDO/SQLite wrapper, helpers, seed,
                       schema.org JSON-LD builders
templates/             layout.php + one template per section type
admin/                 Login, sections, editor, settings, leads, preview
assets/css/main.css    Design system (CSS variables, Grid, clamp())
assets/js/*.js         8 small vanilla ES modules (navigation, scroll,
                       animations, cursor, forms, accordion, lazyload,
                       reviews) loaded from main.js
assets/fonts/          Self-hosted Manrope + Space Grotesk (woff2)
data/                  SQLite database (gitignored, auto-created)
```

## Real photography

`assets/img/villa/` contains optimized photos of the actual property,
sourced from its official listing pages (hero, pool, terrace, bedroom,
kitchen, barbecue, living room, patio). All image slots are CMS-editable
paths, so photos can be swapped without touching code.

## PWA — works like an app on mobile

- `manifest.json` + maskable icons → installable ("Add to Home Screen"),
  runs standalone in portrait with a dark themed status bar.
- `sw.js` service worker → cache-first assets, network-first pages,
  offline fallback to the cached homepage. Admin and the enquiry
  endpoint are never intercepted.
- App-style fixed bottom tab bar on mobile (Home / Villa / Area / Call /
  Enquire) with active-section highlighting, safe-area insets for
  notched phones, no tap highlight flash, and no scroll rubber-banding.

## Frontend notes

- No frameworks, no build step, no external requests (fonts self-hosted).
- Animations are IntersectionObserver + CSS transitions; everything
  respects `prefers-reduced-motion` (parallax, cursor, magnetic buttons
  and reveals all disable).
- Custom cursor and magnetic buttons run only on fine-pointer desktop.
- WCAG 2.2 AA targets: semantic landmarks, skip link, visible focus,
  accessible accordion/menu/forms, `aria-current` section nav.
- SEO: single H1, JSON-LD (VacationRental + FAQPage), OpenGraph,
  canonical, meta description.
- The contact form has CSRF protection, a honeypot and a time-trap;
  it degrades gracefully without JavaScript.
