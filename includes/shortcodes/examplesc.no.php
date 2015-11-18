<?php  
add_shortcode('examplesc' , 'sc__examplesc');
function sc__examplesc( $atts , $content = null ){
	extract( shortcode_atts( array(
		'attr'	=> '',

	), $atts , 'examplesc' ) );
		ob_start();	

		
		$output = ob_get_contents();
		ob_end_clean();
	return $output;
}
