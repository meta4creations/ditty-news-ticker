<?php

/**
 * Ditty News Ticker Tickers Class
 *
 * @package     Ditty News Ticker
 * @subpackage  Classes/Ditty News Ticker Tickers
 * @copyright   Copyright (c) 2019, Metaphor Creations
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/
class DNT_Tickers {

	/**
	 * Get things started
	 * @access  public
	 * @since   1.0
	 */
	public function __construct() {
		
		add_filter( 'mb_settings_pages', array( $this, 'settings_page' ) );
		add_filter( 'rwmb_meta_boxes', array( $this, 'settings_fields' ) );
		
		add_action( 'wp_ajax_dnt_admin_initialize_tick_type', array( $this, 'initialize_tick_type' ) );
	}
	
	
	/**
	 * Create the settings page
	 * @access  public
	 * @since   3.0
	 */
	public function settings_page( $settings_pages ) {
		
		$settings_pages[] = array(
		  'id'          => 'dnt-settings',
		  'option_name' => 'dnt_settings',
		  'menu_title'  => __('Settings', 'content-creator'),
		  'page_title'  => __('News Ticker Settings', 'content-creator'),
		  'parent'      => 'edit.php?post_type=ditty_news_ticker',
		  'style'				=> 'no-boxes',
		  'columns'			=> 1,
/*
		  'tabs'				=> array(
			  'queue' 	=> __('Page Queue', 'content-creator'),
			  'history' => __('History', 'content-creator'),
			  'import' 	=> __('Import', 'content-creator')
		  ),
*/
		);

		return $settings_pages;
	}
	
	
	/**
	 * Add the settings page fields
	 * @access  public
	 * @since   3.0
	 */
	public function settings_fields( $meta_boxes ) {
		
/*
		$meta_boxes['icc_page_creator_history'] = array(
	    'id'             	=> 'icc_page_creator_history',
	    'title'          	=> __('History', 'content-creator'),
	    'settings_pages' 	=> 'page-creator',
	    'tab' 						=> 'history',
	    'fields'         	=> array(
		    'history' => array(
		      'type' 	=> 'custom_html',
		      'std'		=> $this->display_history(),
		    ),
	    ),
		);
*/
	
		return $meta_boxes;
	}
	
	
	/**
	 * Initialize a tick type
	 * @access public
	 * @since  version
	 * @return void
	 */
	public function initialize_tick_type() {
		
		// Check the nonce
		check_ajax_referer( 'ditty-news-ticker', 'security' );
		
		$type = isset( $_POST['type'] ) ? $_POST['type'] : false;
		$class_name = 'DNT_Type_' . ucfirst( $type );
		$ticker_types = dnt_types();
		
		if ( $type && isset( $ticker_types[$type] ) ) {
			$tick = new DNT_Tick( $ticker_types[$type] );
			$tick->render_edit_row();
		}
		
		wp_die();
	}
	

	/**
	 * Return the tick edit type list
	 * @access  public
	 * @since   3.0
	 */
	public function edit_types() {
		
		$html = '';
		$types = dnt_types();			
		if( is_array($types) && count($types) > 0 ) {
			$html .= '<ul class="dnt-tick-list__edit__list">';
			foreach( $types as $slug=>$data ) {
				$html .= '<li class="dnt-tick-list__edit__item" data-type="'.$slug.'"><span class="dnt-tick-list__edit__item__icon"><i class="'.$data['icon'].'"></i></span> <span class="dnt-tick-list__edit__item__label">'.$data['label'].'</span></li>';
			}
			$html .= '</ul>';
		}
		
		return $html;
	}
	
	
	/**
	 * Return the tick edit type list
	 * @access  public
	 * @since   3.0
	 */
	public function edit_templates() {
		
		$html = '';
		$html .= '<ul class="dnt-tick-list__edit__list">';
		for( $i=0; $i<20; $i++ ) {
			$html .= '<li class="dnt-tick-list__edit__item" data-template_id="'.$i.'"><span class="dnt-tick-list__edit__item__label">Template #'.$i.'</span></li>';
		}
		$html .= '</ul>';
		
		return $html;
	}

	
}