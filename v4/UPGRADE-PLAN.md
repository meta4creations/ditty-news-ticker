# Ditty V4 Upgrade Plan

## Overview

This document tracks the vision, progress, and roadmap for upgrading Ditty to version 4.0 - a major architectural overhaul focused on modern WordPress patterns, better user experience, and improved performance.

---

## Current Plugin Architecture (v3.x)

### Three Core Components

1. **Item Types (Content Sources)**
   - Default text, TinyMCE editor, HTML, WP Posts Feed (Lite)
   - Extensions: RSS, Instagram, Facebook, Images, Posts, Timing, XML

2. **Layouts**
   - HTML/CSS templates using merge tags (e.g., `{title}`, `{content}`, `{image}`)
   - Stored as `ditty_layout` custom post type
   - Tags processed via Thunder Shortcode library
   - Allows consistent formatting across different item types

3. **Displays**
   - Container types that render aggregated items
   - Types: Ticker, List, Slider (via extensions: Grid, Carousel)
   - Stored as `ditty_display` custom post type

### Current Technical Stack

- **Custom Post Types**: `ditty`, `ditty_layout`, `ditty_display`
- **Custom React Editor**: For creating/editing Ditty content
- **jQuery Display Classes**: ~4,800+ lines across ticker, list, slider
- **Front-end Rendering**: Shortcode, Gutenberg block, or global JavaScript embed

### Pain Points Identified

1. **JavaScript-dependent rendering** - Items load after page load (hurts SEO and perceived performance)
2. **jQuery dependency** - Works but feels dated
3. **Complex setup** - Not simple for users who want quick implementation
4. **Layout editing** - Requires HTML/CSS knowledge (barrier for many users)
5. **Custom slider implementation** - Maintenance burden vs. using established library

---

## V4 Progress So Far

### JavaScript/Frontend (`assets/src/v4/`)

#### `DittyTicker.js` - Vanilla JS Ticker (~648 lines)
- No jQuery dependency
- Works with pre-rendered PHP HTML (solves JS loading issue)
- Uses IntersectionObserver for visibility detection
- requestAnimationFrame for smooth animations
- Supports: left/right/up/down directions, hover pause, item cloning

#### `dittyV4.js` - Display Initialization
- Custom DittyTicker for ticker type
- Splide.js for slider/carousel/list types
- Parses config from `data-ditty-config` attribute
- Auto-initializes on DOMContentLoaded

#### `dittyV4.scss` - Unified Styles
- Base display container styles
- Ticker-specific styles (absolute positioning, transforms)
- Splide integration styles
- Title display variations

### PHP/Backend (`v4/`)

#### `class-ditty-v4-renderer.php` - Server-Side HTML Rendering
- Generates pre-rendered HTML (items in DOM on page load!)
- Builds container/contents/title/item inline styles
- Handles both ticker and Splide markup structures
- Enqueues v4 assets

#### `class-ditty-v4-layout-renderer.php` - Layout Processing
- Reuses existing `ditty_layout` posts
- Processes merge tags via Thunder Shortcode library
- Maintains compatibility with existing layouts
- CSS scoping for layouts in block context

#### `class-ditty-v4-blocks.php` - Block Registration
- Registers new v4 blocks

#### `class-ditty-v4-shortcodes.php` - Shortcode Support
- V4 shortcode alternatives (if needed)

#### `helpers.php` - Utility Functions
- Spacing preset resolution
- Other helper functions

### New Blocks (`assets/src/blocks/`)

#### `ditty-display` - Container Block
- Ticker or Slider type (via block variations)
- Uses InnerBlocks for child items
- Full Splide options exposed in inspector:
  - Layout: perPage, perMove, start, focus, dimensions
  - Navigation: loop, rewind, arrows, pagination
  - Animation: speed, easing, updateOnMove
  - Interaction: drag, snap
  - Autoplay: interval, pauseOnHover/Focus
- Edit/Preview toggle in block toolbar
- Provides context to child blocks via `usesContext`

#### `ditty-display-item` - Individual Item Block
- Receives context from parent display
- Contains InnerBlocks for content (paragraph, heading, etc.)
- Applies item styling from parent context

