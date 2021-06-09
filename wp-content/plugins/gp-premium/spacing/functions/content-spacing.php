<?php
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

$wp_customize->add_section(
	// ID
	'generate_spacing_content',
	// Arguments array
	array(
		'title' => __( 'Content', 'generate-spacing' ),
		'capability' => 'edit_theme_options',
		'priority' => 10,
		'panel' => 'generate_spacing_panel'
	)
);

if ( $wp_customize->get_panel( 'generate_layout_panel' ) ) {
	$content_section = 'generate_layout_container';
} else {
	$content_section = 'generate_spacing_content';
}

$wp_customize->add_setting(
	'generate_spacing_settings[separator]', array(
		'default' => $defaults['separator'],
		'type' => 'option', 
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'absint',
		'transport' => 'postMessage'
	)
);

$wp_customize->add_control(
	new Generate_Customize_Spacing_Slider_Control(
		$wp_customize,
		'generate_spacing_settings[separator]', 
		array(
			'label' => __( 'Separating Space', 'generate-spacing' ), 
			'section' => $content_section,
			'settings' => 'generate_spacing_settings[separator]',
			'priority' => 80,
			'secondary_description' => __( 'The spacing between elements when "Content Layout" is set to "Separate Containers".', 'generate-spacing' ),
			'type' => 'gp-spacing-slider',
			'default_value' => $defaults['separator'],
			'unit' => 'px'
		)
	)
);

$wp_customize->add_control(
	new Generate_Spacing_Customize_Misc_Control(
		$wp_customize,
		'generate_content_spacing_title',
		array(
			'section'  => $content_section,
			'description'    => __( 'Content Padding', 'generate-spacing' ),
			'type'     => 'text',
			'priority' => 99,
		)
	)
);
	
// Header top
$wp_customize->add_setting(
	'generate_spacing_settings[content_top]', array(
		'default' => $defaults['content_top'],
		'type' => 'option', 
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'absint',
		'transport' => 'postMessage'
	)
);

$wp_customize->add_control(
	new Generate_Spacing_Customize_Control(
		$wp_customize,
		'generate_spacing_settings[content_top]', 
		array(
			'description' => __('Top', 'generate-spacing' ), 
			'section' => $content_section,
			'settings' => 'generate_spacing_settings[content_top]',
			'priority' => 100
		)
	)
);

// Header right
$wp_customize->add_setting(
	'generate_spacing_settings[content_right]', array(
		'default' => $defaults['content_right'],
		'type' => 'option', 
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'absint',
		'transport' => 'postMessage'
	)
);

$wp_customize->add_control(
	new Generate_Spacing_Customize_Control(
		$wp_customize,
		'generate_spacing_settings[content_right]', 
		array(
			'description' => __('Right', 'generate-spacing' ), 
			'section' => $content_section,
			'settings' => 'generate_spacing_settings[content_right]',
			'priority' => 105
		)
	)
);

// Header bottom
$wp_customize->add_setting(
	'generate_spacing_settings[content_bottom]', array(
		'default' => $defaults['content_bottom'],
		'type' => 'option', 
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'absint',
		'transport' => 'postMessage'
	)
);

$wp_customize->add_control(
	new Generate_Spacing_Customize_Control(
		$wp_customize,
		'generate_spacing_settings[content_bottom]', 
		array(
			'description' => __('Bottom', 'generate-spacing' ), 
			'section' => $content_section,
			'settings' => 'generate_spacing_settings[content_bottom]',
			'priority' => 110
		)
	)
);

// Header left
$wp_customize->add_setting(
	'generate_spacing_settings[content_left]', array(
		'default' => $defaults['content_left'],
		'type' => 'option', 
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'absint',
		'transport' => 'postMessage'
	)
);

$wp_customize->add_control(
	new Generate_Spacing_Customize_Control(
		$wp_customize,
		'generate_spacing_settings[content_left]', 
		array(
			'description' => __('Left', 'generate-spacing' ), 
			'section' => $content_section,
			'settings' => 'generate_spacing_settings[content_left]',
			'priority' => 115
		)
	)
);

if ( isset( $defaults['mobile_content_padding'] ) ) {
	$wp_customize->add_setting(
		'generate_spacing_settings[mobile_content_padding]', array(
			'default' => $defaults['mobile_content_padding'],
			'type' => 'option', 
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'absint',
			'transport' => 'postMessage'
		)
	);

	$wp_customize->add_control(
		new Generate_Customize_Spacing_Slider_Control(
			$wp_customize,
			'generate_spacing_settings[mobile_content_padding]', 
			array(
				'label' => __( 'Mobile content padding', 'generate-spacing' ), 
				'section' => $content_section,
				'settings' => 'generate_spacing_settings[mobile_content_padding]',
				'priority' => 116,
				'type' => 'gp-spacing-slider',
				'default_value' => $defaults['mobile_content_padding'],
				'unit' => 'px'
			)
		)
	);
}