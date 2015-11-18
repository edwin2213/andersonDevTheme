<?php

class rm_theme_options{
	public function __construct(){
		if( function_exists('acf_add_options_sub_page') )
		{
		    acf_add_options_sub_page(array(
		        'title' => 'Theme Options',
		        'parent' => 'themes.php',
		        'capability' => 'manage_options'
		    ));
		    
		}
	}
}
$rm_theme_options = new rm_theme_options();

function acf_get_theme_option( $val = null ){
	$theme_options_rebuild = array();
	if(!function_exists('get_field')) return;
	$theme_options =  get_field('options', 'option');
	if(empty($theme_options)) return;
	foreach($theme_options as $key => $array):

		/* BUILD options array */
		switch ($array['acf_fc_layout']):
			case 'social':
				$theme_options_rebuild['social'][$array['social_type']] = $array['social_link'];
			break;
			case 'phone':
				$theme_options_rebuild['phone'][$array['unique_key']] = $array['phone'];
			break;
			// case 'address':
			// 	$theme_options_rebuild['address'][$array['unique_key']] = $array['address'];
			// break;
			default:
				$default = $array['acf_fc_layout'];
				$unique_key = $array['unique_key'];
				unset($array['acf_fc_layout']);
				unset($array['unique_key']);
				$theme_options_rebuild[$default][$unique_key] = $array;
			break;
		endswitch;

	endforeach;

	if(!empty($val)):
		$val = preg_replace('/\s+/', '', $val);
		$val = explode('/', $val);
		$val = array_filter($val);

		/*
		* Sort through options
		*/
		foreach($val as $key):
			if(array_key_exists($key, $theme_options_rebuild)):
				$theme_options_rebuild = $theme_options_rebuild[$key];
			else:
				return 'sorry, that option was not available';
			endif;

		endforeach;
			return $theme_options_rebuild;
	endif;
}

function acf__getopt( $atts = null ) {
	extract( shortcode_atts( array( 'opt'=>'' ),$atts ) );
	ob_start();

		if(!empty($opt)):
			echo acf_get_theme_option($opt);
		endif;
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
add_shortcode( 'acf_getopt','acf__getopt' );