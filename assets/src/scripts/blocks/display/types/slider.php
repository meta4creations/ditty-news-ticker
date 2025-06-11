<?php
// Count slides however makes sense for you—e.g. count 'ditty-item' in $content
$slide_count = substr_count( $content, 'ditty-item' );
// Or: pass slideCount into the block attributes via JS

$arrows = $attributes['arrows'] ?? false;
$bullets = $attributes['bullets'] ?? false;

$prev_id = (int) ( $attributes['arrowPrevIcon'] ?? 0 );
$next_id = (int) ( $attributes['arrowNextIcon'] ?? 0 );
$default_prev_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="24" height="24" fill="currentColor">
    <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/>
  </svg>';
$default_next_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="24" height="24" fill="currentColor">
    <path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/>
  </svg>';

function render_arrow_icon( $id, $default_svg ) {
  if ( $id && get_post_type( $id ) === 'attachment' ) {
    $mime = get_post_mime_type( $id );
    if ( $mime === 'image/svg+xml' ) {
      $path = get_attached_file( $id );
      if ( file_exists( $path ) ) {
        echo file_get_contents( $path );
        return;
      }
    }
    echo wp_get_attachment_image( $id, 'full', false, [
      'class' => 'dittySlider__arrow__icon',
      'alt'   => '',
    ] );
    return;
  }
  echo $default_svg;
}
?>

<?php if ( $arrows ) { ?>
<div class="dittySlider__arrows">
  <button class="dittySlider__arrow dittySlider__arrow--left" aria-label="Previous slide">
    <?php render_arrow_icon( $prev_id, $default_prev_svg ); ?>
  </button>
  <button class="dittySlider__arrow dittySlider__arrow--right" aria-label="Next slide">
    <?php render_arrow_icon( $next_id, $default_next_svg ); ?>
  </button>
</div>
<?php } ?>

<div class="dittySlider__slider keen-slider">
  <?php echo $content; ?>
</div>

<?php if ( $bullets ) { ?>
<div class="dittySlider__bullets">
  <?php for ( $i = 0; $i < $slide_count; $i++ ): ?>
    <button class="dittySlider__bullet" data-idx="<?php echo esc_attr( $i ); ?>"></button>
  <?php endfor; ?>
</div>
<?php } ?>
