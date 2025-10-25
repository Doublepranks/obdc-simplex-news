# ObDC Simplex News

Lightweight, modular WordPress theme designed for small newsrooms. It mirrors the ObDC visual prototype with a focus on performance, accessibility, SEO, and an editorial workflow built around featured stories, a continuous feed, and audience ranking.

## Requirements

- WordPress 6.5 or newer (REST API enabled and pretty permalinks).
- PHP 8.0 or newer.
- Standard WordPress PHP extensions (curl, dom, gd).
- Permission to manage menus, widgets, and Customizer options.

## Overview

The front page is organised in five blocks: persistent LIVE bar, hero (sticky post), secondary highlights, continuous feed with “load more”, and the “Mais lidas” column. Components live in `template-parts/`, making it easy to reuse them elsewhere. The theme is based on the Underscores (`_s`) boilerplate with newsroom-focused tweaks.

## Key Features

- Responsive-first layout with hero + cards using Inter/Merriweather via Google Fonts (`wp_resource_hints` + `font-display: swap`).
- LIVE ticker configurable in the Customizer (status, text, CNPJ, city).
- Editorial panel powered by sticky posts with automatic fallback to recent posts.
- Infinite feed driven by REST (`js/front-page.js`) with `IntersectionObserver` and manual fallback.
- Author archive with progressive loading (`js/author-feed.js`) via the dedicated REST endpoint.
- Search result pages reuse the same infinite-scroll behaviour as the home feed.
- “Mais lidas” sidebar based on the `post_views` meta key; fallback helpers exist in `inc/helpers.php`.
- Ad slots ready in `template-parts/ads` (top/home feed) and via widgets.
- SEO: OG/Twitter cards (`inc/seo-meta.php`), canonical links, and Schema.org structured data (`inc/structured-data.php`).
- Accessibility enhancements (`inc/template-functions.php`) and translation-ready strings (`load_theme_textdomain`).
- Basic Jetpack integration stub (`inc/jetpack.php`).
- Restricted author archives: subscribers (and any roles filtered via `obdc_simplex_news_restricted_author_roles`) receive 404 on author pages, and only profile editing is allowed in wp-admin.

## Installation

1. Zip the theme or clone it into `wp-content/themes`.
2. In the WordPress dashboard go to `Appearance > Themes > Add New > Upload Theme`.
3. Activate “ObDC Simplex News”.
4. Visit `Settings > Permalinks` and ensure pretty permalinks are enabled (required for the REST endpoints).

## Initial Setup

1. Open `Appearance > Customize > Configurações do Tema` to configure the LIVE bar (status, CNPJ, city, YouTube API settings, fallback text).
2. Create menus for every registered location (`main`, `footer-news`, `footer-brazil`, `footer-site`, `footer-opinion`, `footer-sports`, `footer-entertainment`, `footer-social`).
3. Choose the sticky posts that will fill the hero and highlight slots.
4. Install a post-views plugin (e.g. Post Views Counter) or call `obdc_simplex_news_increment_post_views()` in `single.php`.
5. Replace `screenshot.png` with a 1200x900 preview for wp-admin.
6. Add `images/logo.png` and `images/og-default.jpg` to feed SEO/meta defaults (adjust sizes as needed).

## Editorial Content

- **Hero & highlights:** `template-parts/home/hero.php` and `template-parts/home/highlights.php` consume `obdc_simplex_news_get_front_page_featured_data()` (cached sticky posts with exclusion list) and respect Yoast primary categories when the plugin is active.
- **Home feed:** `front-page.php` builds the loop excluding featured IDs and exposes pagination data for JS.
- **Search:** `search.php` mirrors the infinite-scroll structure, consuming the search REST endpoint.
- **Cards:** `template-parts/content/card.php` defines the standard feed card layout.
- **Mais lidas:** `template-parts/sidebar/most-read.php` reads `post_views`; change the meta key if needed.
- **LIVE bar:** `template-parts/topbar.php` renders data from the YouTube integration (or fallback text) with animation.
- **Authors carousel:** `template-parts/front-page/authors-carousel.php` displays data from `obdc_simplex_news_get_featured_authors()`. Title defaults to “Equipe editorial” and can be filtered via `obdc_simplex_news_authors_heading`.

## Scripts & REST Endpoints

- `js/front-page.js`: manages “load more”, loading states, `IntersectionObserver`, and fallbacks. Reused on the home feed and search results.
- `js/author-feed.js`: extends the same behaviour to author archives.
- `js/navigation.js`, `js/footer-accordion.js`, `js/share.js`, `js/authors-carousel.js`, `js/topbar.js`: handle responsive menu/drawer, footer accordion, sharing utilities, authors carousel, and YouTube ticker overflow.
- `js/script.js`: legacy AJAX load-more prototype (admin-ajax). Not enqueued; keep only as reference or remove during refactors.
- REST endpoints registered in `functions.php` (generated as relative URLs):
  - `GET /wp-json/obdc-simplex-news/v1/front-page-feed?page={n}`
  - `GET /wp-json/obdc-simplex-news/v1/author-feed?author_id={ID}&page={n}` (legacy `author=` still works for compatibility)
  - `GET /wp-json/obdc-simplex-news/v1/search-feed?search={query}&page={n}`
  Each endpoint returns rendered HTML ready to inject in the DOM (`load more` scripts expect JSON with `html`, `maxPages`, `foundPosts`).
- Configurable autoload limits: `obdc_simplex_news_front_page_autoload_limit`, `obdc_simplex_news_author_autoload_limit`, `obdc_simplex_news_search_autoload_limit`.

## Development

- See `LOCAL_DEV.md` for Docker instructions (`docker compose up -d`). Defaults to `http://192.168.15.8:8080`; adjust `WP_HOME`/`WP_SITEURL` if needed.
- No build step: CSS/JS are enqueued from `style.css` and `js/*.js`.
- Generate new translations with `wp i18n make-pot` targeting the theme directory and output to `languages/`.

## Folder Structure (summary)

```
.
|-- inc/                # Helpers, SEO, Customizer, schema
|-- js/                 # Navigation, feeds, authors, share scripts
|-- template-parts/     # Reusable components (ads, cards, sidebar, etc.)
|-- author.php          # Author archive with async feed
|-- front-page.php      # Home with sticky posts + infinite feed
|-- functions.php       # Theme supports, menus, REST endpoints
|-- search.php          # Search results with infinite feed
|-- single.php          # Full article layout
|-- style.css           # Theme metadata & global styles
`-- README.md
```

## License

GPL-2.0-or-later.

## Author

Samuel Pantoja — https://www.obrasildecima.com.br
