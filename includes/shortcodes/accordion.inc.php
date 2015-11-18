<?php 
/*
  Accordion Shortcodes
*/
add_shortcode('accordion_set' , 'sc_accordion_set');
function sc_accordion_set( $atts , $content = null ){
	$html = "<div id=\"accordion-set\" class=\"accordion-set clearfix\">" .  do_shortcode( $content ) . "</div>";
return $html;
}

add_shortcode('accordion_tab' , 'sc_accordion_tab');
function sc_accordion_tab( $atts , $content = null ){
	extract( shortcode_atts( array(
		'title'	=> '',
		'id'	=> '',
		'class' => ''
	), $atts , 'accordion_tab' ) );
		$title = ( isset($title) && !empty($title) ) ? $title : 'Title Required' ;
		ob_start();	
		$html = "<div class=\"accordion-tab-block\">";
			$html .= "<div class=\"accordion-tab container-full accordi-tab {$class}\"><h2 id=\"{$id}\" class=\"container-grid accordion-title\"><span>{$title}</span></h2></div>";
			$html .= "<div style=\"display:none;\" class=\"accordion-content container-full\"><div class=\"accordion-contents-inside clearfix container-grid\">". apply_filters( 'the_content', $content) ."</div></div>";
		$html .= "</div>";
		
		echo $html;
		
		$output = ob_get_contents();
		ob_end_clean();
	return $output;
}

function accordion_cleanups($content , $sc_ar = array("accordion_tab" , "accordion_set") ){
	// array of custom shortcodes requiring the fix
	$block = join("|", $sc_ar );
	// opening tag
	$rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);
	// closing tag
	$rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]",$rep); 
	return $rep; 
}
add_filter("the_content", "accordion_cleanups" );

