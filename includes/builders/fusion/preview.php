<?php
/**
 * Underscore.js template.
 *
 * @package fusion-builder
 */

?>
<script type="text/template" id="fusion-builder-block-module-ditty-template">
	<h4 class="fusion_module_title"><span class="fusion-module-icon {{ fusionAllElements[element_type].icon }}"></span>{{ fusionAllElements[element_type].name }}</h4>
	<?php printf( esc_html__( 'Ditty: %s - Display: %s - Layout: %s', 'ditty-news-ticker' ), '{{ params.id }}', '{{ params.display }}', '{{ params.layout }}' ); ?>
</script>
