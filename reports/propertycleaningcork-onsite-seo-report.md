# On-Site SEO Optimisation Report — propertycleaningcork.ie

**Prepared by:** Crest Web Media (crestwebmedia.com)
**Date:** 13 August 2026
**Scope:** Full on-site SEO overhaul — titles, meta descriptions, schema markup, internal linking, new location pages, and Core Web Vitals optimisation.

---

## Executive summary

Property Cleaning Cork's website had strong content but was held back by technical SEO problems: bloated duplicate title tags, broken meta descriptions, incorrect schema markup (the site was telling Google it was a *person*, not a local business), dead internal links, and completely unoptimised page delivery (40 render-blocking stylesheets, no minification, no deferred JavaScript).

Every one of those problems has been fixed, and the site's footprint across Cork County has been expanded with six new location pages built on the existing design. The work was verified live, page by page, after completion.

**Headline results**

| Area | Before | After |
|---|---|---|
| Page titles | 70–92 chars, duplicated branding | 30 unique titles, all 33–59 chars |
| Meta descriptions | 9 pages showed "4.9 / 5.0 on Trustpilot"; others truncated auto-text | 30 unique, benefit-led descriptions, 74–153 chars |
| Schema | Person + wrong Article schema on every page | LocalBusiness (HomeAndConstructionBusiness) + Service on 19 pages + FAQPage on 7 pages |
| Location pages | 6 (Cork City, Douglas, Cobh, Ballincollig, Midleton, Carrigaline) | 12 — added Kinsale, Mallow, Fermoy, Youghal, Bandon, Glanmire |
| Broken links | 4 dead/mislabeled internal links incl. main menu | 0 |
| Render-blocking Google Fonts | 3 full-weight families, blocking | Async with preconnect + font-display: swap |
| CSS/JS/HTML minification | Off | On (all 37 stylesheets minified, 41 scripts deferred) |
| Rank Math site audit | — | 78/100 ("good") after changes |

---

## 1. Site identity & knowledge graph (site-wide fix)

The single biggest problem: the WordPress **site title** was set to the keyword string *"Exterior Cleaning Cork | Pressure Washing, Roof & Gutters"*. Because every page template appends the site title, every page on the site carried that 57-character suffix — producing titles like:

> *"Roof Cleaning Cork | Exterior Cleaning Cork | Pressure Washing, Roof & Gutters"* (82 chars — truncated in Google)

**Fixed:**
- Site title → **Property Cleaning Cork** (the actual brand)
- Tagline → *Exterior Cleaning & Pressure Washing Specialists in Cork*
- Rank Math knowledge graph switched from **Person** ("Radoslaw") to **Organisation**, typed as **HomeAndConstructionBusiness** (a Google-recognised LocalBusiness type)
- Organisation name corrected from the old keyword-stuffed string to *Property Cleaning Cork*, with legal name, customer-support phone number (+353 87 708 1460), and opening hours retained
- Website schema `name`/`alternateName` corrected

Every page on the site now emits a proper LocalBusiness entity that Google can connect to reviews, maps, and local search.

## 2. Titles & meta descriptions (30 URLs rewritten)

All 23 pages and 7 blog posts received hand-written titles (≤60 chars, primary keyword first, brand where space allows) and meta descriptions (~130–155 chars, benefit-led, with trust signals — insured, 4.9★ Trustpilot, free quotes — and Irish spelling throughout). Focus keywords were set in Rank Math for every commercial page.

Examples:

| Page | Before (title) | After |
|---|---|---|
| Roof Cleaning | Roof Cleaning Cork \| Exterior Cleaning Cork \| Pressure Washing, Roof & Gutters | Roof Cleaning Cork \| Moss Removal & Roof Washing |
| Douglas | Douglas \| Exterior Cleaning Cork \| Pressure Washing, Roof & Gutters | Property Cleaning Douglas \| Pressure Washing & More |
| Contact | Contact \| Exterior Cleaning Cork \| Pressure Washing, Roof & Gutters (desc: "4.9 / 5.0 on Trustpilot") | Contact Us \| Free Quote \| Property Cleaning Cork (desc: full CTA with phone number) |

Nine pages whose meta description was literally *"4.9 / 5.0 on Trustpilot"* (23 chars) now have real descriptions.

## 3. Schema markup

- **Removed** the incorrect *Article* schema (with a personal author) that was auto-applied to every page — pages are not articles.
- **Added Service schema** to all 13 service pages and 6 location pages: service type, provider (Property Cleaning Cork with phone), and `areaServed` (County Cork, or the specific town for location pages).
- **Added FAQPage schema** to the 7 service pages that have on-page FAQ accordions (pressure washing, soft washing, roof, gutter, driveway, stone, exterior cleaning) — built from the actual questions and answers on each page, making these pages eligible for FAQ rich results.
- Blog posts correctly retain BlogPosting/Article schema.

