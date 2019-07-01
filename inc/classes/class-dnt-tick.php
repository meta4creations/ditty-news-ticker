<?php

/**
 * Ditty News Ticker Tick Class
 *
 * @package     Ditty News Ticker
 * @subpackage  Classes/Ditty News Ticker Tick
 * @copyright   Copyright (c) 2019, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class DNT_Tick {
	
	/**
	 * Type
	 *
	 * @since 3.0
	 */
	private $type;
	
	/**
	 * Icon
	 *
	 * @since 3.0
	 */
	private $icon;
	
	/**
	 * Label
	 *
	 * @since 3.0
	 */
	private $label;

	/**
	 * Get things started
	 * @access  public
	 * @since   1.0
	 */
	public function __construct( $args=array() ) {
		
		$all_types = dnt_types();
		$this->type = $type = 'none';
		$this->icon = $icon = 'fas fa-exclamation';
		
		if ( isset( $all_types[$type] ) && isset( $all_types[$type]['icon'] ) ) {
			$this->icon = $all_types[$type]['icon'];
			$this->label = $all_types[$type]['label'];
		}	
	}
	
	/**
	 * Return the tick type
	 * @access public
	 * @since  3.0
	 * @return string $type
	 */
	public function get_type() {
		return $this->type;
	}
	
	/**
	 * Return the tick icon
	 * @access public
	 * @since  3.0
	 * @return string $icon
	 */
	public function get_icon() {
		return $this->icon;
	}
	
	/**
	 * Return the tick label
	 * @access public
	 * @since  3.0
	 * @return string $label
	 */
	public function get_label() {
		return $this->label;
	}
	
	/**
	 * Render a new admin edit row
	 * @access public
	 * @since  1.0
	 * @return html
	 */
	public function render_new_edit_row() {	
		?>	
		<li class="dnt-ticks__item dnt-ticks__item--<?php echo $this->type; ?>">
		
			<?php do_action( 'dnt_admin_tick_top', $this ); ?>
			
			<div class="dnt-tick-type-selection">
				
				<?php
				$types = dnt_types();
				if ( is_array( $types ) && count( $types ) > 0 ) {
					foreach ( $types as $t_id => $t_data ) {
						?>
						<a class="dnt-btn dnt-tick-type-option dnt-tick-type-option--<?php echo $t_id; ?>" data-type="<?php echo $t_id; ?>" href="#">
							<span class="dnt-btn__icon"><i class="<?php echo esc_attr( $t_data[ 'icon' ] ); ?>"></i></span>
							<span class="dnt-btn__label"><?php echo sanitize_text_field( $t_data[ 'label' ] ); ?></span>
						</a>
						<?php
					}
				}
				?>
				
			</div>
			
			<?php do_action( 'dnt_admin_tick_bottom', $this ); ?>
			
		</li>
		<?php
	}
	
	/**
	 * Render the admin edit row
	 * @access public
	 * @since  1.0
	 * @return html
	 */
	public function render_edit_row() {	
		
		$tabs = apply_filters( 'dnt_admin_tick_tabs', array(), $this );
		?>
		
		<li class="dnt-ticks__item dnt-ticks__item--<?php echo $this->type; ?> dnt-tabs" data-tab-type="toggle">
		
			<?php do_action( 'dnt_admin_tick_top', $this ); ?>

			<div class="dnt-tab__links">
				
				<?php
				// Render the tabs
				if ( is_array( $tabs ) && count( $tabs ) > 0 ) {
					foreach ( $tabs as $id => $data ) {
						echo '<a class="dnt-tab dnt-tick-tab--' . $id . '" href="#" data-panel="' . $id . '">';
							echo '<span class="dnt-tab__icon"><i class="' . esc_attr( $data['icon'] ) . '"></i></span>';
							if ( isset( $data['label'] ) ) {
								echo '<span class="dnt-tab__label">' . sanitize_text_field( $data['label'] ) . '</span>';
							}	
						echo '</a>';
					}
				}	
				?>
				
			</div>
			<div class="dnt-tab__panels">
				
				<?php
				// Render the panels
				if ( is_array( $tabs ) && count( $tabs ) > 0 ) {
					foreach ( $tabs as $id => $data ) {
						echo '<div class="dnt-tab__panel dnt-tab__panel--' . $id . '" data-id="' . $id . '">';
							echo '<div class="dnt-tab__panel__contents">';
								do_action( 'dnt_admin_tick_tab_panel', $id, $data, $this );
							echo '</div>';
						echo '</div>';
					}
				}	
				?>

			</div>
			
			<?php do_action( 'dnt_admin_tick_bottom', $this ); ?>
			
		</li>
		<?php
	}
	
	
	
	
}