#### `ditty-posts-feed` - Dynamic Posts Block
- Uses existing `ditty_layout` posts for templating
- Renders via `Ditty_V4_Layout_Renderer`
- Limit control for number of posts
- Layout selector (PostControlDynamic component)

---

## Key V4 Architectural Improvements

1. **Server-side rendering** - Items are in HTML on page load (SEO friendly, better LCP)
2. **No jQuery** - Modern vanilla JS for ticker
3. **Splide.js** - Mature, well-tested slider library (replaces custom ~1600 line slider)
4. **Block-based approach** - Native WordPress editing experience
5. **InnerBlocks pattern** - Compose displays from standard blocks
6. **Reuses existing layouts** - Backward compatibility with `ditty_layout` posts

---

## Vision & Goals

### User Experience Goals
- [ ] Simple "add to site" experience for non-technical users
- [ ] Block-based editing as primary creation method
- [ ] Visual layout builder (reduce HTML/CSS requirement)
- [ ] Keep power-user features available

### Technical Goals
- [ ] Server-side rendered content (SEO/performance)
- [ ] Remove jQuery dependency
- [ ] Reduce JavaScript bundle size
- [ ] Maintain backward compatibility with v3 Ditty posts
- [ ] Extensible architecture for add-ons

### Display Types (Priority Order)
- [x] Ticker (vanilla JS - complete)
- [x] Slider/Carousel (Splide - complete)
- [ ] List (paged, non-sliding)
- [ ] Grid (extension consideration)

---

## Open Questions

1. **Layout System Evolution**
   - Keep HTML/CSS layouts as power-user option?
   - Add visual/block-based layout builder?
   - How to migrate existing layouts?

2. **Legacy Support**
   - How long to support v3 `ditty` post type?
   - Migration path for existing Ditty posts?
   - Deprecation timeline?

3. **Block Patterns/Templates**
   - Pre-built block patterns for common use cases?
   - Starter templates?

4. **Extensions**
   - How do extensions integrate with new block system?
   - API for custom item types as blocks?

---

## Implementation Phases

### Phase 1: Core Block Infrastructure (In Progress)
- [x] V4 renderer classes
- [x] Ticker display (vanilla JS)
- [x] Slider display (Splide)
- [x] ditty-display block
- [x] ditty-display-item block
- [x] ditty-posts-feed block
- [ ] Editor preview refinements
- [ ] Block variations polish

### Phase 2: Content Type Blocks
- [ ] Custom text/HTML item block
- [ ] Posts query block (advanced)
- [ ] RSS feed block
- [ ] Social feed blocks (extensions)

### Phase 3: Layout System
- [ ] Visual layout editor/builder
- [ ] Layout block patterns
- [ ] Layout migration tools

### Phase 4: Migration & Polish
- [ ] v3 to v4 migration wizard
- [ ] Documentation
- [ ] Performance optimization
- [ ] Accessibility audit

---

## Notes & Ideas

*Add ongoing notes here as the upgrade progresses...*

---

## File Reference

### V4 Core Files
- `v4/class-ditty-v4-renderer.php` - Display rendering
- `v4/class-ditty-v4-layout-renderer.php` - Layout tag processing
- `v4/class-ditty-v4-blocks.php` - Block registration
- `v4/class-ditty-v4-shortcodes.php` - Shortcode support
- `v4/helpers.php` - Utility functions

### V4 Frontend Files
- `assets/src/v4/DittyTicker.js` - Ticker class
- `assets/src/v4/dittyV4.js` - Initialization
- `assets/src/v4/dittyV4.scss` - Styles

### V4 Blocks
- `assets/src/blocks/ditty-display/` - Container block
- `assets/src/blocks/ditty-display-item/` - Item block
- `assets/src/blocks/ditty-posts-feed/` - Posts feed block

### Legacy Files (to eventually deprecate)
- `assets/src/class-ditty-display-ticker.js` - jQuery ticker (~1975 lines)
- `assets/src/class-ditty-display-list.js` - jQuery list (~1232 lines)
- `assets/src/class-ditty-slider.js` - jQuery slider (~1607 lines)
