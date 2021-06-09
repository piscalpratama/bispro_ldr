<?php
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Set default options
 */
if ( !function_exists( 'generate_get_background_defaults' ) ) :
	function generate_get_background_defaults()
	{
		$generate_background_defaults = array(
			'body_image' => '',
			'body_repeat' => '',
			'body_size' => '',
			'body_attachment' => '',
			'body_position' => '',
			'header_image' => '',
			'header_repeat' => '',
			'header_size' => '',
			'header_attachment' => '',
			'header_position' => '',
			'nav_image' => '',
			'nav_repeat' => '',
			'nav_item_image' => '',
			'nav_item_repeat' => '',
			'nav_item_hover_image' => '',
			'nav_item_hover_repeat' => '',
			'nav_item_current_image' => '',
			'nav_item_current_repeat' => '',
			'sub_nav_image' => '',
			'sub_nav_repeat' => '',
			'sub_nav_item_image' => '',
			'sub_nav_item_repeat' => '',
			'sub_nav_item_hover_image' => '',
			'sub_nav_item_hover_repeat' => '',
			'sub_nav_item_current_image' => '',
			'sub_nav_item_current_repeat' => '',
			'content_image' => '',
			'content_repeat' => '',
			'content_size' => '',
			'content_attachment' => '',
			'content_position' => '',
			'sidebar_widget_image' => '',
			'sidebar_widget_repeat' => '',
			'sidebar_widget_size' => '',
			'sidebar_widget_attachment' => '',
			'sidebar_widget_position' => '',
			'footer_widget_image' => '',
			'footer_widget_repeat' => '',
			'footer_widget_size' => '',
			'footer_widget_attachment' => '',
			'footer_widget_position' => '',
			'footer_image' => '',
			'footer_repeat' => '',
			'footer_size' => '',
			'footer_attachment' => '',
			'footer_position' => '',
		);
		
		return apply_filters( 'generate_background_option_defaults', $generate_background_defaults );
	}
endif;

