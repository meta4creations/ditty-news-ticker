<?php
/**
 * Ditty Editor Preview Class
 *
 * Handles iframe preview rendering for the Ditty editor with v4 scripts.
 * Provides both endpoint and data storage for preview rendering.
 *
 * @package     Ditty
 * @subpackage  Classes/Ditty Editor Preview
 * @copyright   Copyright (c) 2024, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Ditty_Editor_Preview {

	/**
	 * Transient prefix for storing preview data
	 *
	 * @var string
	 */
	private $transient_prefix = 'ditty_preview_';

	/**
	 * Transient expiration time (in seconds)
	 *
	 * @var int
	 */
	private $transient_expiration = 3600; // 1 hour

	/**
	 * Get things started
	 * 
	 * @access  public
	 * @since   4.0
	 */
	public function __construct() {
		add_action( 'admin_action_ditty_preview', array( $this, 'render_preview_page' ) );
		add_action( 'admin_action_ditty_layout_preview', array( $this, 'render_layout_preview_page' ) );
		add_action( 'wp_ajax_ditty_store_preview_data', array( $this, 'ajax_store_preview_data' ) );
	}

	/**
	 * Generate a unique preview nonce
	 *
	 * @access public
	 * @since  4.0
	 * @return string Preview nonce
	 */
	public function generate_preview_nonce() {
		return wp_create_nonce( 'ditty_preview_' . get_current_user_id() . '_' . time() );
	}

	/**
	 * Verify preview nonce and user capabilities
	 *
	 * @access private
	 * @since  4.0
	 * @param  string $nonce Preview nonce
	 * @return bool True if valid, false otherwise
	 */
	private function verify_preview_access( $nonce ) {
		// Check if user is logged in and has edit capabilities
		if ( ! is_user_logged_in() ) {
			return false;
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			return false;
		}

		// Verify nonce
		$user_id = get_current_user_id();
		if ( ! wp_verify_nonce( $nonce, 'ditty_preview_' . $user_id . '_' . time() ) ) {
			// Try with a slightly older timestamp (within 1 hour window)
			$valid = false;
			for ( $i = 0; $i < 3600; $i += 60 ) {
				if ( wp_verify_nonce( $nonce, 'ditty_preview_' . $user_id . '_' . ( time() - $i ) ) ) {
					$valid = true;
					break;
				}
			}
			if ( ! $valid ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Store preview data in transient via AJAX
	 *
	 * @access public
	 * @since  4.0
	 */
	public function ajax_store_preview_data() {
		// Verify nonce for preview storage
		check_ajax_referer( 'ditty_preview', 'preview_nonce' );

		// Check capabilities
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
		}

		// Get preview data from request
		$preview_key = isset( $_POST['preview_key'] ) ? sanitize_key( wp_unslash( $_POST['preview_key'] ) ) : '';
		$preview_data = isset( $_POST['preview_data'] ) ? wp_unslash( $_POST['preview_data'] ) : array();

		if ( empty( $preview_key ) ) {
			wp_send_json_error( array( 'message' => 'Missing preview key' ) );
		}

		// Decode preview data if JSON string
		if ( is_string( $preview_data ) ) {
			$decoded = json_decode( $preview_data, true );
			if ( json_last_error() === JSON_ERROR_NONE && is_array( $decoded ) ) {
				$preview_data = $decoded;
			}
		}

		// Store in transient
		$stored = set_transient( 
			$this->transient_prefix . $preview_key, 
			$preview_data, 
			$this->transient_expiration 
		);

		if ( $stored ) {
			wp_send_json_success( array( 'preview_key' => $preview_key ) );
		} else {
			wp_send_json_error( array( 'message' => 'Failed to store preview data' ) );
		}
	}

	/**
	 * Get preview data from transient
	 *
	 * @access private
	 * @since  4.0
	 * @param  string $preview_key Preview key
	 * @return array|false Preview data or false if not found
	 */
	private function get_preview_data( $preview_key ) {
		return get_transient( $this->transient_prefix . $preview_key );
	}

	/**
	 * Get the preview URL
	 *
	 * @access public
	 * @since  4.0
	 * @param  string $preview_key Preview key for transient data
	 * @return string Preview URL
	 */
	public function get_preview_url( $preview_key = '' ) {
		$params = array(
			'action' => 'ditty_preview',
		);

		if ( ! empty( $preview_key ) ) {
			$params['preview_key'] = $preview_key;
		}

		return add_query_arg( $params, admin_url( 'admin.php' ) );
	}

	/**
	 * Render the preview page
	 *
	 * Outputs a complete HTML page with theme styles and v4 display.
	 *
	 * @access public
	 * @since  4.0
	 */
	public function render_preview_page() {
		// Get preview key from request
		$preview_key = isset( $_GET['preview_key'] ) ? sanitize_key( $_GET['preview_key'] ) : '';

		if ( empty( $preview_key ) ) {
			wp_die( __( 'Invalid preview key', 'ditty-news-ticker' ) );
		}

		// Verify access
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( __( 'You do not have permission to view this preview', 'ditty-news-ticker' ) );
		}

		// Get preview data from transient
		$preview_data = $this->get_preview_data( $preview_key );

		if ( false === $preview_data ) {
			wp_die( __( 'Preview data not found or expired', 'ditty-news-ticker' ) );
		}
		
		// Decode preview data if stored as JSON string
		if ( is_string( $preview_data ) ) {
			$decoded = json_decode( $preview_data, true );
			if ( json_last_error() === JSON_ERROR_NONE && is_array( $decoded ) ) {
				$preview_data = $decoded;
			}
		}

		// Extract preview data
		$ditty_id = isset( $preview_data['ditty_id'] ) ? $preview_data['ditty_id'] : 'preview';
		$display_settings = isset( $preview_data['display'] ) ? $preview_data['display'] : array();
		$items = isset( $preview_data['items'] ) ? $preview_data['items'] : array();
		$custom_styles = isset( $preview_data['styles'] ) ? $preview_data['styles'] : array();

		// Prepare display args for v4 renderer
		$display_args = wp_parse_args( $display_settings['settings'], Ditty_V4_Renderer::get_defaults() );
		
		// Add title if present
		if ( isset( $preview_data['title'] ) ) {
			$display_args['title'] = $preview_data['title'];
		}

		// Render items HTML
		$rendered_items = array();
		if ( is_array( $items ) && count( $items ) > 0 ) {
			foreach ( $items as $item ) {
				if ( isset( $item['html'] ) ) {
					$rendered_items[] = $item['html'];
				}
			}
		}

		// Start output buffering
		ob_start();
		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
		<head>
			<meta charset="<?php bloginfo( 'charset' ); ?>">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta name="robots" content="noindex, nofollow">
			<title><?php echo esc_html( sprintf( __( 'Ditty Preview: %s', 'ditty-news-ticker' ), $ditty_id ) ); ?></title>
			<?php
			// Avoid deprecated emoji/admin bar head output in preview
			remove_action( 'wp_head', 'print_emoji_styles' );
			remove_action( 'wp_head', 'wp_admin_bar_header' );

			// Enqueue v4 assets
			Ditty_V4_Renderer::enqueue_assets();
			
			// Output theme styles
			wp_head(); 
			?>
			<style>
				/* Reset HTML/body styles for clean preview */
				html {
					margin: 0 !important;
				}
				body {
					margin: 0;
					padding: 0;
					background: transparent;
					overflow: auto;
				}

				.ditty-preview-body {
					padding: 0;
				}
				
				/* Apply custom preview styles */
				<?php if ( ! empty( $custom_styles ) ) : ?>
					.ditty-display {
						<?php
						foreach ( $custom_styles as $property => $value ) {
							if ( empty( $value ) ) {
								continue;
							}

							// Handle spacing arrays (e.g., top/right/bottom/left)
							if ( is_array( $value ) ) {
								$parts = [];
								$order = [ 'top', 'right', 'bottom', 'left' ];
								foreach ( $order as $side ) {
									if ( isset( $value[ $side ] ) && '' !== $value[ $side ] ) {
										$parts[] = $value[ $side ];
									}
								}
								if ( empty( $parts ) ) {
									$parts = array_filter( array_map( 'strval', $value ) );
								}
								$value = implode( ' ', $parts );
							}

							if ( '' !== $value && ! is_array( $value ) ) {
								echo esc_attr( $property ) . ': ' . esc_attr( $value ) . ';';
							}
						}
						?>
					}
				<?php endif; ?>

				/* Hide WordPress admin bar if present */
				#wpadminbar {
					display: none !important;
				}
			</style>
		</head>
		<body class="ditty-preview-body">
			<?php 
			// Render the Ditty display using v4 renderer
			if ( ! empty( $rendered_items ) ) {
				echo Ditty_V4_Renderer::render( $rendered_items, $display_args );
			} else {
				echo '<div class="ditty-preview-empty">';
				echo '<p>' . esc_html__( 'No items to display', 'ditty-news-ticker' ) . '</p>';
				echo '</div>';
			}
			?>
			<?php wp_footer(); ?>
			<script>
				// Notify parent frame that preview has loaded
				if ( window.parent !== window ) {
					window.parent.postMessage( { type: 'ditty_preview_loaded' }, '*' );
				}
			</script>
		</body>
		</html>
		<?php
		$output = ob_get_clean();
		
		// Output the page
		echo $output;
		exit;
	}

	/**
	 * Render the layout preview page
	 *
	 * Outputs a complete HTML page with theme styles for a single layout item.
	 * Listens for postMessage events to handle CSS-only updates without reload.
	 *
	 * @access public
	 * @since  4.0
	 */
	public function render_layout_preview_page() {
		$preview_key = isset( $_GET['preview_key'] ) ? sanitize_key( $_GET['preview_key'] ) : '';

		if ( empty( $preview_key ) ) {
			wp_die( __( 'Invalid preview key', 'ditty-news-ticker' ) );
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( __( 'You do not have permission to view this preview', 'ditty-news-ticker' ) );
		}

		$preview_data = $this->get_preview_data( $preview_key );

		if ( false === $preview_data ) {
			wp_die( __( 'Preview data not found or expired', 'ditty-news-ticker' ) );
		}

		if ( is_string( $preview_data ) ) {
			$decoded = json_decode( $preview_data, true );
			if ( json_last_error() === JSON_ERROR_NONE && is_array( $decoded ) ) {
				$preview_data = $decoded;
			}
		}

		$item_html     = isset( $preview_data['html'] ) ? $preview_data['html'] : '';
		$item_css      = isset( $preview_data['css'] ) ? $preview_data['css'] : '';
		$custom_styles = isset( $preview_data['styles'] ) ? $preview_data['styles'] : array();

		ob_start();
		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
		<head>
			<meta charset="<?php bloginfo( 'charset' ); ?>">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta name="robots" content="noindex, nofollow">
			<title><?php esc_html_e( 'Layout Preview', 'ditty-news-ticker' ); ?></title>
			<?php
			remove_action( 'wp_head', 'print_emoji_styles' );
			remove_action( 'wp_head', 'wp_admin_bar_header' );
			wp_head();
			?>
			<style id="ditty-layout-css"><?php echo $item_css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
			<style>
				html {
					margin: 0 !important;
				}
				body {
					margin: 0;
					padding: 0;
					background: transparent;
					overflow: auto;
				}
				#wpadminbar {
					display: none !important;
				}
				.ditty-layout-preview {
					box-sizing: border-box;
				}
				<?php if ( ! empty( $custom_styles ) ) : ?>
					.ditty-layout-preview {
						<?php
						foreach ( $custom_styles as $property => $value ) {
							if ( empty( $value ) ) {
								continue;
							}
							if ( is_array( $value ) ) {
								$parts = [];
								$order = [ 'top', 'right', 'bottom', 'left' ];
								foreach ( $order as $side ) {
									if ( isset( $value[ $side ] ) && '' !== $value[ $side ] ) {
										$parts[] = $value[ $side ];
									}
								}
								if ( empty( $parts ) ) {
									$parts = array_filter( array_map( 'strval', $value ) );
								}
								$value = implode( ' ', $parts );
							}
							if ( '' !== $value && ! is_array( $value ) ) {
								$css_property = preg_replace_callback( '/[A-Z]/', function( $matches ) {
									return '-' . strtolower( $matches[0] );
								}, $property );
								echo esc_attr( $css_property ) . ': ' . esc_attr( $value ) . ';';
							}
						}
						?>
					}
				<?php endif; ?>
			</style>
		</head>
		<body class="ditty-preview-body">
			<div class="ditty ditty-layout-preview">
				<?php echo $item_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<?php wp_footer(); ?>
			<script>
				if ( window.parent !== window ) {
					window.parent.postMessage( { type: 'ditty_preview_loaded' }, '*' );
				}
				window.addEventListener( 'message', function( event ) {
					if ( event.data && event.data.type === 'ditty_update_css' ) {
						var styleEl = document.getElementById( 'ditty-layout-css' );
						if ( styleEl ) {
							styleEl.textContent = event.data.css;
						}
					}
				});
			</script>
		</body>
		</html>
		<?php
		$output = ob_get_clean();
		echo $output;
		exit;
	}
}
