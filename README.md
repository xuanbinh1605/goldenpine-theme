# Goldenpine WordPress Theme

A modular, scalable WordPress custom theme built following best practices for long-term maintainability.

**Version:** 1.0.0  
**Requires WordPress:** 6.0+  
**Requires PHP:** 8.0+  
**License:** GPL v2 or later

---

## 📁 Theme Structure

```
goldenpine-theme/
│
├── style.css                 # Theme header (required by WordPress)
├── functions.php             # Theme entry point — loads all inc/ files
├── index.php                 # Fallback template (blog loop)
├── header.php                # Global header wrapper
├── footer.php                # Global footer wrapper
├── page.php                  # Default page template
├── single.php                # Single post template
├── archive.php               # Archive template
├── search.php                # Search results template
├── 404.php                   # 404 error page
├── front-page.php            # Static front page
├── page-about.php            # About page template
├── page-services.php         # Services page template
├── page-contact.php          # Contact page template
│
├── assets/
│   ├── css/
│   │   ├── base/             # Variables, typography, global, utilities
│   │   ├── components/       # Buttons, cards, forms, modals, alerts
│   │   └── layout/           # Header, footer, navigation, grid, sidebar
│   │
│   ├── js/
│   │   ├── common/           # Main.js, utils.js, navigation.js (every page)
│   │   ├── customizer-js/    # Customizer live preview
│   │   └── page-specific-js/ # Front-page.js, about.js, services.js, contact.js
│   │
│   ├── images/               # Theme images (icons, hero backgrounds, etc.)
│   └── fonts/                # Self-hosted web fonts
│
├── inc/
│   ├── setup.php             # Theme supports, image sizes, nav menus, widgets
│   ├── enqueue.php           # Enqueue all CSS & JS
│   │
│   ├── admin/
│   │   ├── admin-columns.php # Custom admin list-table columns
│   │   └── admin-settings.php# Dashboard widgets, admin customizations
│   │
│   ├── ajax/
│   │   └── ajax-handlers.php # wp_ajax_ action handlers
│   │
│   ├── customizer/
│   │   ├── customizer-setup.php      # Main panel registration
│   │   ├── customizer-colors.php     # Color palette settings
│   │   ├── customizer-typography.php # Typography settings
│   │   └── customizer-home.php       # Front-page hero & sections
│   │
│   ├── helpers/
│   │   └── helper-functions.php # Reusable utility functions
│   │
│   ├── post-types/
│   │   └── cpt-portfolio.php # Portfolio custom post type (example)
│   │
│   ├── taxonomies/
│   │   └── taxonomy-portfolio-category.php # Portfolio taxonomy (example)
│   │
│   └── integrations/
│       └── class-integrations.php # WooCommerce, Yoast SEO, CF7 compatibility
│
├── template-parts/
│   ├── global/               # Site header, footer, breadcrumbs
│   ├── components/           # CTA section, post card (reusable partials)
│   ├── front-page/           # Hero, features, testimonials, stats
│   ├── about-page/           # About hero, values, team
│   ├── services-page/        # Services hero, services list
│   ├── contact-page/         # Contact hero, form, info, map
│   └── other-pages/          # Placeholder for future page sections
│
├── page-templates/
│   ├── template-full-width.php   # Full-width page (no sidebar)
│   ├── template-landing-page.php # Minimal header/footer for campaigns
│   └── template-no-sidebar.php   # Standard page without sidebar
│
└── languages/
    └── goldenpine-theme.pot  # Translation template
```

---

## 🚀 Getting Started

### Installation

1. **Upload the theme folder** to `/wp-content/themes/goldenpine-theme/`
2. **Activate** the theme in **Appearance → Themes**
3. **Set up menus** in **Appearance → Menus**:
   - Primary Navigation
   - Footer Column 1, 2, 3
4. **Configure settings** in **Appearance → Customize → Goldenpine Theme**

### Initial Configuration

#### 1. Homepage Setup
- Go to **Settings → Reading**
- Set **"A static page"** and select your front page

#### 2. Navigation Menus
- Create a primary menu and assign it to **"Primary Navigation"**
- Optionally create footer menus for the three footer columns

#### 3. Customizer Settings
- **Goldenpine Theme → Colours**: Adjust primary, secondary, accent colors
- **Goldenpine Theme → Typography**: Font size and family
- **Goldenpine Theme → Home Page**: Hero content, section toggles

#### 4. Create Core Pages
Create these pages and assign the appropriate templates:
- **About** → Assign "About Page" template
- **Services** → Assign "Services Page" template
- **Contact** → Assign "Contact Page" template

---

## 📂 How to Add New Content

### Adding a New Page

1. **Create the root page template** (e.g., `page-portfolio.php`) in the theme root
2. **Create a folder** in `template-parts/` (e.g., `template-parts/portfolio-page/`)
3. **Add section partials** inside that folder (e.g., `portfolio-hero.php`, `portfolio-grid.php`)
4. **Load sections** in your page template using `get_template_part()`
5. **Create page-specific JS** in `assets/js/page-specific-js/portfolio.js`
6. **Enqueue conditionally** in `inc/enqueue.php` using `is_page('portfolio')`

