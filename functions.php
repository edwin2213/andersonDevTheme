<?
if( !defined('TMPL_DIR')):
	define('TMPL_DIR' , get_template_directory() );
endif;
if( !defined('TMPL_DIR_URI')):
	define('TMPL_DIR_URI' , get_template_directory_uri() );
endif;

/**
*	rm-functions.php must be included before anything else
**/
include TMPL_DIR . '/includes/rm-functions.php';
/**
 * Recurses through the 'includes' folder and auto includes files that match the regex '.inc.php'
 * recurse function is in rm-functions.php
 **/
rmFunctionRecurse( TMPL_DIR . '/includes/' );

/**
 * Register JavaScript and CSS files with WP
 **/
function rm_load_javascript_files() {
	// JS
	wp_deregister_script('jquery');
	
	wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js", false, "2.1.3", true);
	wp_register_script( 'rm_modernizr', TMPL_DIR_URI . '/js/libs/modernizr.min.js', false, '2.8.3', false );
	wp_register_script( 'rm_fancybox', TMPL_DIR_URI . '/js/libs/fancybox2/jquery.fancybox.js', array('jquery'), '2', true );
	wp_register_style( 'css_rm_fancybox', TMPL_DIR_URI . '/js/libs/fancybox2/jquery.fancybox.css' , null , null , 'all' );
	wp_register_script( 'rm_cycle', TMPL_DIR_URI . '/js/libs/jquery.cycle2.js', array('jquery'), '2.88', true );
	wp_register_script( 'rm_carousel', TMPL_DIR_URI . '/js/libs/jquery.cycle2.carousel.js', array('jquery'), '2.88', true );
	wp_register_script( 'rm_harvey', TMPL_DIR_URI . '/js/libs/harvey.min.js', array('jquery'), '1', true );
	wp_register_script( 'rm_scripts', TMPL_DIR_URI . '/js/scripts.js', array('jquery','rm_modernizr'), '1.0', true );

	$data_array = rm_data_array();
	wp_localize_script( $handle = 'rm_scripts', $object_name = 'rm_data', $l10n = $data_array ); //found in rm-functions.php
	wp_enqueue_script( 'rm_js_data' );

	wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'rm_modernizr');
	wp_enqueue_script( 'rm_fancybox');
	wp_enqueue_style( 'css_rm_fancybox');
	//wp_enqueue_script( 'rm_cycle');
	//wp_enqueue_script( 'rm_carousel');
	//wp_enqueue_script( 'rm_harvey');
	wp_enqueue_script( 'rm_scripts');

}
add_action( 'wp_enqueue_scripts', 'rm_load_javascript_files' );

/**
* YOUR COUSTOM FUNCTIONS GO HERE  :) 
**/
