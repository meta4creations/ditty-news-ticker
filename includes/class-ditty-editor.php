<?php

/**
 * Ditty Editor Class
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Editor
 * @copyright   Copyright (c) 2021, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
 */
class Ditty_Editor {

	/**
	 * Get things started
	 * 
	 * @access  public
	 * @since   3.0
	 */
	public function __construct() {
	}

	/**
	 * Get all display data for the editor
	 *
	 * @access public
	 * @since  3.1
	 */
	public function display_data() {
		$args = array(
			'posts_per_page' => -1,
			'orderby' 		=> 'post_title',
			'order' 			=> 'ASC',
			'post_type' 	=> 'ditty_display',
		);
		$posts = get_posts($args);

		$display_data = array();
		if (is_array($posts) && count($posts) > 0) {
			foreach ($posts as $i => $post) {
				$display_type = get_post_meta($post->ID, '_ditty_display_type', true);
				$display_settings = get_post_meta($post->ID, '_ditty_display_settings', true);
				if (!is_array($display_settings)) {
					$display_settings = array();
				}
				$display_data[] = array(
					'id' => $post->ID,
					'type' => $display_type,
					'title' => $post->post_title,
					'description' => get_post_meta($post->ID, '_ditty_display_description', true),
					'settings' => $display_settings,
					'version' => get_post_meta($post->ID, '_ditty_display_version', true),
					'edit_url' => get_edit_post_link($post->ID, 'code'),
				);
			}
		}
		return $display_data;
	}

	/**
	 * Get all layout data for the editor
	 *
	 * @access public
	 * @since  3.1
	 */
	public function layout_data() {
		$args = array(
			'posts_per_page' => -1,
			'orderby' 		=> 'post_title',
			'order' 			=> 'ASC',
			'post_type' 	=> 'ditty_layout',
		);
		$posts = get_posts($args);

		$layout_data = array();
		if (is_array($posts) && count($posts) > 0) {
			foreach ($posts as $i => $post) {
				$layout_data[] = array(
					'id' => $post->ID,
					'title' => $post->post_title,
					'description' => get_post_meta($post->ID, '_ditty_layout_description', true),
					'html' => get_post_meta($post->ID, '_ditty_layout_html', true),
					'css' => get_post_meta($post->ID, '_ditty_layout_css', true),
					'version' => get_post_meta($post->ID, '_ditty_layout_version', true),
					'edit_url' => get_edit_post_link($post->ID, 'code'),
				);
			}
		}
		return $layout_data;
	}

	/**
	 * Get all item type data for the editor
	 *
	 * @access public
	 * @since  3.1
	 */
	public function item_type_data() {
		$item_types = ditty_item_types();
		$item_type_data = array();
		if (is_array($item_types) && count($item_types) > 0) {
			foreach ($item_types as $i => $type) {
				$item_type_object = ditty_item_type_object($type['type']);
				$default_settings = $item_type_object->default_settings();
				$fields = $this->format_js_fields($item_type_object->fields($default_settings));

				$first_field = reset( $fields );
				if ( isset( $first_field['type'] ) ) {
					$settings = [[
						'id' => 'settings',
						'label' => __("Settings", "ditty-news-ticker"),
						'name' => __("Settings", "ditty-news-ticker"),
						'description' => sprintf( __( 'Configure the settings of the %s item.', "ditty-news-ticker" ), $type['label'] ),
						'icon' => 'fas fa-sliders',
						'fields' => $fields,
					]];
				} else {
					$settings = $fields;
				}
				$item_type = [
					'id' => $type['type'],
					'icon' => $type['icon'],
					'label' => $type['label'],
					'description' => $type['description'],
					'settings' => $settings,
					'defaultValues' => $default_settings,
					'layoutTags' => array_values( $item_type_object->get_layout_tags() ),
					'layoutVariations' => $item_type_object->get_layout_variations(),
					'defaultLayout' => $item_type_object->default_layout(),
				];
				$item_type_data[] = $item_type;
			}
		}
		//echo '<pre style="height:100%;overflow:scroll;">';print_r($item_type_data);echo '</pre>';
		return array_values($item_type_data);
	}

	/**
	 * Get all display type data for the editor
	 *
	 * @access public
	 * @since  3.1
	 */
	// public function display_type_data() {
	// 	$display_types = ditty_display_types();
	// 	$display_type_data = array();
	// 	if (is_array($display_types) && count($display_types) > 0) {
	// 		foreach ($display_types as $i => $type) {
	// 			$display_type_object = ditty_display_type_object($type['type']);
	// 			if ($display_type_object->has_js_fields()) {
	// 				continue;
	// 			}
	// 			$type['settings'] = $this->format_js_fields($display_type_object->fields());
	// 			$type['defaultValues'] = $display_type_object->default_settings();
	// 			$display_type_data[] = $type;
	// 		}
	// 	}
	// 	return array_values($display_type_data);
	// }

	// Convert fields for js
	private function convert_js_field_keys(&$field) {
		if (isset($field['multiple_fields'])) {
			$field['multipleFields'] = $field['multiple_fields'];
			unset($field['multiple_fields']);
		}
		if (isset($field['default_state'])) {
			$field['defaultState'] = $field['default_state'];
			unset($field['default_state']);
		}
		if (isset($field['clone_button'])) {
			$field['cloneButton'] = $field['clone_button'];
			unset($field['clone_button']);
		}
		if (isset($field['js_options'])) {
			if (is_array($field['js_options']) && count($field['js_options']) > 0) {
				foreach ($field['js_options'] as $key => $value) {
					$field[$key] = $value;
				}
			}
			unset($field['js_options']);
		}
	}
	private function format_js_field($field) {
		$this->convert_js_field_keys($field);
		if (isset($field['fields']) && is_array($field['fields']) && count($field['fields']) > 0) {
			$field['fields'] = array_values($field['fields']);

			foreach ($field['fields'] as $i => &$f) {
				$this->convert_js_field_keys($f);
				if (isset($f['type']) && 'group' == $f['type']) {
					$f = $this->format_js_field($f);
				}
			}
		}
		return $field;
	}
	private function format_js_fields($fields) {
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $i => &$field) {
				$field = $this->format_js_field($field);
			}
		}
		return array_values($fields);
	}
}