### Adding CSS

**Base styles** (variables, utilities):
- Add to `assets/css/base/`

**Reusable components** (buttons, cards):
- Add to `assets/css/components/`

**Layout modules** (header, footer):
- Add to `assets/css/layout/`

**Enqueue** the new file in `inc/enqueue.php`

### Adding JavaScript

**Global functionality** (runs on every page):
- Add to `assets/js/common/`
- Enqueue in the main section of `goldenpine_enqueue_assets()`

**Page-specific** (runs on one page):
- Add to `assets/js/page-specific-js/`
- Enqueue conditionally using `if ( is_page('slug') )`

### Adding a Custom Post Type

1. **Duplicate** `inc/post-types/cpt-portfolio.php`
2. **Rename** and update all labels and slugs
3. **Require** the new file in `functions.php`
4. **Visit** Settings → Permalinks (flush rewrite rules)

### Adding a Custom Taxonomy

1. **Duplicate** `inc/taxonomies/taxonomy-portfolio-category.php`
2. **Update** labels, slug, and associated post type
3. **Require** the new file in `functions.php`

### Adding Customizer Settings

1. **Create a new file** in `inc/customizer/` (e.g., `customizer-footer.php`)
2. **Register settings** using `$wp_customize->add_section()`, `add_setting()`, `add_control()`
3. **Require** the new file in `functions.php`
4. **Output** the values in your template using `get_theme_mod()`

### Adding AJAX Handlers

1. **Add handler function** to `inc/ajax/ajax-handlers.php`
2. **Verify nonce**, sanitize input, process, send JSON response
3. **Register actions**: `add_action( 'wp_ajax_...', 'your_function' )`
4. **Call from JS** using `fetch( ajaxUrl, { ... } )`

---

## 🎨 Customization

### CSS Variables

All design tokens live in `assets/css/base/_variables.css`:
- Colors
- Typography scale
- Spacing scale
- Border radii
- Shadows
- Z-index layers

Change values there to update the entire theme instantly.

### Utility Classes

Pre-built atomic classes in `assets/css/base/_utilities.css`:
- Display: `.d-flex`, `.d-none`, `.d-block`
- Flex: `.flex-row`, `.items-center`, `.justify-between`
- Spacing: `.mt-4`, `.mb-8`, `.p-6`, `.gap-4`
- Text: `.text-center`, `.text-lg`, `.font-bold`
- Colors: `.text-primary`, `.bg-alt`

### Helper Functions

Reusable PHP utilities in `inc/helpers/helper-functions.php`:
- `goldenpine_get_excerpt( $length, $more )` — Trimmed excerpt
- `goldenpine_section_open( $id, $classes )` — Section wrapper
- `goldenpine_get_option( $setting, $default )` — Customizer value
- `goldenpine_image_url( $filename )` — Asset URL
- `goldenpine_breadcrumbs()` — Breadcrumb nav

---

## 🔌 Plugin Integration

### WooCommerce
Supported out-of-the-box. Theme wrappers and product gallery features enabled in `inc/integrations/class-integrations.php`.

### Yoast SEO
Breadcrumb support enabled. Use `yoast_breadcrumb()` in templates.

### Contact Form 7
Default styles disabled — the theme's form styles apply automatically.

---

## 📋 Best Practices

### Separation of Concerns
- **Business logic** → `inc/`
- **Presentation** → `template-parts/`
- **Styles** → `assets/css/`
- **Behavior** → `assets/js/`

### Naming Conventions
- **CSS classes**: BEM (Block__Element--Modifier)
- **PHP functions**: `goldenpine_` prefix
- **JS objects**: `goldenpine` + PascalCase (e.g., `goldenpineUtils`)
- **Hooks**: `goldenpine_` prefix

### When the Site Grows
- Add new page folders to `template-parts/`
- Split large CSS files into smaller component files
- Create dedicated JS modules for complex features
- Use Customizer repeater fields or ACF for dynamic content

---

## 🐛 Debugging

### Common Issues

**"Required file not found"**
- Check that all files in `functions.php` exist
- Verify file paths are correct (case-sensitive on Linux servers)

**Styles not loading**
- Hard refresh (Ctrl+Shift+R)
- Check `inc/enqueue.php` for correct paths
- Verify file permissions (644 for files, 755 for folders)

**AJAX not working**
- Verify nonce is being passed correctly
- Check `wp_ajax_` action names match JS
- Look in browser console for errors

**Customizer live preview not updating**
- Check `transport => 'postMessage'` is set
- Verify JS bindings in `assets/js/customizer-js/customizer-preview.js`

---

## 📝 License

This theme is licensed under the GPL v2 or later.

---

## 🤝 Support

For theme documentation and support, visit [https://goldenpine.com](https://goldenpine.com)

---

**Built with care by the Goldenpine Team** 🌲
