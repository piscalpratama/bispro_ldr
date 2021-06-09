<?php
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'generate_menu_plus_setup' ) ) :
add_action( 'after_setup_theme','generate_menu_plus_setup' );
function generate_menu_plus_setup()
{
	register_nav_menus( array(
		'slideout' => __( 'Slideout Menu','menu-plus' ),
	) );
	
}
endif;

if ( ! function_exists( 'generate_menu_plus_get_defaults' ) ) :
/**
 * Set default options
 */
function generate_menu_plus_get_defaults()
{
	$generate_menu_plus_get_defaults = array(
		'mobile_menu_label' => __( 'Menu','menu-plus' ),
		'sticky_menu' => 'false',
		'sticky_menu_effect' => 'fade',
		'sticky_menu_logo' => '',
		'sticky_menu_logo_position' => 'sticky-menu',
		'mobile_header' => 'disable',
		'mobile_header_logo' => '',
		'mobile_header_sticky' => 'disable',
		'slideout_menu' => 'false',
	);
	
	return apply_filters( 'generate_menu_plus_option_defaults', $generate_menu_plus_get_defaults );
}
endif;

if ( ! function_exists( 'generate_menu_plus_customize_register' ) ) :
/**
 * Initiate Customizer controls
 */
add_action( 'customize_register', 'generate_menu_plus_customize_register', 100 );
function generate_menu_plus_customize_register( $wp_customize ) {

	$defaults = generate_menu_plus_get_defaults();
	
	if ( class_exists( 'WP_Customize_Panel' ) ) :
		if ( ! $wp_customize->get_panel( 'generate_menu_plus' ) ) {
			$wp_customize->add_panel( 'generate_menu_plus', array(
				'priority'       => 50,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '',
				'title'          => __( 'Menu Plus','menu-plus' ),
				'description'    => '',
			) );
		}
	endif;
	
	if ( $wp_customize->get_panel( 'generate_layout_panel' ) ) {
		$panel = 'generate_layout_panel';
		$navigation_section = 'generate_layout_navigation';
		$header_section = 'generate_layout_header';
		$sticky_menu_section = 'generate_layout_navigation';
	} else {
		$panel = 'generate_menu_plus';
		$navigation_section = 'menu_plus_section';
		$header_section = 'menu_plus_mobile_header';
		$sticky_menu_section = 'menu_plus_sticky_menu';
	}
	
	// Add Menu Extended section
	$wp_customize->add_section(
		// ID
		'menu_plus_section',
		// Arguments array
		array(
			'title' => __( 'General Settings','menu-plus' ),
			'capability' => 'edit_theme_options',
			'panel' => 'generate_menu_plus'
		)
	);
	
	// Add mobile menu label setting
	$wp_customize->add_setting(
		'generate_menu_plus_settings[mobile_menu_label]', 
		array(
			'default' => $defaults['mobile_menu_label'],
			'type' => 'option',
			'sanitize_callback' => 'wp_kses_post'
		)
	);
		 
	$wp_customize->add_control(
		'mobile_menu_label_control', array(
			'label' => __('Mobile Menu Label','menu-plus'),
			'section' => $navigation_section,
			'settings' => 'generate_menu_plus_settings[mobile_menu_label]'
		)
	);
	
	// Add Menu Extended section
	$wp_customize->add_section(
		// ID
		'menu_plus_sticky_menu',
		// Arguments array
		array(
			'title' => __( 'Sticky Menu','menu-plus' ),
			'capability' => 'edit_theme_options',
			'panel' => $panel,
			'priority' => 17
		)
	);
	
	// Add sticky nav setting
	$wp_customize->add_setting(
		// ID
		'generate_menu_plus_settings[sticky_menu]',
		// Arguments array
		array(
			'default' => $defaults['sticky_menu'],
			'type' => 'option',
			'sanitize_callback' => 'generate_menu_plus_sanitize_choices'
		)
	);
	
	// Add sticky nav control
	$wp_customize->add_control(
		// ID
		'generate_menu_plus_settings[sticky_menu]',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Sticky Navigation','menu-plus' ),
			'section' => $sticky_menu_section,
			'choices' => array(
				'mobile' => __( 'Mobile only','menu-plus' ),
				'desktop' => __( 'Desktop only','menu-plus' ),
				'true' => __( 'Both','menu-plus' ),
				'false' => __( 'Disable','menu-plus' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_menu_plus_settings[sticky_menu]',
			'priority' => 105
		)
	);
	
	// Add sticky nav setting
	$wp_customize->add_setting(
		// ID
		'generate_menu_plus_settings[sticky_menu_effect]',
		// Arguments array
		array(
			'default' => $defaults['sticky_menu_effect'],
			'type' => 'option',
			'sanitize_callback' => 'generate_menu_plus_sanitize_choices'
		)
	);
	
	// Add sticky nav control
	$wp_customize->add_control(
		// ID
		'generate_menu_plus_settings[sticky_menu_effect]',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Sticky Navigation Transition','menu-plus' ),
			'section' => $sticky_menu_section,
			'choices' => array(
				'fade' => __( 'Fade','menu-plus' ),
				'slide' => __( 'Slide','menu-plus' ),
				'none' => __( 'None','menu-plus' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_menu_plus_settings[sticky_menu_effect]',
			'active_callback' => 'generate_sticky_navigation_activated',
			'priority' => 110
		)
	);
	
	$wp_customize->add_setting( 
		'generate_menu_plus_settings[sticky_menu_logo]', 
		array(
			'default' => $defaults['sticky_menu_logo'],
			'type' => 'option',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
 
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'generate_menu_plus_settings[sticky_menu_logo]',
			array(
				'label' => __('Navigation Logo','menu-plus'),
				'section' => $sticky_menu_section,
				'settings' => 'generate_menu_plus_settings[sticky_menu_logo]',
				'priority' => 115
			)
		)
	);
	
	// Add sticky nav setting
	$wp_customize->add_setting(
		// ID
		'generate_menu_plus_settings[sticky_menu_logo_position]',
		// Arguments array
		array(
			'default' => $defaults['sticky_menu_logo_position'],
			'type' => 'option',
			'sanitize_callback' => 'generate_menu_plus_sanitize_choices'
		)
	);
	
	// Add sticky nav control
	$wp_customize->add_control(
		// ID
		'generate_menu_plus_settings[sticky_menu_logo_position]',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Navigation Logo Position','menu-plus' ),
			'section' => $sticky_menu_section,
			'choices' => array(
				'sticky-menu' => __( 'Sticky','menu-plus' ),
				'menu' => __( 'Sticky + Static','menu-plus' ),
				'regular-menu' => __( 'Static','menu-plus' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_menu_plus_settings[sticky_menu_logo_position]',
			'priority' => 120
		)
	);
	
	// Add Menu Extended section
	$wp_customize->add_section(
		// ID
		'menu_plus_mobile_header',
		// Arguments array
		array(
			'title' => __( 'Mobile Header','menu-plus' ),
			'capability' => 'edit_theme_options',
			'panel' => $panel,
			'priority' => 11
		)
	);
	
	// Add sticky nav setting
	$wp_customize->add_setting(
		// ID
		'generate_menu_plus_settings[mobile_header]',
		// Arguments array
		array(
			'default' => $defaults['mobile_header'],
			'type' => 'option',
			'sanitize_callback' => 'generate_menu_plus_sanitize_choices'
		)
	);
	
	// Add sticky nav control
	$wp_customize->add_control(
		// ID
		'generate_menu_plus_settings[mobile_header]',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Mobile Header','menu-plus' ),
			'section' => $header_section,
			'choices' => array(
				'disable' => __( 'Disable','menu-plus' ),
				'enable' => __( 'Enable','menu-plus' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_menu_plus_settings[mobile_header]',
		)
	);
	
	$wp_customize->add_setting( 
		'generate_menu_plus_settings[mobile_header_logo]', 
		array(
			'default' => $defaults['mobile_header_logo'],
			'type' => 'option',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
 
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'generate_menu_plus_settings[mobile_header_logo]',
			array(
				'label' => __('Mobile Header Logo','menu-plus'),
				'section' => $header_section,
				'settings' => 'generate_menu_plus_settings[mobile_header_logo]',
				'active_callback' => 'generate_mobile_header_activated'
			)
		)
	);
	
	// Add sticky nav setting
	$wp_customize->add_setting(
		// ID
		'generate_menu_plus_settings[mobile_header_sticky]',
		// Arguments array
		array(
			'default' => $defaults['mobile_header_sticky'],
			'type' => 'option',
			'sanitize_callback' => 'generate_menu_plus_sanitize_choices'
		)
	);
	
	// Add sticky nav control
	$wp_customize->add_control(
		// ID
		'generate_menu_plus_settings[mobile_header_sticky]',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Sticky Mobile Header','menu-plus' ),
			'section' => $header_section,
			'choices' => array(
				'enable' => __( 'Enable','menu-plus' ),
				'disable' => __( 'Disable','menu-plus' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_menu_plus_settings[mobile_header_sticky]',
			'active_callback' => 'generate_mobile_header_activated'
		)
	);
	
	// Add Menu Extended section
	$wp_customize->add_section(
		// ID
		'menu_plus_slideout_menu',
		// Arguments array
		array(
			'title' => __( 'Slideout Menu','menu-plus' ),
			'capability' => 'edit_theme_options',
			'panel' => $panel,
			'priority' => 19
		)
	);
	
	// Add slideout nav setting
	$wp_customize->add_setting(
		// ID
		'generate_menu_plus_settings[slideout_menu]',
		// Arguments array
		array(
			'default' => $defaults['slideout_menu'],
			'type' => 'option',
			'sanitize_callback' => 'generate_menu_plus_sanitize_choices'
		)
	);
	
	// Add sticky nav control
	$wp_customize->add_control(
		// ID
		'generate_menu_plus_settings[slideout_menu]',
		// Arguments array
		array(
			'type' => 'select',
			'label' => __( 'Slideout Navigation','menu-plus' ),
			'section' => $navigation_section,
			'choices' => array(
				'mobile' => __( 'Mobile only','menu-plus' ),
				'desktop' => __( 'Desktop only','menu-plus' ),
				'both' => __( 'Both','menu-plus' ),
				'false' => __( 'Disable','menu-plus' )
			),
			// This last one must match setting ID from above
			'settings' => 'generate_menu_plus_settings[slideout_menu]',
			'priority' => 150
		)
	);
}
endif;

if ( ! function_exists( 'generate_menu_plus_customizer_live_preview' ) ) :
add_action( 'customize_preview_init', 'generate_menu_plus_customizer_live_preview' );
function generate_menu_plus_customizer_live_preview()
{
	wp_enqueue_script( 
		  'generate-menu-plus-themecustomizer',
		  plugin_dir_url( __FILE__ ) . '/js/customizer.js',
		  array( 'jquery','customize-preview' ),
		  GENERATE_MENU_PLUS_VERSION,
		  true
	);
}
endif;

if ( ! function_exists( 'generate_menu_plus_enqueue_css' ) ) :
/**
 * Enqueue scripts
 */
add_action( 'wp_enqueue_scripts','generate_menu_plus_enqueue_css', 100 );
function generate_menu_plus_enqueue_css()
{
	$generate_menu_plus_settings = wp_parse_args( 
		get_option( 'generate_menu_plus_settings', array() ), 
		generate_menu_plus_get_defaults() 
	);
	
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	
	// Add sticky menu script
	if ( 'false' !== $generate_menu_plus_settings['sticky_menu'] && 'none' !== $generate_menu_plus_settings['sticky_menu_effect'] ) :
		wp_enqueue_style( 'generate-advanced-sticky', plugin_dir_url( __FILE__ ) . "css/advanced-sticky{$suffix}.css", array(), GENERATE_MENU_PLUS_VERSION );
	endif;
	
	// Add sticky menu script
	if ( 'false' !== $generate_menu_plus_settings['sticky_menu'] && 'none' == $generate_menu_plus_settings['sticky_menu_effect'] ) :
		wp_enqueue_style( 'generate-sticky', plugin_dir_url( __FILE__ ) . "css/sticky{$suffix}.css", array(), GENERATE_MENU_PLUS_VERSION );
	endif;
	
	// Add slideout menu script
	if ( 'false' !== $generate_menu_plus_settings['slideout_menu'] ) :
		wp_enqueue_style( 'generate-sliiide', plugin_dir_url( __FILE__ ) . "css/sliiide{$suffix}.css", array(), GENERATE_MENU_PLUS_VERSION );
	endif;
	
	if ( 'mobile' == $generate_menu_plus_settings['slideout_menu'] || 'both' == $generate_menu_plus_settings['slideout_menu'] ) :
		wp_dequeue_script( 'generate-navigation' );
	endif;
	
	// Add regular menu logo styling
	if ( '' !== $generate_menu_plus_settings['sticky_menu_logo'] ) :
		wp_enqueue_style( 'generate-menu-logo', plugin_dir_url( __FILE__ ) . "css/menu-logo{$suffix}.css", array(), GENERATE_MENU_PLUS_VERSION );
	endif;
	
	// Add mobile header CSS
	if ( 'enable' == $generate_menu_plus_settings['mobile_header'] ) :
		wp_enqueue_style( 'generate-mobile-header', plugin_dir_url( __FILE__ ) . "css/mobile-header{$suffix}.css", array(), GENERATE_MENU_PLUS_VERSION );
	endif;
	
	// Add inline CSS
	wp_add_inline_style( 'generate-style', generate_menu_plus_inline_css() );
	
}
endif;

if ( ! function_exists( 'generate_menu_plus_enqueue_js' ) ) :
/**
 * Enqueue scripts
 */
add_action( 'wp_enqueue_scripts','generate_menu_plus_enqueue_js', 0 );
function generate_menu_plus_enqueue_js()
{
	$generate_menu_plus_settings = wp_parse_args( 
		get_option( 'generate_menu_plus_settings', array() ), 
		generate_menu_plus_get_defaults() 
	);
	
	if ( function_exists( 'generate_get_defaults' ) ) :
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	endif;
	
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	
	// Add sticky menu script
	if ( 'false' !== $generate_menu_plus_settings['sticky_menu'] && 'none' !== $generate_menu_plus_settings['sticky_menu_effect'] ) :
		if ( 'enable' == $generate_settings['nav_search'] ) {
			$array = array( 'jquery', 'generate-navigation-search' );
		} else {
			$array = array( 'jquery' );
		}
		wp_enqueue_script( 'generate-advanced-sticky', plugin_dir_url( __FILE__ ) . "js/advanced-sticky{$suffix}.js", $array, GENERATE_MENU_PLUS_VERSION, true );
	endif;
	
	// Add sticky menu script
	if ( ( 'false' !== $generate_menu_plus_settings['sticky_menu'] && 'none' == $generate_menu_plus_settings['sticky_menu_effect'] ) || 'enable' == $generate_menu_plus_settings['mobile_header_sticky'] ) :
		wp_enqueue_script( 'generate-sticky', plugin_dir_url( __FILE__ ) . "js/sticky{$suffix}.js", array( 'jquery' ), GENERATE_MENU_PLUS_VERSION, true );
		if ( function_exists( 'wp_script_add_data' ) ) :
			wp_enqueue_script( 'generate-sticky-matchMedia', plugin_dir_url( __FILE__ ) . "js/matchMedia{$suffix}.js", array(), GENERATE_MENU_PLUS_VERSION, true );
			wp_script_add_data( 'generate-sticky-matchMedia', 'conditional', 'lt IE 10' );
		endif;
	endif;
	
	// Add slideout menu script
	if ( 'false' !== $generate_menu_plus_settings['slideout_menu'] ) :
		wp_enqueue_script( 'generate-sliiide', plugin_dir_url( __FILE__ ) . "js/sliiide{$suffix}.js", array( 'jquery' ), GENERATE_MENU_PLUS_VERSION, true );
	endif;
	
	if ( 'mobile' == $generate_menu_plus_settings['slideout_menu'] || 'both' == $generate_menu_plus_settings['slideout_menu'] ) :
		wp_enqueue_script( 'generate-sliiide-navigation', plugin_dir_url( __FILE__ ) . "js/navigation{$suffix}.js", array( 'jquery' ), GENERATE_MENU_PLUS_VERSION, true );
	endif;
	
}
endif;

if ( ! function_exists( 'generate_menu_plus_mobile_header_js' ) ) :
/**
 * Enqueue scripts
 */
add_action( 'wp_enqueue_scripts','generate_menu_plus_mobile_header_js', 15 );
function generate_menu_plus_mobile_header_js()
{
	if ( function_exists( 'wp_add_inline_script' ) ) :
	
		$generate_menu_plus_settings = wp_parse_args( 
			get_option( 'generate_menu_plus_settings', array() ), 
			generate_menu_plus_get_defaults() 
		);
	
		if ( 'enable' == $generate_menu_plus_settings[ 'mobile_header' ] && ( 'desktop' == $generate_menu_plus_settings[ 'slideout_menu' ] || 'false' == $generate_menu_plus_settings[ 'slideout_menu' ] ) ) :
			wp_add_inline_script( 'generate-navigation', 'jQuery( document ).ready( function( $ ) {$( "#mobile-header .menu-toggle" ).GenerateMobileMenu();});' );
		endif;
	endif;
}
endif;

if ( ! function_exists( 'generate_menu_plus_inline_css' ) ) :
/**
 * Enqueue inline CSS
 */
function generate_menu_plus_inline_css()
{
	// Bail if GP isn't active
	if ( ! function_exists( 'generate_get_defaults' ) )
		return;
	
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults()
	);
	
	$generate_menu_plus_settings = wp_parse_args( 
		get_option( 'generate_menu_plus_settings', array() ), 
		generate_menu_plus_get_defaults() 
	);
	
	if ( function_exists( 'generate_spacing_get_defaults' ) ) {
		$spacing_settings = wp_parse_args( 
			get_option( 'generate_spacing_settings', array() ), 
			generate_spacing_get_defaults() 
		);
		$menu_height = $spacing_settings['menu_item_height'];
	} else {
		$menu_height = 60;
	}
	
	$return = '';
	if ( 'contained-nav' == $generate_settings['nav_layout_setting'] && 'false' !== $generate_menu_plus_settings['sticky_menu'] ) {
		$return .= '@media (min-width: ' . $generate_settings['container_width'] . 'px) { .navigation-stick:not(.gen-sidebar-nav) { left: 50%; width: 100% !important; max-width: ' . $generate_settings['container_width'] . 'px !important; margin-left: -' . $generate_settings['container_width'] / 2 . 'px; } }';
		$return .= '@media (min-width: 768px) and (max-width: ' . ($generate_settings['container_width'] - 1) . 'px) {.navigation-stick:not(.gen-sidebar-nav) { width: 100%; } }';
	}
	
	if ( '' !== $generate_menu_plus_settings['sticky_menu_logo'] ) {
		$logo_height = $menu_height - 20;
		$return .= '.main-navigation .navigation-logo img {height:' . $logo_height . 'px;}';
		$return .= '@media (max-width: ' . ( $generate_settings['container_width'] + 10 ) . 'px) {.main-navigation .navigation-logo {margin-left: 10px;}body.sticky-menu-logo.nav-float-left .main-navigation .site-logo.navigation-logo {margin-right:10px;}}';
	}
	
	if ( '' !== $generate_menu_plus_settings['mobile_header_logo'] ) {
		$logo_height = $menu_height - 20;
		$return .= '.mobile-header-navigation .mobile-header-logo img {height:' . $logo_height . 'px;}';
	}
	
	return $return;
}
endif;

if ( ! function_exists( 'generate_menu_plus_mobile_menu_script' ) ) :
add_action( 'wp_footer','generate_menu_plus_mobile_menu_script' );
function generate_menu_plus_mobile_menu_script()
{
	// No need for this if wp_add_inline_script() exists
	if ( function_exists( 'wp_add_inline_script' ) )
		return;
	
	$generate_menu_plus_settings = wp_parse_args( 
		get_option( 'generate_menu_plus_settings', array() ), 
		generate_menu_plus_get_defaults() 
	);
	
	if ( 'desktop' == $generate_menu_plus_settings[ 'slideout_menu' ] || 'false' == $generate_menu_plus_settings[ 'slideout_menu' ] ) { ?>
		<script>
			jQuery( document ).ready( function( $ ) {
				$( '#mobile-header .menu-toggle' ).GenerateMobileMenu();
			});
		</script>
	<?php }
}
endif;

if ( ! function_exists( 'generate_menu_plus_mobile_header' ) ) :
add_action( 'generate_after_header', 'generate_menu_plus_mobile_header', 5 );
add_action( 'generate_inside_mobile_header','generate_navigation_search');
add_action( 'generate_inside_mobile_header','generate_mobile_menu_search_icon' );
function generate_menu_plus_mobile_header()
{
	$generate_menu_plus_settings = wp_parse_args( 
		get_option( 'generate_menu_plus_settings', array() ), 
		generate_menu_plus_get_defaults() 
	);
	
	if ( 'disable' == $generate_menu_plus_settings[ 'mobile_header' ] )
		return;
	
	?>
	<nav itemtype="http://schema.org/SiteNavigationElement" itemscope="itemscope" id="mobile-header" <?php generate_navigation_class( 'mobile-header-navigation' ); ?>>
		<div class="inside-navigation grid-container grid-parent">
			<?php do_action( 'generate_inside_mobile_header' ); ?>
			<button class="menu-toggle" aria-controls="mobile-menu" aria-expanded="false">
				<?php do_action( 'generate_inside_mobile_header_menu' ); ?>
				<span class="mobile-menu"><?php echo apply_filters('generate_mobile_menu_label', __( 'Menu', 'generatepress' ) ); ?></span>
			</button>
			<?php 
			wp_nav_menu( 
				array( 
					'theme_location' => apply_filters( 'generate_mobile_header_theme_location', 'primary' ),
					'container' => 'div',
					'container_class' => 'main-nav',
					'container_id' => 'mobile-menu',
					'menu_class' => '',
					'fallback_cb' => 'generate_menu_fallback',
					'items_wrap' => '<ul id="%1$s" class="%2$s ' . join( ' ', generate_get_menu_class() ) . '">%3$s</ul>'
				) 
			);
			?>
		</div><!-- .inside-navigation -->
	</nav><!-- #site-navigation -->
	<?php
}
endif;

if ( ! function_exists( 'generate_slideout_navigation' ) ) :
/**
 *
 * Build the navigation
 * @since 0.1
 *
 */
add_action( 'wp_footer','generate_slideout_navigation', 0 );
function generate_slideout_navigation()
{
	$generate_menu_plus_settings = wp_parse_args( 
		get_option( 'generate_menu_plus_settings', array() ), 
		generate_menu_plus_get_defaults() 
	);
	
	if ( 'false' == $generate_menu_plus_settings['slideout_menu'] )
		return;
	
	?>
	<nav itemtype="http://schema.org/SiteNavigationElement" itemscope="itemscope" id="generate-slideout-menu" <?php generate_slideout_navigation_class(); ?>>
		<div class="inside-navigation grid-container grid-parent">
			<?php do_action( 'generate_inside_slideout_navigation' ); ?>
			<div class="screen-reader-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'generatepress' ); ?>"><?php _e( 'Skip to content', 'generatepress' ); ?></a></div>
			<?php 
			wp_nav_menu( 
				array( 
					'theme_location' => 'slideout',
					'container' => 'div',
					'container_class' => 'main-nav',
					'menu_class' => '',
					'fallback_cb' => 'generate_slideout_menu_fallback',
					'items_wrap' => '<ul id="%1$s" class="%2$s ' . join( ' ', generate_get_slideout_menu_class() ) . '">%3$s</ul>'
				) 
			);
			?>
			<?php do_action( 'generate_after_slideout_navigation' ); ?>
		</div><!-- .inside-navigation -->
	</nav><!-- #site-navigation -->
	<div class="slideout-overlay" style="display: none;"></div>
	<?php
}
endif;

if ( ! function_exists( 'generate_slideout_menu_fallback' ) ) :
/**
 * Menu fallback. 
 *
 * @param  array $args
 * @return string
 * @since 1.1.4
 */
function generate_slideout_menu_fallback( $args )
{ 
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	?>
	<div class="main-nav">
		<ul <?php generate_slideout_menu_class(); ?>>
			<?php wp_list_pages('sort_column=menu_order&title_li='); ?>
		</ul>
	</div><!-- .main-nav -->
	<?php 
}
endif;

if ( ! function_exists( 'generate_slideout_navigation_class' ) ) :
/**
 * Display the classes for the slideout navigation.
 *
 * @since 0.1
 * @param string|array $class One or more classes to add to the class list.
 */
function generate_slideout_navigation_class( $class = '' ) {
	// Separates classes with a single space, collates classes for post DIV
	echo 'class="' . join( ' ', generate_get_slideout_navigation_class( $class ) ) . '"';
}
endif;

if ( ! function_exists( 'generate_get_slideout_navigation_class' ) ) :
/**
 * Retrieve the classes for the slideout navigation.
 *
 * @since 0.1
 * @param string|array $class One or more classes to add to the class list.
 * @return array Array of classes.
 */
function generate_get_slideout_navigation_class( $class = '' ) {

	$classes = array();

	if ( !empty($class) ) {
		if ( !is_array( $class ) )
			$class = preg_split('#\s+#', $class);
		$classes = array_merge($classes, $class);
	}

	$classes = array_map('esc_attr', $classes);

	return apply_filters('generate_slideout_navigation_class', $classes, $class);
}
endif;

if ( ! function_exists( 'generate_slideout_menu_class' ) ) :
/**
 * Display the classes for the slideout navigation.
 *
 * @since 0.1
 * @param string|array $class One or more classes to add to the class list.
 */
function generate_slideout_menu_class( $class = '' ) {
	// Separates classes with a single space, collates classes for post DIV
	echo 'class="' . join( ' ', generate_get_slideout_menu_class( $class ) ) . '"';
}
endif;

if ( ! function_exists( 'generate_get_slideout_menu_class' ) ) :
/**
 * Retrieve the classes for the slideout navigation.
 *
 * @since 0.1
 * @param string|array $class One or more classes to add to the class list.
 * @return array Array of classes.
 */
function generate_get_slideout_menu_class( $class = '' ) {

	$classes = array();

	if ( !empty($class) ) {
		if ( !is_array( $class ) )
			$class = preg_split('#\s+#', $class);
		$classes = array_merge($classes, $class);
	}

	$classes = array_map('esc_attr', $classes);

	return apply_filters('generate_slideout_menu_class', $classes, $class);
}
endif;

if ( ! function_exists( 'generate_slideout_menu_classes' ) ) :
/**
 * Adds custom classes to the menu
 * @since 0.1
 */
add_filter( 'generate_slideout_menu_class', 'generate_slideout_menu_classes');
function generate_slideout_menu_classes( $classes )
{
	
	$classes[] = 'slideout-menu';
	return $classes;
	
}
endif;

if ( ! function_exists( 'generate_slideout_navigation_classes' ) ) :
/**
 * Adds custom classes to the navigation
 * @since 0.1
 */
add_filter( 'generate_slideout_navigation_class', 'generate_slideout_navigation_classes');
function generate_slideout_navigation_classes( $classes )
{

	$slideout_effect = apply_filters( 'generate_menu_slideout_effect','overlay' );
	$slideout_position = apply_filters( 'generate_menu_slideout_position','left' );
	
	$classes[] = 'main-navigation';
	$classes[] = 'slideout-navigation';

	return $classes;
	
}
endif;

if ( ! function_exists( 'generate_slideout_body_classes' ) ) :
/**
 * Adds custom classes to body
 * @since 0.1
 */
add_filter( 'body_class', 'generate_slideout_body_classes');
function generate_slideout_body_classes( $classes )
{
	$generate_menu_plus_settings = wp_parse_args( 
		get_option( 'generate_menu_plus_settings', array() ), 
		generate_menu_plus_get_defaults() 
	);
	
	if ( 'false' !== $generate_menu_plus_settings['slideout_menu'] ) :
		$classes[] = 'slideout-enabled';
	endif;
	
	if ( 'mobile' == $generate_menu_plus_settings['slideout_menu'] ) :
		$classes[] = 'slideout-mobile';
	endif;
	
	if ( 'desktop' == $generate_menu_plus_settings['slideout_menu'] ) :
		$classes[] = 'slideout-desktop';
	endif;
	
	if ( 'both' == $generate_menu_plus_settings['slideout_menu'] ) :
		$classes[] = 'slideout-both';
	endif;
	
	if ( 'slide' == $generate_menu_plus_settings['sticky_menu_effect'] ) :
		$classes[] = 'sticky-menu-slide';
	endif;
	
	if ( 'fade' == $generate_menu_plus_settings['sticky_menu_effect'] ) :
		$classes[] = 'sticky-menu-fade';
	endif;
	
	if ( 'none' == $generate_menu_plus_settings['sticky_menu_effect'] ) :
		$classes[] = 'sticky-menu-no-transition';
	endif;
	
	if ( 'sticky-menu' == $generate_menu_plus_settings['sticky_menu_logo_position'] ) :
		$classes[] = 'navigation-logo-sticky';
	elseif ( 'menu' == $generate_menu_plus_settings['sticky_menu_logo_position'] ) :
		$classes[] = 'navigation-logo';
	elseif ( 'regular-menu' == $generate_menu_plus_settings['sticky_menu_logo_position'] ) :
		$classes[] = 'navigation-logo-regular';
	endif;
	
	if ( 'false' !== $generate_menu_plus_settings['sticky_menu'] ) :
		$classes[] = 'sticky-enabled';
	endif;
	
	if ( '' !== $generate_menu_plus_settings['sticky_menu_logo'] ) :
		if ( 'sticky-menu' == $generate_menu_plus_settings['sticky_menu_logo_position'] ) :
			$classes[] = 'sticky-menu-logo';
		elseif ( 'menu' == $generate_menu_plus_settings['sticky_menu_logo_position'] ) :
			$classes[] = 'menu-logo';
		elseif ( 'regular-menu' == $generate_menu_plus_settings['sticky_menu_logo_position'] ) :
			$classes[] = 'regular-menu-logo';
		endif;
		$classes[] = 'menu-logo-enabled';
	endif;
	
	if ( 'mobile' == $generate_menu_plus_settings['sticky_menu'] ) :
		$classes[] = 'mobile-sticky-menu';
	endif;
	
	if ( 'desktop' == $generate_menu_plus_settings['sticky_menu'] ) :
		$classes[] = 'desktop-sticky-menu';
	endif;
	
	if ( 'true' == $generate_menu_plus_settings['sticky_menu'] ) :
		$classes[] = 'both-sticky-menu';
	endif;
	
	if ( 'enable' == $generate_menu_plus_settings['mobile_header'] ) :
		$classes[] = 'mobile-header';
	endif;
	
	if ( '' !== $generate_menu_plus_settings['mobile_header_logo'] ) :
		$classes[] = 'mobile-header-logo';
	endif;
	
	if ( 'enable' == $generate_menu_plus_settings['mobile_header_sticky'] ) :
		$classes[] = 'mobile-header-sticky';
	endif;
	
	return $classes;
	
}
endif;

if ( ! function_exists( 'generate_menu_plus_slidebar_icon' ) ) :
/**
 * Add slidebar icon to primary menu if set
 *
 * @since 0.1
 */
add_filter( 'wp_nav_menu_items','generate_menu_plus_slidebar_icon', 10, 2 );
function generate_menu_plus_slidebar_icon( $nav, $args ) 
{
	$generate_menu_plus_settings = wp_parse_args( 
		get_option( 'generate_menu_plus_settings', array() ), 
		generate_menu_plus_get_defaults() 
	);
	
	// If the search icon isn't enabled, return the regular nav
	if ( 'desktop' !== $generate_menu_plus_settings['slideout_menu'] && 'both' !== $generate_menu_plus_settings['slideout_menu'] )
		return $nav;
	
	// If our primary menu is set, add the search icon
    if( $args->theme_location == 'primary' )
        return $nav . '<li class="slideout-toggle"><a href="#generate-slideout-menu" data-transition="overlay"></a></li>';
	
	// Our primary menu isn't set, return the regular nav
	// In this case, the search icon is added to the generate_menu_fallback() function in navigation.php
    return $nav;
}
endif;

if ( ! function_exists( 'generate_menu_plus_label' ) ) :
/**
 * Add mobile menu label
 *
 * @since 0.1
 */
add_filter( 'generate_mobile_menu_label','generate_menu_plus_label' );
function generate_menu_plus_label()
{
	$generate_menu_plus_settings = wp_parse_args( 
		get_option( 'generate_menu_plus_settings', array() ), 
		generate_menu_plus_get_defaults() 
	);
	
	return $generate_menu_plus_settings['mobile_menu_label'];
}
endif;

if ( ! function_exists( 'generate_menu_plus_sticky_logo' ) ) :
/**
 * Add logo to sticky menu
 *
 * @since 0.1
 */
add_action( 'generate_inside_navigation','generate_menu_plus_sticky_logo' );
function generate_menu_plus_sticky_logo()
{
	$generate_menu_plus_settings = wp_parse_args( 
		get_option( 'generate_menu_plus_settings', array() ), 
		generate_menu_plus_get_defaults() 
	);
	
	if ( '' == $generate_menu_plus_settings['sticky_menu_logo'] )
		return;
		 
	?>
	<div class="site-logo sticky-logo navigation-logo">
		<a href="<?php echo apply_filters( 'generate_logo_href' , esc_url( home_url( '/' ) ) ); ?>" title="<?php echo esc_attr( apply_filters( 'generate_logo_title', get_bloginfo( 'name', 'display' ) ) ); ?>" rel="home"><img class="header-image" src="<?php echo esc_url( apply_filters( 'generate_navigation_logo', $generate_menu_plus_settings['sticky_menu_logo'] ) ); ?>" alt="<?php echo esc_attr( apply_filters( 'generate_logo_title', get_bloginfo( 'name', 'display' ) ) ); ?>" /></a>
	</div>
	<?php
}
endif;

if ( ! function_exists( 'generate_menu_plus_mobile_header_logo' ) ) :
/**
 * Add logo to mobile header
 *
 * @since 0.1
 */
add_action( 'generate_inside_mobile_header','generate_menu_plus_mobile_header_logo' );
function generate_menu_plus_mobile_header_logo()
{
	$generate_menu_plus_settings = wp_parse_args( 
		get_option( 'generate_menu_plus_settings', array() ), 
		generate_menu_plus_get_defaults() 
	);
	
	if ( '' == $generate_menu_plus_settings['mobile_header_logo'] )
		return;
		 
	?>
	<div class="site-logo mobile-header-logo">
		<a href="<?php echo apply_filters( 'generate_logo_href' , esc_url( home_url( '/' ) ) ); ?>" title="<?php echo esc_attr( apply_filters( 'generate_logo_title', get_bloginfo( 'name', 'display' ) ) ); ?>" rel="home"><img class="header-image" src="<?php echo $generate_menu_plus_settings['mobile_header_logo']; ?>" alt="<?php echo esc_attr( apply_filters( 'generate_logo_title', get_bloginfo( 'name', 'display' ) ) ); ?>" /></a>
	</div>
	<?php
}
endif;

if ( ! function_exists( 'generate_menu_plus_sanitize_choices' ) ) :
/**
 * Sanitize choices
 */
function generate_menu_plus_sanitize_choices( $input, $setting ) {
	
	// Ensure input is a slug
	$input = sanitize_key( $input );
	
	// Get list of choices from the control
	// associated with the setting
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	// If the input is a valid key, return it;
	// otherwise, return the default
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}
endif;

if ( ! function_exists( 'generate_mobile_header_activated' ) ) :
function generate_mobile_header_activated()
{
	$generate_menu_plus_settings = wp_parse_args( 
		get_option( 'generate_menu_plus_settings', array() ), 
		generate_menu_plus_get_defaults() 
	);
	
	return ( 'enable' == $generate_menu_plus_settings[ 'mobile_header' ] ) ? true : false;
}
endif;

if ( ! function_exists( 'generate_sticky_navigation_activated' ) ) :
function generate_sticky_navigation_activated()
{
	$generate_menu_plus_settings = wp_parse_args( 
		get_option( 'generate_menu_plus_settings', array() ), 
		generate_menu_plus_get_defaults() 
	);
	
	return ( 'false' !== $generate_menu_plus_settings[ 'sticky_menu' ] ) ? true : false;
}
endif;