# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

ClCar is a RadiusTheme WordPress **theme** (text domain `clcar`, namespace `radiustheme\ClCar`) for car classified listings, built on top of the **Classified Listing** plugin family (`classified-listing`, `classified-listing-toolkits`/Pro, Store, Booking, Review Schema). The companion plugin **ClCar Core** lives in the sibling repo `../clcar-core` (Elementor widgets, demo importer, post meta used by layout settings). There is no PHP build step, test suite, or linter in this repo — code runs directly inside a WordPress install.

## Commands

- `npm run zip` — runs `scripts/zip.js`, which packages the theme into `dist/clcar.<Version>.zip`. The version is read from the `Version:` header in `style.css`; `.git`, `scripts/`, `dist/`, `package.json`, `CLAUDE.md` are excluded.

## Releases / versioning

- Theme version lives in `style.css` (`Version:`); `Constants::$theme_version` reads it at runtime and it is used as the asset cache-buster in `inc/scripts.php`.
- The bundled ClCar Core version is pinned in `inc/tgm-config.php` (`source` => `clcar-core.<ver>.zip`, `version`). The zip itself goes in `plugin-bundle/` (zips are git-ignored). Keep this in sync with `../clcar-core/clcar-core.php` `Version:` when releasing.
- Release commits typically touch only `style.css` + `inc/tgm-config.php` ("Released Version: x.y.z").
- `readme.txt` holds a loose changelog (`Fixed:` / `Added:` / `Updated:` lines) without dated version blocks.

## Architecture

**Bootstrap chain:** `functions.php` (defines `CLCAR_*_URL` constants, `ClCar_Main`) → `inc/constants.php` → `inc/helper.php` → `inc/includes.php`, which loads everything else. `includes.php` conditionally loads:
- `inc/updater/*` (license/theme updater) only when `RT_DEBUG` is not defined/true — define `RT_DEBUG` locally to skip licensing.
- `woo-functions.php` / `woo-hooks.php` only if WooCommerce is active.
- `classified-listing/custom/{functions,make_model_year,shortcode}.php` only if `class_exists( 'Rtcl' )`.

**File loading goes through `Helper`**, which is child-theme aware: `Helper::requires( $file, $dir )` and `Helper::get_template_part()` check `get_stylesheet_directory()` first, then the parent theme. Use these rather than raw `require`/`get_template_part` so child themes can override.

**Options / settings flow:**
- Customizer settings are defined in `inc/customizer/settings/*.php` with defaults in `inc/customizer/customizer-default-data.php` (`rttheme_generate_defaults()`). `RDTheme::$options` (`inc/rdtheme.php`) is populated from `get_theme_mod()` on `after_setup_theme`. A new option needs both a setting and a default.
- `inc/layout-settings.php` (`Layouts`, on `template_redirect`) resolves per-page values — layout, sidebar, header/footer style, banner/breadcrumb, paddings — into the static props on `RDTheme` (`RDTheme::$layout`, `::$header_style`, …), falling back from post meta (set via ClCar Core) to the global Customizer options per context (page, blog, listing archive/single, store archive/single).
- Customizer-driven CSS (colors, typography) is generated in PHP in `inc/dynamic-styles/` and attached inline from `inc/scripts.php`.

**Classified Listing integration (`classified-listing/`):** This directory is the plugin's template-override folder — files here mirror the plugin's template paths (e.g. `single-rtcl_listing.php`, `listing/…`, `myaccount/…`, `store/…`, `elementor/…`) and replace the plugin's templates. Theme-specific logic and hooks into `rtcl_*` filters/actions live in `classified-listing/custom/functions.php` (`Listing_Functions`, large file), with car Make → Model → Year logic in `custom/make_model_year.php`. Files suffixed `-old`/`2` are legacy and generally unused. When touching code that calls into Rtcl/RtclPro/RtclStore/Booking classes, guard with `class_exists`/`method_exists`, since those plugins may be absent or on different versions.

**Assets:** No SCSS/JS build — CSS and JS in `assets/` are edited directly. RTL is a separate, hand-maintained copy in `assets/css-rtl/` (`default`, `listings`, `styles`) loaded via `Helper::get_maybe_rtl_css()`; when changing LTR rules in those three files, mirror the change in the RTL file. Custom icon font is in `assets/custom-icons/` (fontello).

## ClCar Core plugin (`../clcar-core`)

A separate git repo (own branch; check it with `git -C ../clcar-core branch --show-current`), text domain `clcar-core`, namespace `radiustheme\ClCar_Core`. Entry point `clcar-core.php` defines `CLCAR_CORE` (version), `CLCAR_CORE_BASE_DIR/URL`, `CLCAR_CORE_THEME_PREFIX` (`clcar`) and the `ClCar_Core` class. What it provides and how it loads:

- On `clcar_theme_init` (fired at the end of the theme's `ClCar_Main::includes()`) and on `init`: `lib/sidebar-generator`, `lib/wp-svg`, `inc/shortcode.php`, `inc/hooks.php` (`Plugins_Hooks`: nav-menu item fields plus many `rtcl_el_*` / `el_listing_widget_*` filters that add theme styles to Classified Listing's Elementor widgets), and widgets in `widgets/`.
- `elementor/init.php` (only if Elementor is loaded): registers theme widgets via a `dirname => ClassName` map in `Custom_Widget_Init::init()`. Rtcl-dependent widgets are added only when `class_exists( 'Rtcl' )`. Each widget is `elementor/<dir>/class.php` extending `Custom_Widget_Base` (`elementor/base.php`) with `view*.php` templates. `rt_template()` lets the theme or child theme override a view at `elementor-custom/<dir>/<view>.php`.
- `inc/post-meta.php` (only if the **RT Framework** plugin is active, `RT_FRAMEWORK_VERSION`): the "Layout Settings" meta box saving `clcar_layout_settings` post meta, which the theme's `inc/layout-settings.php` reads.
- `demo-importer/` (Easy Demo Importer config, `demo-files/`), `demo-users/` (user importer and exporter), `optimization/` (asset optimization classes), and `ClCar_Core::social_share()`, which the theme calls from `template-parts/content-single.php`.

**Theme ↔ core dependency.** Each side calls into the other: the theme guards with `class_exists( 'ClCar_Core' )`, and the core uses `radiustheme\ClCar\Helper::*` and `RDTheme::$options` heavily (e.g. `Helper::custom_sidebar_fields()`, `get_advanced_search_*_html()`, `time_elapsed_string()`). Follow the global cross-plugin rule: when adding a theme `Helper` method for the core (or the reverse), guard the call (`class_exists( 'radiustheme\ClCar\Helper' )` / `method_exists`) instead of duplicating it. Also bump the pinned core version in this theme's `inc/tgm-config.php` when the core's `Version:` changes.

**Translations:** `languages/` contains `.po/.mo` plus `.l10n.php` files for several locales; all strings must use the `clcar` text domain.
