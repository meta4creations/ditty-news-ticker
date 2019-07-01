<?php

/**
 * Add tick type tab
 * @access public
 * @since  3.0
 * @return array $tabs
 */
function dnt_admin_tick_tab_type( $tabs, $tick ) {
	$label = ( $tick->get_type() == 'none' ) ? __( 'Click here to select a ticker type', 'ditty-news-ticker' ) : $tick->get_label();
	$tabs['type'] = array(
		'icon' => $tick->get_icon(),
		'label' => $label
	);

	return $tabs;
}

/**
 * Add tick layout tab
 * @access public
 * @since  3.0
 * @return array $tabs
 */
function dnt_admin_tick_tab_layout( $tabs ) {
	$tabs['layout'] = array(
		'icon' => 'fas fa-pencil-paintbrush',
	);
	
	return $tabs;
}

/**
 * Add tick time tab
 * @access public
 * @since  3.0
 * @return array $tabs
 */
function dnt_admin_tick_tab_time( $tabs ) {
	$tabs['time'] = array(
		'icon' => 'far fa-clock',
	);
	
	return $tabs;
}

/**
 * Add tick type panel
 * @access public
 * @since  3.0
 * @return array $tabs
 */
function dnt_admin_tick_panel_type( $id ) {
	if( 'type' != $id ) {
		return;
	}
	
	$types = dnt_types();
	if ( is_array( $types ) && count( $types ) > 0 ) {
		foreach ( $types as $t_id => $t_data ) {
			?>
			<a class="dnt-btn dnt-type-option" data-type="<?php echo $t_id; ?>" href="#">
				<span class="dnt-btn__icon"><i class="<?php echo esc_attr( $t_data[ 'icon' ] ); ?>"></i></span>
				<span class="dnt-btn__label"><?php echo sanitize_text_field( $t_data[ 'label' ] ); ?></span>
			</a>
			<?php
		}
	}
/*
	?>
	<div class="dnt-panel__variations">
		<?php
		if ( is_array( $types ) && count( $types ) > 0 ) {
			foreach ( $types as $t_id => $t_id ) {										
				?>	
				<a class="dnt-panel__variation" data-variation="<?php echo $t_id; ?>">
					<span class="dnt-panel__variation__icon"><i class="<?php echo esc_attr( $t_data[ 'icon' ] ); ?>"></i></span>
					<span class="dnt-panel__variation__label"><?php echo sanitize_text_field( $t_data[ 'label' ] ); ?></span>
				</a>	
				<?php						
			}
		}
		?>
	</div>
	<div class="dnt-panel__body">
		<?php	
		if ( is_array( $types ) && count( $types ) > 0 ) {
			foreach ( $types as $t_id => $t_data ) {
				?>
				<div class="dnt-panel__input dnt-panel__input--variation" data-id="<?php echo esc_attr( $t_id ); ?>">									
					<?php
					$class_name = 'DNT_Type_' . ucfirst( $t_id );
					$tick_type = new $class_name();
					
					foreach ( $tick_type->fields() as $field ) {
						
						$field = RWMB_Field::call( 'normalize', $field );

						// Allow to add default values for fields.
						$field = apply_filters( 'rwmb_normalize_field', $field );
						$field = apply_filters( "rwmb_normalize_{$field['type']}_field", $field );
						$field = apply_filters( "rwmb_normalize_{$field['id']}_field", $field );
						
						$saved = false;
						$object_id = 'test';
						RWMB_Field::call( 'show', $field, $saved, $object_id );
					}
					?>
				</div>	
				<?php			
			}
		}
		?>		
	</div>
	<?php
*/
}

/**
 * Add tick layout panel
 * @access public
 * @since  3.0
 * @return array $tabs
 */
function dnt_admin_tick_panel_layout( $id ) {
	if( 'layout' != $id ) {
		return;
	}
	echo '<p>layout panel</p>';
}

/**
 * Add tick time panel
 * @access public
 * @since  3.0
 * @return array $tabs
 */
function dnt_admin_tick_panel_time( $id ) {
	if( 'time' != $id ) {
		return;
	}
	echo '<p>time panel</p>';
}