<?php
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'generate_excerpt_length' ) ) :
add_filter( 'excerpt_length', 'generate_excerpt_length', 999 );
function generate_excerpt_length( $length ) {
	$generate_settings = wp_parse_args( 
		get_option( 'generate_blog_settings', array() ), 
		generate_blog_get_defaults() 
	);
	return $generate_settings['excerpt_length'];
}
endif;

if ( ! function_exists( 'generate_blog_css' ) ) :
function generate_blog_css()
{
	$generate_blog_settings = wp_parse_args( 
		get_option( 'generate_blog_settings', array() ), 
		generate_blog_get_defaults() 
	);
	
	global $post;
	
	// Get disable headline meta
	$disable_headline = ( isset( $post ) ) ? get_post_meta( $post->ID, '_generate-disable-headline', true ) : '';
	
	$return = '';
	
	if ( 'false' == $generate_blog_settings['categories'] && 'false' == $generate_blog_settings['comments'] && 'false' == $generate_blog_settings['tags'] ) :
		$return .= '.blog footer.entry-meta, .archive footer.entry-meta {display:none;}';
	endif;
	
	if ( 'false' == $generate_blog_settings['date'] && 'false' == $generate_blog_settings['author'] && $disable_headline ) :
		$return .= '.single .entry-header{display:none;}.single .entry-content {margin-top:0;}';
	endif;
	
	if ( 'false' == $generate_blog_settings['date'] && 'false' == $generate_blog_settings['author'] ) :
		$return .= '.entry-header .entry-meta {display:none;}';
	endif;
	
	if ( function_exists( 'generate_spacing_get_defaults' ) ) :
		$spacing_settings = wp_parse_args( 
			get_option( 'generate_spacing_settings', array() ), 
			generate_spacing_get_defaults() 
		);
	endif;
	
	$separator = ( function_exists('generate_spacing_get_defaults') ) ? $spacing_settings['separator'] : 20;
	
	if ( 'true' == generate_blog_get_masonry() ) {
		$return .= '.masonry-post .inside-article {margin-left: ' . $separator . 'px}';
		$return .= '.masonry-container > article {margin-bottom: ' . $separator . 'px;}';
		$return .= '.masonry-container {margin-left: -' . $separator . 'px;}';
		$return .= '.page-header {margin-bottom: ' . $separator . 'px;margin-left: ' . $separator . 'px}';
		$return .= '.separate-containers .site-main > .masonry-load-more {margin-bottom: ' . $separator . 'px;}';
	}
	
	$elements = array(
		1 => array(
			'id' => 'date',
			'class' => 'posted-on'
		),
		2 => array(
			'id' => 'author',
			'class' => 'byline, .single .byline, .group-blog .byline'
		),
		3 => array(
			'id' => 'categories',
			'class' => 'cat-links'
		),
		4 => array(
			'id' => 'comments',
			'class' => 'comments-link'
		),
		5 => array(
			'id' => 'tags',
			'class' => 'tags-links'
		)
	);
	
	foreach ( $elements as $element ) {
		if ( 'false' == $generate_blog_settings[$element['id']] ) :
			$return .= '.' . $element['class'] . '{display:none;}';
		endif;
	}
	
	return $return;
}
endif;

if ( ! function_exists( 'generate_blog_excerpt_more' ) ) :
/**
 * Prints the read more HTML
 */
add_filter( 'excerpt_more', 'generate_blog_excerpt_more', 99 );
function generate_blog_excerpt_more( $more ) {
	$generate_settings = wp_parse_args( 
		get_option( 'generate_blog_settings', array() ), 
		generate_blog_get_defaults() 
	);
	
	// If empty, return
	if ( '' == $generate_settings['read_more'] )
		return;
		
	return ' ... <a title="' . esc_attr( get_the_title() ) . '" class="read-more" href="'. esc_url( get_permalink( get_the_ID() ) ) . '">' . $generate_settings['read_more'] . '</a>';
}
endif;

if ( ! function_exists( 'generate_blog_content_more' ) ) :
/**
 * Prints the read more HTML
 */
add_filter( 'the_content_more_link', 'generate_blog_content_more', 99 );
function generate_blog_content_more( $more ) {
	$generate_settings = wp_parse_args( 
		get_option( 'generate_blog_settings', array() ), 
		generate_blog_get_defaults() 
	);
	
	// If empty, return
	if ( '' == $generate_settings['read_more'] )
		return;
		
	$more_jump = apply_filters( 'generate_more_jump','#more-' . get_the_ID() );
		
	return '<p class="read-more-container"><a class="read-more content-read-more" href="'. get_permalink( get_the_ID() ) . $more_jump . '">' . $generate_settings['read_more'] . '</a></p>';
}
endif;

if ( ! function_exists( 'generate_disable_post_date' ) ) :
add_filter( 'generate_post_date', 'generate_disable_post_date' );
function generate_disable_post_date()
{
	$generate_blog_settings = wp_parse_args( 
		get_option( 'generate_blog_settings', array() ), 
		generate_blog_get_defaults() 
	);
	
	if ( 'false' == $generate_blog_settings['date'] )
		return false;
	
	return true;
}
endif;

if ( ! function_exists( 'generate_disable_post_author' ) ) :
add_filter( 'generate_post_author', 'generate_disable_post_author' );
function generate_disable_post_author()
{
	$generate_blog_settings = wp_parse_args( 
		get_option( 'generate_blog_settings', array() ), 
		generate_blog_get_defaults() 
	);
	
	if ( 'false' == $generate_blog_settings['author'] )
		return false;
	
	return true;
}
endif;

if ( ! function_exists( 'generate_disable_post_categories' ) ) :
add_filter( 'generate_show_categories', 'generate_disable_post_categories' );
function generate_disable_post_categories()
{
	$generate_blog_settings = wp_parse_args( 
		get_option( 'generate_blog_settings', array() ), 
		generate_blog_get_defaults() 
	);
	
	if ( 'false' == $generate_blog_settings['categories'] )
		return false;
	
	return true;
}
endif;

if ( ! function_exists( 'generate_disable_post_tags' ) ) :
add_filter( 'generate_show_tags', 'generate_disable_post_tags' );
function generate_disable_post_tags()
{
	$generate_blog_settings = wp_parse_args( 
		get_option( 'generate_blog_settings', array() ), 
		generate_blog_get_defaults() 
	);
	
	if ( 'false' == $generate_blog_settings['tags'] )
		return false;
	
	return true;
}
endif;

if ( ! function_exists( 'generate_disable_post_comments_link' ) ) :
add_filter( 'generate_show_comments', 'generate_disable_post_comments_link' );
function generate_disable_post_comments_link()
{
	$generate_blog_settings = wp_parse_args( 
		get_option( 'generate_blog_settings', array() ), 
		generate_blog_get_defaults() 
	);
	
	if ( 'false' == $generate_blog_settings['comments'] )
		return false;
	
	return true;
}
endif;