## 4. Internal linking

- **Fixed 4 broken/mislabeled links:** the main menu and footer "Douglas" links pointed to a dead URL (404); a blog post linked to a malformed URL (`/-cleaning-cork` — 404); two blog links had swapped labels ("soft washing" pointing at roof cleaning, etc.); footer Privacy link pointed at a redirect.
- **Blog → service links:** four posts had zero internal links; contextual links to the relevant service pages were added in-copy (soft washing, pressure washing, stone restoration, roof cleaning, home, contact).
- **Location page → service links:** the Douglas page (and all six new town pages) now link contextually from their service descriptions to the four main service pages.
- **Footer "Our Reach"** expanded from 5 to 12 location links — every location page is now linked site-wide.
- **Main menu** "Areas We Cover" expanded with the six new towns.
- Repaired template leftovers on the Douglas page: buttons labeled "REVENUE"/"Open Account" (finance-template leftovers) renamed, empty "We Cover" buttons now link to the Cork City page, and truncated copy ("a sandstone  in", "damage my driveway or ?") repaired.

## 5. New Cork County location pages (6 built)

Built on the exact Elementor pattern/design of the existing Douglas page, with unique locally-written copy (real landmarks, estates, and the specific cleaning challenges of each town — salt air in Kinsale/Youghal, Blackwater valley damp in Mallow/Fermoy, wooded glens in Glanmire):

| Town | URL |
|---|---|
| Kinsale | /property-cleaning-kinsale/ |
| Mallow | /property-cleaning-mallow/ |
| Fermoy | /property-cleaning-fermoy/ |
| Youghal | /property-cleaning-youghal/ |
| Bandon | /property-cleaning-bandon/ |
| Glanmire | /property-cleaning-glanmire/ |

Each has: optimised title/description/focus keyword, Service schema with town-level `areaServed`, a Google Map of the town, contextual service links, main-menu and footer links, and inclusion in the XML sitemap (verified).

## 6. Core Web Vitals

The site runs **LiteSpeed Cache 7.9** (note: not Breeze — the server is LiteSpeed and the LiteSpeed Cache plugin was already installed and half-configured; page caching was on but every optimisation feature was off).

Enabled (scoped to guest visitors, so admin editing is unaffected):

- **CSS minification** — all 37 stylesheets now served minified from the LiteSpeed cache
- **JS minification + defer** — all 41 scripts deferred (jQuery correctly excluded to avoid breakage)
- **HTML minification** — homepage HTML down from 188 KB to 169 KB
- **Google Fonts async** — 3 render-blocking font stylesheets (each loading all 18 weights of Inter, Rubik and Noto Sans) now load asynchronously with `preconnect` to fonts.gstatic.com and `font-display: swap` (fixes invisible-text-while-loading)
- **WordPress emoji script removed**
- **Missing image width/height attributes auto-added** (reduces layout shift / CLS)
- **DNS prefetch** enabled site-wide
- Alt text added to the 4 homepage images that had none (accessibility + image SEO)
- Duplicate H1 on the homepage demoted to H2 (every page now has one H1; the six new pages carry a hidden theme-title heading noted below)

Deliberately **not** enabled (would risk breaking the site or require external services): CSS combine, JS delay, Unique/Critical CSS generation (requires QUIC.cloud), and image WebP conversion (images are already largely WebP).

## 7. Verification

After completion, all 30 indexable URLs were re-crawled: every title is 33–59 characters, every description in range, Service schema present on all 19 commercial pages, FAQPage on 7, LocalBusiness on every page. Rank Math's built-in site audit scores the site **78/100 ("good")**.

## Recommendations (next steps)

1. **Connect Google Search Console** in Rank Math (needs a Google login) and submit the sitemap — this is the one audit item we could not complete from the outside.
2. **Footer social links** are placeholders (`#`) — point them at the real Facebook/Instagram/WhatsApp profiles.
3. **Google Business Profile:** create/claim one per service area cluster; the new LocalBusiness schema will reinforce it.
4. The **theme page-title bar** on the six new pages is hidden with CSS (the theme stores its per-page "disable title" option in a proprietary metabox that resists programmatic saving). Cosmetically identical to the other pages; if desired, open each of the six pages in the WordPress editor and set Page Options → Page Title → Disable, then the CSS snippet can be removed.
5. Consider **renaming the WP page titles** of the older location pages (e.g. "Douglas" → "Property Cleaning Douglas") to clear the remaining Rank Math "keyword in title" audit flags — cosmetic only, as the SEO titles are already correct.
6. The **carpet/fibre-care blog post** is off-topic for an exterior cleaning site; consider repurposing it toward stone/patio care or removing it.
7. **Plugin updates:** Gravity Forms is at 2.5.0.1 (quite old) — update for security.
8. A **duplicate-content note:** the exterior-cleaning page reuses the roof page's FAQ answers; worth writing unique FAQs for it in future.