if ( ! function_exists( 'generate_backgrounds_customize' ) ) :
add_action( 'customize_register', 'generate_backgrounds_customize', 999 );
function generate_backgrounds_customize( $wp_customize )
{
		
	$defaults = generate_get_background_defaults();
	$wp_customize->add_setting('generate_backgrounds_misc');
	$dir = plugin_dir_path( __FILE__ );
	require_once $dir . 'controls.php';
	
	if ( method_exists( $wp_customize,'register_control_type' ) ) {
		$wp_customize->register_control_type( 'Generate_Backgrounds_Customize_Control' );
	}
	
	if ( class_exists( 'WP_Customize_Panel' ) ) :
		if ( ! $wp_customize->get_panel( 'generate_backgrounds_panel' ) ) {
			$wp_customize->add_panel( 'generate_backgrounds_panel', array(
				'capability'     => 'edit_theme_options',
				'theme_supports' => '',
				'title'          => __( 'Background Images','backgrounds' ),
				'priority'		 => 55
			) );
		}
	endif;
	
	$wp_customize->add_section(
		'backgrounds_section',
		array(
			'title' => __( 'Background Images','backgrounds' ),
			'capability' => 'edit_theme_options',
			'priority' => 50
		)
	);
	
	$wp_customize->add_section(
		'generate_backgrounds_body',
		array(
			'title' => __( 'Body','backgrounds' ),
			'capability' => 'edit_theme_options',
			'priority' => 5,
			'panel' => 'generate_backgrounds_panel'
		)
	);
	
	/**
	 * Body background
	 */
	
	$wp_customize->add_setting(
		'generate_background_settings[body_image]', array(
			'default' => $defaults['body_image'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( 
			$wp_customize, 
			'generate_backgrounds-body-image', 
			array(
				'section'    => 'generate_backgrounds_body',
				'settings'   => 'generate_background_settings[body_image]',
				'label' => __( 'Body','backgrounds' )
			)
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[body_repeat]',
		array(
			'default' => $defaults['body_repeat'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[body_repeat]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_body',
			'choices' => array(
				'' => __( 'Repeat','backgrounds' ),
				'repeat-x' => __( 'Repeat x','backgrounds' ),
				'repeat-y' => __( 'Repeat y','backgrounds' ),
				'no-repeat' => __( 'No Repeat','backgrounds' )
			),
			'settings' => 'generate_background_settings[body_repeat]',
			'priority' => 50
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[body_size]',
		array(
			'default' => $defaults['body_size'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[body_size]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_body',
			'choices' => array(
				'' => __( 'Size (Auto)','backgrounds' ),
				'100% auto' => __( '100% Width','backgrounds' ),
				'cover' => __( 'Cover','backgrounds' ),
				'contain' => __( 'Contain','backgrounds' )
			),
			'settings' => 'generate_background_settings[body_size]',
			'priority' => 100
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[body_attachment]',
		array(
			'default' => $defaults['body_attachment'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[body_attachment]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_body',
			'choices' => array(
				'' => __( 'Attachment','backgrounds' ),
				'fixed' => __( 'Fixed','backgrounds' ),
				'local' => __( 'Local','backgrounds' ),
				'inherit' => __( 'Inherit','backgrounds' )
			),
			'settings' => 'generate_background_settings[body_attachment]',
			'priority' => 150
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[body_position]', array(
			'default' => $defaults['body_position'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_html'
		)
	);
	
	$wp_customize->add_control( 
		new Generate_Backgrounds_Customize_Control( 
			$wp_customize, 
			'generate_background_settings[body_position]', 
			array(
				'section'    => 'generate_backgrounds_body',
				'settings'   => 'generate_background_settings[body_position]',
				'priority' => 200,
				'label' => 'left top, x% y%, xpos ypos (px)',
				'placeholder' => __('Position','backgrounds')
			)
		)
	);
	
	$wp_customize->add_section(
		'generate_backgrounds_header',
		array(
			'title' => __( 'Header','backgrounds' ),
			'capability' => 'edit_theme_options',
			'priority' => 10,
			'panel' => 'generate_backgrounds_panel'
		)
	);
	
	/**
	 * Header background
	 */
	
	$wp_customize->add_setting(
		'generate_background_settings[header_image]', array(
			'default' => $defaults['header_image'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( 
			$wp_customize, 
			'generate_backgrounds-header-image', 
			array(
				'section'    => 'generate_backgrounds_header',
				'settings'   => 'generate_background_settings[header_image]',
				'priority' => 350,
				'label' => __( 'Header','backgrounds' ),
			)
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[header_repeat]',
		array(
			'default' => $defaults['header_repeat'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[header_repeat]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_header',
			'choices' => array(
				'' => __( 'Repeat','backgrounds' ),
				'repeat-x' => __( 'Repeat x','backgrounds' ),
				'repeat-y' => __( 'Repeat y','backgrounds' ),
				'no-repeat' => __( 'No Repeat','backgrounds' )
			),
			'settings' => 'generate_background_settings[header_repeat]',
			'priority' => 400
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[header_size]',
		array(
			'default' => $defaults['header_size'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[header_size]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_header',
			'choices' => array(
				'' => __( 'Size (Auto)','backgrounds' ),
				'100% auto' => __( '100% Width','backgrounds' ),
				'cover' => __( 'Cover','backgrounds' ),
				'contain' => __( 'Contain','backgrounds' )
			),
			'settings' => 'generate_background_settings[header_size]',
			'priority' => 450
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[header_attachment]',
		array(
			'default' => $defaults['header_attachment'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[header_attachment]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_header',
			'choices' => array(
				'' => __( 'Attachment','backgrounds' ),
				'fixed' => __( 'Fixed','backgrounds' ),
				'local' => __( 'Local','backgrounds' ),
				'inherit' => __( 'Inherit','backgrounds' )
			),
			'settings' => 'generate_background_settings[header_attachment]',
			'priority' => 500
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[header_position]', array(
			'default' => $defaults['header_position'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_html'
		)
	);
	
	$wp_customize->add_control( 
		new Generate_Backgrounds_Customize_Control( 
			$wp_customize, 
			'generate_background_settings[header_position]', 
			array(
				'section'    => 'generate_backgrounds_header',
				'settings'   => 'generate_background_settings[header_position]',
				'priority' => 550,
				'label' => 'left top, x% y%, xpos ypos (px)',
				'placeholder' => __('Position','backgrounds')
			)
		)
	);
	
	$wp_customize->add_section(
		'generate_backgrounds_navigation',
		array(
			'title' => __( 'Primary Navigation','backgrounds' ),
			'capability' => 'edit_theme_options',
			'priority' => 15,
			'panel' => 'generate_backgrounds_panel'
		)
	);
	
	/**
	 * Navigation background
	 */
	
	$wp_customize->add_setting(
		'generate_background_settings[nav_image]', array(
			'default' => $defaults['nav_image'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( 
			$wp_customize, 
			'generate_background_settings[nav_image]', 
			array(
				'section'    => 'generate_backgrounds_navigation',
				'settings'   => 'generate_background_settings[nav_image]',
				'priority' => 750,
				'label' => __( 'Navigation','backgrounds' ), 
			)
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[nav_repeat]',
		array(
			'default' => $defaults['nav_repeat'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[nav_repeat]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_navigation',
			'choices' => array(
				'' => __( 'Repeat','backgrounds' ),
				'repeat-x' => __( 'Repeat x','backgrounds' ),
				'repeat-y' => __( 'Repeat y','backgrounds' ),
				'no-repeat' => __( 'No Repeat','backgrounds' )
			),
			'settings' => 'generate_background_settings[nav_repeat]',
			'priority' => 800
		)
	);
	
	/**
	 * Navigation item background
	 */
	
	$wp_customize->add_setting(
		'generate_background_settings[nav_item_image]', array(
			'default' => $defaults['nav_item_image'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( 
			$wp_customize, 
			'generate_backgrounds-nav-item-image', 
			array(
				'section'    => 'generate_backgrounds_navigation',
				'settings'   => 'generate_background_settings[nav_item_image]',
				'priority' => 950,
				'label' => __( 'Navigation Item','backgrounds' ), 
			)
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[nav_item_repeat]',
		array(
			'default' => $defaults['nav_item_repeat'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[nav_item_repeat]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_navigation',
			'choices' => array(
				'' => __( 'Repeat','backgrounds' ),
				'repeat-x' => __( 'Repeat x','backgrounds' ),
				'repeat-y' => __( 'Repeat y','backgrounds' ),
				'no-repeat' => __( 'No Repeat','backgrounds' )
			),
			'settings' => 'generate_background_settings[nav_item_repeat]',
			'priority' => 1000
		)
	);
	
	/**
	 * Navigation item hover background
	 */
	
	$wp_customize->add_setting(
		'generate_background_settings[nav_item_hover_image]', array(
			'default' => $defaults['nav_item_hover_image'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( 
			$wp_customize, 
			'generate_backgrounds-nav-item-hover-image', 
			array(
				'section'    => 'generate_backgrounds_navigation',
				'settings'   => 'generate_background_settings[nav_item_hover_image]',
				'priority' => 1150,
				'label' => __( 'Navigation Item Hover','backgrounds' ),
			)
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[nav_item_hover_repeat]',
		array(
			'default' => $defaults['nav_item_hover_repeat'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[nav_item_hover_repeat]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_navigation',
			'choices' => array(
				'' => __( 'Repeat','backgrounds' ),
				'repeat-x' => __( 'Repeat x','backgrounds' ),
				'repeat-y' => __( 'Repeat y','backgrounds' ),
				'no-repeat' => __( 'No Repeat','backgrounds' )
			),
			'settings' => 'generate_background_settings[nav_item_hover_repeat]',
			'priority' => 1200
		)
	);
	
	/**
	 * Navigation item current background
	 */
	
	$wp_customize->add_setting(
		'generate_background_settings[nav_item_current_image]', array(
			'default' => $defaults['nav_item_current_image'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( 
			$wp_customize, 
			'generate_backgrounds-nav-item-current-image', 
			array(
				'section'    => 'generate_backgrounds_navigation',
				'settings'   => 'generate_background_settings[nav_item_current_image]',
				'priority' => 1350,
				'label' => __( 'Navigation Item Current','backgrounds' ),
			)
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[nav_item_current_repeat]',
		array(
			'default' => $defaults['nav_item_current_repeat'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[nav_item_current_repeat]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_navigation',
			'choices' => array(
				'' => __( 'Repeat','backgrounds' ),
				'repeat-x' => __( 'Repeat x','backgrounds' ),
				'repeat-y' => __( 'Repeat y','backgrounds' ),
				'no-repeat' => __( 'No Repeat','backgrounds' )
			),
			'settings' => 'generate_background_settings[nav_item_current_repeat]',
			'priority' => 1400
		)
	);
	
	$wp_customize->add_section(
		'generate_backgrounds_subnavigation',
		array(
			'title' => __( 'Primary Sub-Navigation','backgrounds' ),
			'capability' => 'edit_theme_options',
			'priority' => 20,
			'panel' => 'generate_backgrounds_panel'
		)
	);
	
	/**
	 * Sub-Navigation item background
	 */
	
	$wp_customize->add_setting(
		'generate_background_settings[sub_nav_item_image]', array(
			'default' => $defaults['sub_nav_item_image'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( 
			$wp_customize, 
			'generate_background_settings[sub_nav_item_image]', 
			array(
				'section'    => 'generate_backgrounds_subnavigation',
				'settings'   => 'generate_background_settings[sub_nav_item_image]',
				'priority' => 1700,
				'label' => __( 'Sub-Navigation Item','backgrounds' ), 
			)
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[sub_nav_item_repeat]',
		array(
			'default' => $defaults['sub_nav_item_repeat'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[sub_nav_item_repeat]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_subnavigation',
			'choices' => array(
				'' => __( 'Repeat','backgrounds' ),
				'repeat-x' => __( 'Repeat x','backgrounds' ),
				'repeat-y' => __( 'Repeat y','backgrounds' ),
				'no-repeat' => __( 'No Repeat','backgrounds' )
			),
			'settings' => 'generate_background_settings[sub_nav_item_repeat]',
			'priority' => 1800
		)
	);
	
	/**
	 * Sub-Navigation item hover background
	 */
	
	$wp_customize->add_setting(
		'generate_background_settings[sub_nav_item_hover_image]', array(
			'default' => $defaults['sub_nav_item_hover_image'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( 
			$wp_customize, 
			'generate_background_settings[sub_nav_item_hover_image]', 
			array(
				'section'    => 'generate_backgrounds_subnavigation',
				'settings'   => 'generate_background_settings[sub_nav_item_hover_image]',
				'priority' => 2000,
				'label' => __( 'Sub-Navigation Item Hover','backgrounds' ), 
			)
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[sub_nav_item_hover_repeat]',
		array(
			'default' => $defaults['sub_nav_item_hover_repeat'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[sub_nav_item_hover_repeat]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_subnavigation',
			'choices' => array(
				'' => __( 'Repeat','backgrounds' ),
				'repeat-x' => __( 'Repeat x','backgrounds' ),
				'repeat-y' => __( 'Repeat y','backgrounds' ),
				'no-repeat' => __( 'No Repeat','backgrounds' )
			),
			'settings' => 'generate_background_settings[sub_nav_item_hover_repeat]',
			'priority' => 2100
		)
	);
	
	/**
	 * Sub-Navigation item current background
	 */
	
	$wp_customize->add_setting(
		'generate_background_settings[sub_nav_item_current_image]', array(
			'default' => $defaults['sub_nav_item_current_image'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( 
			$wp_customize, 
			'generate_background_settings[sub_nav_item_current_image]', 
			array(
				'section'    => 'generate_backgrounds_subnavigation',
				'settings'   => 'generate_background_settings[sub_nav_item_current_image]',
				'priority' => 2300,
				'label' => __( 'Sub-Navigation Item Current','backgrounds' ), 
			)
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[sub_nav_item_current_repeat]',
		array(
			'default' => $defaults['sub_nav_item_current_repeat'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[sub_nav_item_current_repeat]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_subnavigation',
			'choices' => array(
				'' => __( 'Repeat','backgrounds' ),
				'repeat-x' => __( 'Repeat x','backgrounds' ),
				'repeat-y' => __( 'Repeat y','backgrounds' ),
				'no-repeat' => __( 'No Repeat','backgrounds' )
			),
			'settings' => 'generate_background_settings[sub_nav_item_current_repeat]',
			'priority' => 2400
		)
	);
	
	$wp_customize->add_section(
		'generate_backgrounds_content',
		array(
			'title' => __( 'Content','backgrounds' ),
			'capability' => 'edit_theme_options',
			'priority' => 25,
			'panel' => 'generate_backgrounds_panel'
		)
	);
	
	/**
	 * Content background
	 */
	
	$wp_customize->add_setting(
		'generate_background_settings[content_image]', array(
			'default' => $defaults['content_image'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( 
			$wp_customize, 
			'generate_background_settings[content_image]', 
			array(
				'section'    => 'generate_backgrounds_content',
				'settings'   => 'generate_background_settings[content_image]',
				'priority' => 2700,
				'label' => __( 'Content','backgrounds' ), 
			)
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[content_repeat]',
		array(
			'default' => $defaults['content_repeat'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[content_repeat]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_content',
			'choices' => array(
				'' => __( 'Repeat','backgrounds' ),
				'repeat-x' => __( 'Repeat x','backgrounds' ),
				'repeat-y' => __( 'Repeat y','backgrounds' ),
				'no-repeat' => __( 'No Repeat','backgrounds' )
			),
			'settings' => 'generate_background_settings[content_repeat]',
			'priority' => 2800
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[content_size]',
		array(
			'default' => $defaults['content_size'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[content_size]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_content',
			'choices' => array(
				'' => __( 'Size (Auto)','backgrounds' ),
				'100% auto' => __( '100% Width','backgrounds' ),
				'cover' => __( 'Cover','backgrounds' ),
				'contain' => __( 'Contain','backgrounds' )
			),
			'settings' => 'generate_background_settings[content_size]',
			'priority' => 2900
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[content_attachment]',
		array(
			'default' => $defaults['content_attachment'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[content_attachment]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_content',
			'choices' => array(
				'' => __( 'Attachment','backgrounds' ),
				'fixed' => __( 'Fixed','backgrounds' ),
				'local' => __( 'Local','backgrounds' ),
				'inherit' => __( 'Inherit','backgrounds' )
			),
			'settings' => 'generate_background_settings[content_attachment]',
			'priority' => 3000
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[content_position]', array(
			'default' => $defaults['content_position'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_html'
		)
	);
	
	$wp_customize->add_control( 
		new Generate_Backgrounds_Customize_Control( 
			$wp_customize, 
			'generate_backgrounds-content-position', 
			array(
				'section'    => 'generate_backgrounds_content',
				'settings'   => 'generate_background_settings[content_position]',
				'priority' => 3100,
				'label' => 'left top, x% y%, xpos ypos (px)',
				'placeholder' => __('Position','backgrounds')
			)
		)
	);
	
	$wp_customize->add_section(
		'generate_backgrounds_sidebars',
		array(
			'title' => __( 'Sidebar','backgrounds' ),
			'capability' => 'edit_theme_options',
			'priority' => 25,
			'panel' => 'generate_backgrounds_panel'
		)
	);
	
	/**
	 * Sidebar widget background
	 */
	
	$wp_customize->add_setting(
		'generate_background_settings[sidebar_widget_image]', array(
			'default' => $defaults['sidebar_widget_image'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( 
			$wp_customize, 
			'generate_background_settings[sidebar_widget_image]', 
			array(
				'section'    => 'generate_backgrounds_sidebars',
				'settings'   => 'generate_background_settings[sidebar_widget_image]',
				'priority' => 3400,
				'label' => __( 'Sidebar Widgets','backgrounds' ),
			)
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[sidebar_widget_repeat]',
		array(
			'default' => $defaults['sidebar_widget_repeat'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[sidebar_widget_repeat]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_sidebars',
			'choices' => array(
				'' => __( 'Repeat','backgrounds' ),
				'repeat-x' => __( 'Repeat x','backgrounds' ),
				'repeat-y' => __( 'Repeat y','backgrounds' ),
				'no-repeat' => __( 'No Repeat','backgrounds' )
			),
			'settings' => 'generate_background_settings[sidebar_widget_repeat]',
			'priority' => 3500
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[sidebar_widget_size]',
		array(
			'default' => $defaults['sidebar_widget_size'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[sidebar_widget_size]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_sidebars',
			'choices' => array(
				'' => __( 'Size (Auto)','backgrounds' ),
				'100% auto' => __( '100% Width','backgrounds' ),
				'cover' => __( 'Cover','backgrounds' ),
				'contain' => __( 'Contain','backgrounds' )
			),
			'settings' => 'generate_background_settings[sidebar_widget_size]',
			'priority' => 3600
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[sidebar_widget_attachment]',
		array(
			'default' => $defaults['sidebar_widget_attachment'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[sidebar_widget_attachment]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_sidebars',
			'choices' => array(
				'' => __( 'Attachment','backgrounds' ),
				'fixed' => __( 'Fixed','backgrounds' ),
				'local' => __( 'Local','backgrounds' ),
				'inherit' => __( 'Inherit','backgrounds' )
			),
			'settings' => 'generate_background_settings[sidebar_widget_attachment]',
			'priority' => 3700
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[sidebar_widget_position]', array(
			'default' => $defaults['sidebar_widget_position'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_html'
		)
	);
	
	$wp_customize->add_control( 
		new Generate_Backgrounds_Customize_Control( 
			$wp_customize, 
			'generate_background_settings[sidebar_widget_position]', 
			array(
				'section'    => 'generate_backgrounds_sidebars',
				'settings'   => 'generate_background_settings[sidebar_widget_position]',
				'priority' => 3800,
				'label' => 'left top, x% y%, xpos ypos (px)',
				'placeholder' => __('Position','backgrounds')
			)
		)
	);
	
	$wp_customize->add_section(
		'generate_backgrounds_footer',
		array(
			'title' => __( 'Footer','backgrounds' ),
			'capability' => 'edit_theme_options',
			'priority' => 30,
			'panel' => 'generate_backgrounds_panel'
		)
	);
	
	/**
	 * Footer widget background
	 */
	
	$wp_customize->add_setting(
		'generate_background_settings[footer_widget_image]', array(
			'default' => $defaults['footer_widget_image'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( 
			$wp_customize, 
			'generate_background_settings[footer_widget_image]', 
			array(
				'section'    => 'generate_backgrounds_footer',
				'settings'   => 'generate_background_settings[footer_widget_image]',
				'priority' => 4100,
				'label' => __( 'Footer Widget Area','backgrounds' ), 
			)
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[footer_widget_repeat]',
		array(
			'default' => $defaults['footer_widget_repeat'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[footer_widget_repeat]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_footer',
			'choices' => array(
				'' => __( 'Repeat','backgrounds' ),
				'repeat-x' => __( 'Repeat x','backgrounds' ),
				'repeat-y' => __( 'Repeat y','backgrounds' ),
				'no-repeat' => __( 'No Repeat','backgrounds' )
			),
			'settings' => 'generate_background_settings[footer_widget_repeat]',
			'priority' => 4200
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[footer_widget_size]',
		array(
			'default' => $defaults['footer_widget_size'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[footer_widget_size]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_footer',
			'choices' => array(
				'' => __( 'Size (Auto)','backgrounds' ),
				'100% auto' => __( '100% Width','backgrounds' ),
				'cover' => __( 'Cover','backgrounds' ),
				'contain' => __( 'Contain','backgrounds' )
			),
			'settings' => 'generate_background_settings[footer_widget_size]',
			'priority' => 4300
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[footer_widget_attachment]',
		array(
			'default' => $defaults['footer_widget_attachment'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[footer_widget_attachment]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_footer',
			'choices' => array(
				'' => __( 'Attachment','backgrounds' ),
				'fixed' => __( 'Fixed','backgrounds' ),
				'local' => __( 'Local','backgrounds' ),
				'inherit' => __( 'Inherit','backgrounds' )
			),
			'settings' => 'generate_background_settings[footer_widget_attachment]',
			'priority' => 4400
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[footer_widget_position]', array(
			'default' => $defaults['footer_widget_position'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_html'
		)
	);
	
	$wp_customize->add_control( 
		new Generate_Backgrounds_Customize_Control( 
			$wp_customize, 
			'generate_background_settings[footer_widget_position]', 
			array(
				'section'    => 'generate_backgrounds_footer',
				'settings'   => 'generate_background_settings[footer_widget_position]',
				'priority' => 4500,
				'label' => 'left top, x% y%, xpos ypos (px)',
				'placeholder' => __('Position','backgrounds')
			)
		)
	);
	
	/**
	 * Footer background
	 */
	
	$wp_customize->add_setting(
		'generate_background_settings[footer_image]', array(
			'default' => $defaults['footer_image'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw'
		)
	);
	
	$wp_customize->add_control( 
		new WP_Customize_Image_Control( 
			$wp_customize, 
			'generate_backgrounds-footer-image', 
			array(
				'section'    => 'generate_backgrounds_footer',
				'settings'   => 'generate_background_settings[footer_image]',
				'priority' => 4800,
				'label' => __( 'Footer Area','backgrounds' ), 
			)
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[footer_repeat]',
		array(
			'default' => $defaults['footer_repeat'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[footer_repeat]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_footer',
			'choices' => array(
				'' => __( 'Repeat','backgrounds' ),
				'repeat-x' => __( 'Repeat x','backgrounds' ),
				'repeat-y' => __( 'Repeat y','backgrounds' ),
				'no-repeat' => __( 'No Repeat','backgrounds' )
			),
			'settings' => 'generate_background_settings[footer_repeat]',
			'priority' => 4900
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[footer_size]',
		array(
			'default' => $defaults['footer_size'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[footer_size]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_footer',
			'choices' => array(
				'' => __( 'Size (Auto)','backgrounds' ),
				'100% auto' => __( '100% Width','backgrounds' ),
				'cover' => __( 'Cover','backgrounds' ),
				'contain' => __( 'Contain','backgrounds' )
			),
			'settings' => 'generate_background_settings[footer_size]',
			'priority' => 5000
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[footer_attachment]',
		array(
			'default' => $defaults['footer_attachment'],
			'type' => 'option',
			'sanitize_callback' => 'generate_backgrounds_sanitize_choices'
		)
	);
	
	$wp_customize->add_control(
		'generate_background_settings[footer_attachment]',
		array(
			'type' => 'select',
			'section' => 'generate_backgrounds_footer',
			'choices' => array(
				'' => __( 'Attachment','backgrounds' ),
				'fixed' => __( 'Fixed','backgrounds' ),
				'local' => __( 'Local','backgrounds' ),
				'inherit' => __( 'Inherit','backgrounds' )
			),
			'settings' => 'generate_background_settings[footer_attachment]',
			'priority' => 5100
		)
	);
	
	$wp_customize->add_setting(
		'generate_background_settings[footer_position]', array(
			'default' => $defaults['footer_position'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_html'
		)
	);
	
	$wp_customize->add_control( 
		new Generate_Backgrounds_Customize_Control( 
			$wp_customize, 
			'generate_background_settings[footer_position]', 
			array(
				'section'    => 'generate_backgrounds_footer',
				'settings'   => 'generate_background_settings[footer_position]',
				'priority' => 5200,
				'label' => 'left top, x% y%, xpos ypos (px)',
				'placeholder' => __('Position','backgrounds')
			)
		)
	);
	
}
endif;

if ( ! function_exists( 'generate_backgrounds_customize_preview_css' ) ) :
add_action('customize_controls_print_styles', 'generate_backgrounds_customize_preview_css');
function generate_backgrounds_customize_preview_css() {

	?>
	<style>
		#accordion-section-backgrounds_section li {float:left;width:45%;clear:none;}
		#accordion-section-backgrounds_section li.customize-control-backgrounds-heading,
		#accordion-section-backgrounds_section li.customize-control-position,
		#accordion-section-backgrounds_section li.customize-control-line		{display:block;width:100%;clear:both;text-align:center;}
		#accordion-section-backgrounds_section .generate-upload .remove {font-size:10px;display: inline;}
		#accordion-section-backgrounds_section li.customize-control-position .small-customize-label {display:block;text-align:center;}
		
		#accordion-section-backgrounds_section .customize-section-description-container {
			width: 100%;
			float: none;
		}
		
		#customize-control-generate_backgrounds-header,
		#customize-control-generate_backgrounds-header-heading,
		#customize-control-generate_backgrounds-nav-heading,
		#customize-control-generate_backgrounds-sub-nav-item-heading,
		#customize-control-generate_backgrounds-content-heading,
		#customize-control-generate_backgrounds-sidebar-widget-heading,
		#customize-control-generate_backgrounds-footer-widget-heading {
			border: 0;
			padding: 0;
			margin-top: 0;
		}
		
		#accordion-section-generate_backgrounds_navigation .customize-control-select:not(#customize-control-generate_background_settings-nav_item_current_repeat),
		#accordion-section-generate_backgrounds_subnavigation .customize-control-select:not(#customize-control-generate_background_settings-sub_nav_item_current_repeat),
		#accordion-section-secondary_bg_images_section .customize-control-select:not(#customize-control-generate_secondary_nav_settings-nav_item_current_repeat),
		#accordion-section-secondary_subnav_bg_images_section .customize-control-select:not(#customize-control-generate_secondary_nav_settings-sub_nav_item_current_repeat),
		#customize-control-generate_background_settings-footer_widget_position {
			border-bottom: 1px solid #ccc;
			padding-bottom: 20px;
		}
	</style>
	<?php
}
endif;
/**
 * Generate the CSS in the <head> section using the Theme Customizer
 * @since 0.1
 */
if ( !function_exists( 'generate_backgrounds_css' ) ) :
	function generate_backgrounds_css()
	{
		
		$generate_settings = wp_parse_args( 
			get_option( 'generate_background_settings', array() ), 
			generate_get_background_defaults() 
		);
		$space = ' ';

		// Start the magic
		$visual_css = array (
		
			// Body
			'body' => array(
				'background-image' => !empty( $generate_settings['body_image'] ) ? 'url(' . $generate_settings['body_image'] . ')' : '',
				'background-repeat' => $generate_settings['body_repeat'],
				'background-size' => $generate_settings['body_size'],
				'background-attachment' => $generate_settings['body_attachment'],
				'background-position' => $generate_settings['body_position']
			),
			
			// Header
			'.site-header' => array(
				'background-image' => !empty( $generate_settings['header_image'] ) ? 'url(' . $generate_settings['header_image'] . ')' : '',
				'background-repeat' => $generate_settings['header_repeat'],
				'background-size' => $generate_settings['header_size'],
				'background-attachment' => $generate_settings['header_attachment'],
				'background-position' => $generate_settings['header_position']
			),
			
			// Navigation background
			'.main-navigation,
			.menu-toggle' => array(
				'background-image' => !empty( $generate_settings['nav_image'] ) ? 'url(' . $generate_settings['nav_image'] . ')' : '',
				'background-repeat' => $generate_settings['nav_repeat']
			),
			
			// Navigation item background
			'.main-navigation .main-nav > ul > li > a' => array(
				'background-image' => !empty( $generate_settings['nav_item_image'] ) ? 'url(' . $generate_settings['nav_item_image'] . ')' : '',
				'background-repeat' => $generate_settings['nav_item_repeat']
			),
			
			// Navigation background/text on hover
			'.main-navigation .main-nav > ul > li > a:hover, 
			.main-navigation .main-nav > ul > li.sfHover > a' => array(
				'background-image' => !empty( $generate_settings['nav_item_hover_image'] ) ? 'url(' . $generate_settings['nav_item_hover_image'] . ')' : '',
				'background-repeat' => $generate_settings['nav_item_hover_repeat']
			),
			
			// Navigation background / text current
			'.main-navigation .main-nav > ul > .current-menu-item > a, 
			.main-navigation .main-nav > ul > .current-menu-parent > a, 
			.main-navigation .main-nav > ul > .current-menu-ancestor > a' => array(
				'background-image' => !empty( $generate_settings['nav_item_current_image'] ) ? 'url(' . $generate_settings['nav_item_current_image'] . ')' : '',
				'background-repeat' => $generate_settings['nav_item_current_repeat']
			),
			
			// Navigation background text current text hover
			'.main-navigation .main-nav > ul > .current-menu-item > a:hover, 
			.main-navigation .main-nav > ul > .current-menu-parent > a:hover, 
			.main-navigation .main-nav > ul > .current-menu-ancestor > a:hover, 
			.main-navigation .main-nav > ul > .current-menu-item.sfHover > a, 
			.main-navigation .main-nav > ul > .current-menu-parent.sfHover > a, 
			.main-navigation .main-nav > ul > .current-menu-ancestor.sfHover > a' => array(
				'background-image' => !empty( $generate_settings['nav_item_current_image'] ) ? 'url(' . $generate_settings['nav_item_current_image'] . ')' : '',
				'background-repeat' => $generate_settings['nav_item_current_repeat']
			),
			
			// Sub-Navigation text
			'.main-navigation ul ul li a' => array(
				'background-image' => !empty( $generate_settings['sub_nav_item_image'] ) ? 'url(' . $generate_settings['sub_nav_item_image'] . ')' : '',
				'background-repeat' => $generate_settings['sub_nav_item_repeat']
			),
			
			// Sub-Navigation background/text on hover
			'.main-navigation ul ul li > a:hover, 
			.main-navigation ul ul li.sfHover > a' => array(
				'background-image' => !empty( $generate_settings['sub_nav_item_hover_image'] ) ? 'url(' . $generate_settings['sub_nav_item_hover_image'] . ')' : '',
				'background-repeat' => $generate_settings['sub_nav_item_hover_repeat']
			),
			
			// Sub-Navigation background / text current
			'.main-navigation ul ul .current-menu-item > a, 
			.main-navigation ul ul .current-menu-parent > a, 
			.main-navigation ul ul .current-menu-ancestor > a' => array(
				'background-image' => !empty( $generate_settings['sub_nav_item_current_image'] ) ? 'url(' . $generate_settings['sub_nav_item_current_image'] . ')' : '',
				'background-repeat' => $generate_settings['sub_nav_item_current_repeat']
			),
			
			// Sub-Navigation current background / text current
			'.main-navigation ul ul .current-menu-item > a:hover, 
			.main-navigation ul ul .current-menu-parent > a:hover, 
			.main-navigation ul ul .current-menu-ancestor > a:hover,
			.main-navigation ul ul .current-menu-item.sfHover > a, 
			.main-navigation ul ul .current-menu-parent.sfHover > a, 
			.main-navigation ul ul .current-menu-ancestor.sfHover > a' => array(
				'background-image' => !empty( $generate_settings['sub_nav_item_current_image'] ) ? 'url(' . $generate_settings['sub_nav_item_current_image'] . ')' : '',
				'background-repeat' => $generate_settings['sub_nav_item_current_repeat']
			),

			// Content
			'.separate-containers .inside-article, 
			.separate-containers .comments-area, 
			.separate-containers .page-header,
			.one-container .container,
			.separate-containers .paging-navigation,
			.separate-containers .inside-page-header' => array(
				'background-image' => !empty( $generate_settings['content_image'] ) ? 'url(' . $generate_settings['content_image'] . ')' : '',
				'background-repeat' => $generate_settings['content_repeat'],
				'background-size' => $generate_settings['content_size'],
				'background-attachment' => $generate_settings['content_attachment'],
				'background-position' => $generate_settings['content_position']
			),
			
			// Sidebar widget
			'.sidebar .widget' => array(
				'background-image' => !empty( $generate_settings['sidebar_widget_image'] ) ? 'url(' . $generate_settings['sidebar_widget_image'] . ')' : '',
				'background-repeat' => $generate_settings['sidebar_widget_repeat'],
				'background-size' => $generate_settings['sidebar_widget_size'],
				'background-attachment' => $generate_settings['sidebar_widget_attachment'],
				'background-position' => $generate_settings['sidebar_widget_position']
			),
			
			// Footer widget
			'.footer-widgets' => array(
				'background-image' => !empty( $generate_settings['footer_widget_image'] ) ? 'url(' . $generate_settings['footer_widget_image'] . ')' : '',
				'background-repeat' => $generate_settings['footer_widget_repeat'],
				'background-size' => $generate_settings['footer_widget_size'],
				'background-attachment' => $generate_settings['footer_widget_attachment'],
				'background-position' => $generate_settings['footer_widget_position']
			),
			
			// Footer
			'.site-info' => array(
				'background-image' => !empty( $generate_settings['footer_image'] ) ? 'url(' . $generate_settings['footer_image'] . ')' : '',
				'background-repeat' => $generate_settings['footer_repeat'],
				'background-size' => $generate_settings['footer_size'],
				'background-attachment' => $generate_settings['footer_attachment'],
				'background-position' => $generate_settings['footer_position']
			),
			
		);
		
		// Output the above CSS
		$output = '';
		foreach($visual_css as $k => $properties) {
			if(!count($properties))
				continue;

			$temporary_output = $k . ' {';
			$elements_added = 0;

			foreach($properties as $p => $v) {
				if(empty($v))
					continue;

				$elements_added++;
				$temporary_output .= $p . ': ' . $v . '; ';
			}

			$temporary_output .= "}";

			if($elements_added > 0)
				$output .= $temporary_output;
		}
		
		$output = str_replace(array("\r", "\n", "\t"), '', $output);
		return $output;
	}
	
	/**
	 * Enqueue scripts and styles
	 */
	add_action( 'wp_enqueue_scripts', 'generate_background_scripts', 70 );
	function generate_background_scripts() {

		wp_add_inline_style( 'generate-style', generate_backgrounds_css() );
	
	}
endif;

if ( ! function_exists( 'generate_backgrounds_sanitize_choices' ) ) :
/**
 * Sanitize choices
 */
function generate_backgrounds_sanitize_choices( $input, $setting ) {
	
	// Ensure input is a slug
	$input = sanitize_text_field( $input );
	
	// Get list of choices from the control
	// associated with the setting
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	// If the input is a valid key, return it;
	// otherwise, return the default
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}
endif;