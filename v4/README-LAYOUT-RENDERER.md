# Ditty V4 Layout Renderer

## Overview

The `Ditty_V4_Layout_Renderer` class provides a simplified, block-friendly system for rendering content using existing `ditty_layout` posts. This system is designed to work seamlessly with WordPress blocks while reusing the proven layout tag processing from the core Ditty system.

## Features

- **Layout Reuse**: Leverages existing `ditty_layout` posts with `_ditty_layout_html` and `_ditty_layout_css` metadata
- **Tag Processing**: Uses Thunder Shortcode library to process layout tags like `{image}`, `{title}`, `{content}`, etc.
- **CSS Management**: Automatically compiles and scopes CSS per layout ID
- **Performance**: Caches layout data and compiled CSS to minimize database queries
- **Filter Integration**: Works with all existing `ditty_layout_tag_*` filters from `includes/layout-tag-hooks-posts.php`

## Usage

### Basic Example (Posts Feed Block)

The posts-feed block demonstrates the basic usage pattern:

```php
// Initialize the renderer
$renderer = new Ditty_V4_Layout_Renderer();

// Track unique styles
$styles_by_layout = array();

// Render each post with a layout
foreach ( $posts as $post ) {
    $result = $renderer->render_post_with_layout( $post, $layout_id );

    // Collect CSS (once per unique layout)
    if ( ! isset( $styles_by_layout[ $result['layout_id'] ] ) ) {
        $styles_by_layout[ $result['layout_id'] ] = $result['css'];
    }

    // Output the rendered HTML
    echo '<div class="ditty-display__item ditty-layout--' . esc_attr( $result['layout_id'] ) . '">';
    echo $result['html'];
    echo '</div>';
}

// Output collected CSS
if ( ! empty( $styles_by_layout ) ) {
    echo '<style type="text/css">';
    foreach ( $styles_by_layout as $css ) {
        echo $css;
    }
    echo '</style>';
}
```

### Using with Custom Post Types

```php
$renderer = new Ditty_V4_Layout_Renderer();

// Render a custom post type with a specific item type
$result = $renderer->render_post_with_layout(
    $custom_post,
    $layout_id,
    'custom_item_type'
);

echo $result['html'];
```

### Return Value Structure

The `render_post_with_layout()` method returns an array with:

- `html` (string): The processed HTML with all tags replaced
- `css` (string): The compiled and scoped CSS for this layout
- `layout_id` (int): The layout ID used for rendering

## Supported Layout Tags

When using `item_type = 'posts_feed'`, these tags are available:

- `{image}` - Featured image
- `{title}` - Post title
- `{content}` - Post content
- `{excerpt}` - Post excerpt
- `{author_avatar}` - Author avatar
- `{author_name}` - Author name
- `{author_bio}` - Author bio
- `{categories}` - Post categories
- `{time}` - Post date/time
- `{permalink}` - Post URL
- `{icon}` - WordPress icon
- `{image_url}` - Featured image URL

All tags support standard Ditty attributes like `link`, `wrapper`, `before`, `after`, etc.

## Integration with Existing System

The renderer is designed to work alongside the existing Ditty system without conflicts:

1. **Filters**: All existing `ditty_layout_tag_*` filters continue to work
2. **CSS Compilation**: Uses the same `Ditty_Layouts::compile_layout_style()` method
3. **Tag System**: Leverages `ditty_layout_tags()` for tag definitions
4. **Rendering**: Uses `ditty_layout_render_tag()` for consistent output

## Performance Considerations

- Layout data is cached per layout ID to avoid repeated database queries
- CSS is compiled once per layout and cached
- Use `$renderer->clear_cache()` if layouts are updated during execution

## Extending for Other Blocks

To use the renderer in other blocks:

1. Ensure the renderer class is loaded (already done in `Ditty_V4_Blocks`)
2. Initialize the renderer in your block's render.php
3. Call `render_post_with_layout()` for each item
4. Collect and output CSS at the end

Example for a custom block:

```php
$renderer = new Ditty_V4_Layout_Renderer();
$result = $renderer->render_post_with_layout( $my_post, $layout_id, 'my_custom_type' );
echo $result['html'];
echo '<style>' . $result['css'] . '</style>';
```

## File Structure

- **Renderer Class**: `v4/class-ditty-v4-layout-renderer.php`
- **Initialization**: `v4/class-ditty-v4-blocks.php`
- **Example Usage**: `assets/src/blocks/ditty-posts-feed/render.php`

## Error Handling

If a layout is not found or invalid, the renderer returns:

```php
array(
    'html' => '<p>Layout not found</p>',
    'css' => '',
    'layout_id' => $layout_id,
)
```

This ensures graceful degradation when layouts are missing.
