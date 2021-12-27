<?php
	
/**
 * Register settings pages
 *
 * @since    3.0
*/
function ditty_info_page() {
	add_submenu_page(
		'',																							// The ID of the top-level menu page to which this submenu item belongs
		__( 'Ditty Info', 'ditty-news-ticker' ),				// The value used to populate the browser's title bar when the menu page is active
		__( 'Upgrade Details', 'ditty-news-ticker' ),		// The label of this submenu item displayed in the menu
		'manage_ditty_settings',												// What roles are able to access this submenu item
		'ditty_info',																		// The ID used to represent this submenu item
		'ditty_info_display'														// The callback function used to render the options for this submenu item
	);
}
add_action( 'admin_menu', 'ditty_info_page', 9 );

function ditty_info_display() {
	?>
	<div id="ditty-page" class="wrap">
		
		<div id="ditty-page__header">
			<div id="ditty-page__version"><?php printf( __( 'Ditty v%s', 'ditty-news-ticker' ), ditty_version() ); ?></div>
			<h1 id="ditty-page__logo" style="text-align:center;"><img id="ditty-page__logo__image" src="<?php echo DITTY_URL; ?>includes/img/ditty-logo-black.png" alt="<?php _e( 'Ditty', 'ditty-news-ticker' ); ?>" style="margin-left:auto;margin-right:auto;" /></h1>
			<h3 style="text-align:center;"><?php _e( 'News Tickers were just the beginning!' ); ?></h3>
			
		</div>
		
		<div id="ditty-page__content">
			<section class="ditty-section ditty-section--intro">
				<p style="font-size:20px;margin-top:0;"><?php _e( '<strong>Ditty News Ticker</strong> has now become <strong>Ditty</strong>!' ); ?></p>
				<p style="font-size:16px;"><?php _e( "This update has been a long time in the making! Due to the complete rebuild of code for <strong>Ditty 3.0</strong> we decided to limit the upgrade process. We created a new post type you can start using right away without potentially causing issues with your existing <strong>News Tickers</strong>.", 'ditty-news-ticker' ); ?></p>
				<p style="font-size:16px;margin-bottom:0;"><?php _e( "Don't worry, all of your existing <strong>News Tickers</strong> will still work! Although, we do urge you to start upgrading and updating your tickers to the new <strong>Ditty</strong> post type. <strong>Ditty News Ticker</strong> is now relegated to legacy code and there will be very limited updates from this point on. Most development time will now be assigned to <strong>Ditty</strong> and existing and new <strong>Ditty</strong> extensions.", 'ditty-news-ticker' ); ?></p>
			</section>
			<section class="ditty-section">
				<div class="ditty-row">
					<div class="ditty-column">
						<div class="ditty-container">
							<h2 style="font-size:20px;margin-top: 0;margin-bottom:40px;"><?php printf( __( 'Ditty %s Features', 'ditty-news-ticker' ), ditty_version() ); ?>:</h2>
						</div>
					</div>
				</div>
				<div class="ditty-row">
					<div class="ditty-column ditty-column--1_2">
						<div class="ditty-container ditty-container--details">
							<i class="fas fa-redo-alt"></i>
							<div class="ditty-container--details__content">
								<h3><?php _e( 'Live Updates', 'ditty-news-ticker' ); ?></h3>
								<p><?php _e( 'Ditty will update in the background for users without the need to refresh the browser. Keep your content fresh and engaging.', 'ditty-news-ticker' ); ?></p>
							</div>
						</div>
					</div>
					<div class="ditty-column ditty-column--1_2">
						<div class="ditty-container ditty-container--details">
							<i class="fas fa-edit"></i>
							<div class="ditty-container--details__content">
								<h3><?php _e( 'Live Editing', 'ditty-news-ticker' ); ?></h3>
								<p><?php _e( 'See the changes you make while editing your Ditty as you make them. Add Items, edit Layouts and Displays and see what it looks like before saving.', 'ditty-news-ticker' ); ?></p>
							</div>
						</div>
					</div>
					<div class="ditty-column ditty-column--1_2">
						<div class="ditty-container ditty-container--details">
							<i class="fas fa-plus-square"></i>
							<div class="ditty-container--details__content">
								<h3><?php _e( 'Global Rendering', 'ditty-news-ticker' ); ?></h3>
								<p><?php _e( 'Easily add your Ditty globally on your site, anywhere, without modifying theme files. Want a ticker scrolling at the top of your site, no problem!', 'ditty-news-ticker' ); ?></p>
							</div>
						</div>
					</div>
					<div class="ditty-column ditty-column--1_2">
						<div class="ditty-container ditty-container--details">
							<i class="fas fa-random"></i>
							<div class="ditty-container--details__content">
								<h3><?php _e( 'Mix & Match Content', 'ditty-news-ticker' ); ?></h3>
								<p><?php _e( 'Combine multiple content feeds or custom content in a single Ditty. Merging custom default Items and feeds from various Ditty extensions together has never been easier.', 'ditty-news-ticker' ); ?></p>
							</div>
						</div>
					</div>
					<div class="ditty-column ditty-column--1_2">
						<div class="ditty-container ditty-container--details">
							<i class="fas fa-tablet-alt"></i>
							<div class="ditty-container--details__content">
								<h3><?php _e( 'Customized Displays', 'ditty-news-ticker' ); ?></h3>
								<p><?php _e( 'Render your Ditty as a custom ticker, list, slider, or other Display through extensions. Customize multiple settings to show your content the way you want.', 'ditty-news-ticker' ); ?></p>
							</div>
						</div>
					</div>
					<div class="ditty-column ditty-column--1_2">
						<div class="ditty-container ditty-container--details">
							<i class="fas fa-pencil-ruler"></i>
							<div class="ditty-container--details__content">
								<h3><?php _e( 'Customized Layouts', 'ditty-news-ticker' ); ?></h3>
								<p><?php _e( 'Take control of the the style of your content. Every Ditty Item type can be customized to reflect the style of your site. Use a pre-made template, or edit and customize to your needs!', 'ditty-news-ticker' ); ?></p>
							</div>
						</div>
					</div>
				</div>
			</section>
			
		</div>
	</div><!-- /.wrap -->
	<?php
}