<?php
/**
*	Function to recursively include all files in a director
**/
function rmFunctionRecurse( $dir = '' ){
	$directory = new RecursiveDirectoryIterator( $dir );
	$iterator = new RecursiveIteratorIterator($directory);
	$Regex = new RegexIterator($iterator, "/\.inc\.php|\.custom\.php/i", RecursiveRegexIterator::MATCH);
	foreach( $Regex as $filepath => $object ):
		include $filepath;
	endforeach;
}
	
/**
 * Register template URL and use in js files
 * Use like: background-image: url('+rm_data.templateDirectoryUri+'/images/dd-meet-our-team.jpg)
 */
function rm_data_array(){
	$theme = wp_get_theme();
	$data = array( 
		'siteUrl' => rtrim( site_url() , '/') ,
		'themeName'	=> $theme['Template'] ,
		'tmplDirUri' => TMPL_DIR_URI,
	);	
	return $data;
}

/**
*	can be used in template parts to include other files within the theme
**/
function get_file_from_theme($file = ''){
	if(file_exists(dirname(__FILE__) .'/' .$file)){
		return dirname(__FILE__) .'/' .$file;
	}
}

/**
 * Allows SVG through WordPress media uploader
 */
function cc_mime_types( $mimes ){
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}

add_filter( 'upload_mimes', 'cc_mime_types' );

/**
 * Register new Navigation menu
 */	
function register_my_menus() {
  register_nav_menus(
    array( 'header-menu' => __( 'Header Menu' ) )
  );
}
add_action( 'init', 'register_my_menus' );



/*=--------- MINI STYLES STARTS HERE ---------=*/
function css_strip_whitespace($css , $googlefonts = false ){
	# Thanks to
	//https://www.progclub.org/blog/2012/01/10/compressing-css-in-php-no-comments-or-whitespace/

	/* @ RM */ 
	if($googlefonts == false):
		# svg folder will always be inside of images
		# images/svg/file.svg
		$css = preg_replace('/images\//i', TMPL_DIR_URI . '/images/', $css); 
		
	endif;
	/* remove comments */
	$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
	/* remove tabs, spaces, newlines, etc. */
	$css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);

  return trim($css);
}

function make_mini_file( $file = null , $is_google = false ){
	
	if(empty($file)) return false;
	
	$upload_dir = wp_upload_dir(); 
	$baseupload_dir = $upload_dir['basedir'];

	$minifile = $file;
	$defaultfile = $file;
	$minifilecontents = '';

	$minicreated = false;
	
	if( preg_match('/\//', $file ) ):
		$file = ltrim( $file , '/');
		if( preg_match('/\//', $file ) ):
			$_file = preg_replace('/\//', '%', $file);
			$minifile = $_file;
		endif;
	endif;

	
	if( !file_exists($baseupload_dir . '/mini/css/') && is_writable($baseupload_dir) ):
		mkdir($baseupload_dir . '/mini/css/' , 0775 , true );	
	elseif(!is_writable($baseupload_dir)):
		return false;
	endif;
	

	$original_file = TMPL_DIR . "/{$file}";
	$mini_file = $baseupload_dir . "/mini/css/{$minifile}";

	if( !file_exists($baseupload_dir . "/mini/css/{$minifile}") || filemtime($original_file) > filemtime($mini_file) ):
		#create/open file for writing 
		$filetowrite = fopen($baseupload_dir . "/mini/css/{$minifile}", "w");
		
		// original file
		$themefile = TMPL_DIR . "/{$file}";
		// get file contents from template
		$default_file_contents = file_get_contents( $themefile );
		// minify contents
		$default_file_contents = css_strip_whitespace( $default_file_contents , $is_google );
		// write to file
		fwrite( $filetowrite , $default_file_contents );
		// close file
		fclose( $filetowrite );
		$minicreated = true;
	else:
		 $minicreated = true;	
	endif;
	
	if( $minicreated == true && ( filemtime($original_file) < filemtime($mini_file) ) ):
		
		$getfile = $baseupload_dir . "/mini/css/{$minifile}";
		
		$time = date ("F d Y", filemtime($getfile) );
		
		return "/** {$time} | mini/css **/ " . file_get_contents( $getfile );
	else:
		return false;
	endif;

}

/**
*	replaces : <link rel="stylesheet" href="<?bloginfo('template_directory');?>/style.css" media="screen" />
*	mini_styles('style.css');
**/
function mini_styles( $sheet = '' , $googlefonts = false ){
	$stylesheet = $sheet;
	$family = $sheet;
	$makeminifile = false;
	if($googlefonts == false):
		$sheet = (!empty($sheet)) ? $sheet : 'style.css';
		
		$stylesheet = TMPL_DIR . '/' . $sheet ;
		
		if(!file_exists($stylesheet)): return; endif;

		$makeminifile = make_mini_file( $sheet , $googlefonts );

	else:
		preg_match('/family=(.*)/i', $stylesheet , $matched);
		$family = 'googlefonts : ' . $matched[1];
	endif;


	if( $makeminifile == false ):
		$get_sheet = file_get_contents($stylesheet);
		$css = "/** minified **/ " . css_strip_whitespace( $get_sheet , $is_google );
	else:
		// already minified and serving from uploads/mini/css/
		$css = $makeminifile;
	endif;

	
	echo "<!-- start minified {$family} --><style>{$css}</style><!-- end minified CSS -->";
}
/*=--------- MINI STYLES ENDS HERE ^^ */

/*=========================================================== 
	CLEAN URI / SLUG [!important]
	# used in  : check_url , bodyClass , check_existence
=============================================================*/
function clean_uri(){
	$ex = preg_replace('(^https?://)', '', get_bloginfo('url') ); // removes 'http(s)' from url
	$ex = preg_replace('/www./i' , '' , $ex ); // remove 'www.' from url
	$output = explode('/' , preg_replace( '(^'.$ex.')' , '' , preg_replace( '/www./i' , '' , $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ) ) ); //clean URI / SLUG
	return $output;
}

/*************
* Get file from image/svg folder 
* use like  <?=uri_imgs('name-of-image.extension');?>
* can also be used like <?=uri_imgs('/sub-headers/img.jpg');?>
* -- wrap link in an <img>
*  <?=uri_imgs('name-of-image.extension' , array(true)); ?>
* -- allows svg ( wraps svg in an <img> )
*  example <?=uri_imgs('file.svg' , array(true) , '/svg/');?>
*  example <?=uri_imgs('../svg/file.svg' , array(true) );?>
*************/
function uri_imgs( $file = null , $args = array() , $folder = '/images/' ){
	$path = TMPL_DIR . "{$folder}" . $file;
	$path_uri = TMPL_DIR_URI . "{$folder}" . $file;
	if(file_exists( $path ) ):	
		if( isset($args[0]) && $args[0] == true ):
			$attr = '';
			if( isset( $args['attr'] ) && is_array( $args['attr'] )):		
				$attr = array();
				foreach( $args['attr'] as $key => $val):
					$attr[] = "{$key}=\"{$val}\"";
				endforeach;
				$attr = join(' ', $attr );
			endif;
			
			if( preg_match('/images/i' , $folder ) && !preg_match('/svg/i' , $path ) ):
				$size = getimagesize( $path );
				$width = $size[0];
				$height = $size[1];
				$tag = "<img src=\"{$path_uri}\" width=\"{$width}\" height=\"{$height}\" {$attr}>";
			else:
				if( preg_match('/svg/i' , $path ) ):
					$svg_contents = file_get_contents($path);
					$svg = simplexml_load_string($svg_contents);
					$svgattrs = $svg->attributes();
					$_w = (string) $svgattrs->width;
					$_h = (string) $svgattrs->height;
					if( !empty($_h) && !empty($_w)):
						$tag = "<img src=\"{$path_uri}\"  width=\"{$_w}\" height=\"{$_h}\" {$attr}>";
						return $tag;
					endif;
				endif;	

				$tag = "<img src=\"{$path_uri}\"  {$attr}>";
			endif;	
			return $tag;
		endif;
	 return $path_uri;
	else:
		return null;
	endif;
}


/*************
* Get URL path
* use like  <?=url_path();?>
*************/
function url_path(){
	$path = get_bloginfo('url');
	return $path;
}

/******************
*
* Add 'last' class to last item in 1st level navigation & 'first' to first item in 1st level
*
******************/
add_filter('wp_nav_menu_objects', function($items){
	$items[1]->classes[] = 'first';
    $items[count($items)]->classes[] = 'last';
    return $items;
});

/******************************* 
Checks to see if a slug is at a certain segment of the URL
check_url update [ruben]  : USES clean_uri()  
  #NOTE
    Doesn't require a different index for LIVE / DEV (?)
    -- need to test this on the live to see how it works
    -- working on Live & Dev
*******************************/
function check_url($uri = '', $position = ''){
  $current = array_values( array_filter( clean_uri() ) ); // remove empty arrays at the beginning and end [removes any array that is empty] , then reset the index [0]
  return ( isset($current[$position]) && $current[$position] == $uri ) ? true : false ;
} 

/**
 * Check the existence of a particular URL segment
 *
 */
function check_existence($position = '') {
	$current = array_values( array_filter( clean_uri() ) ); // remove empty arrays at the beginning and end [removes any array that is empty] , then reset the index [0]	
	if( isset($current[$position]) && $current[$position] != ''):
		return true;
	endif;
}

/*****************
for gallery pages :  
page-with-related : checks is parent page has children , checks if child has related pages from parent
page-with-child : checks if page has children
*****************/
function this_is($type = '', $val = ''){
  global $post;
  switch ($type) {
    case 'gallery':
      $output = ( check_url('gallery' , 0) ) ? true : false ;
    break;    
    
    case 'gallery-child':
      $output = ( check_url('gallery' , 0) && check_existence(1) ) ? true : false;
    break;
    
    case 'gallery-case':
      $output = ( check_url('gallery' , 0) && check_existence(2) ) ? true : false;
    break;

    case 'page-with-child':
      $children = get_pages( array(
          'child_of'  =>  (isset($val))  ? $val : $post->ID
        ));
      $output = ( !empty($children) ) ? true : false;
    break;
    
    case 'page-with-related':
      $children = get_pages( array(
          'child_of'  =>  (isset($val))  ? $val : ( $post->post_parent ) ? $post->post_parent : $post->ID
        ));
      $output = ( !empty($children) ) ? true : false;
    break;

  }
  return $output;
}


/********************
* Add the slug segments as body classes on inside pages
* last - modified [ruben]
* #note : tested on dev  = Working
* #note : tested on live = Working
* no longer including the ID option
*********************/
function bodyClass( $active_home_id = '' , $home_name = '' , $new_classes = '' , $override = FALSE ) {
	$current = array_values( array_filter( clean_uri() ) ); // remove empty arrays at the beginning and end [removes any array that is empty] , then reset the index [0]
	$home_name =  ( !empty($home_name) ) ? $home_name : 'home';
	global $post;
	$parent_page = ( $post->post_parent ) ? $post->post_parent : $post->ID;

	$classes = array();
	$classes[] = (is_front_page()) ? $home_name : 'inside';

	foreach($current as $slug):
	$classes[] = ($slug != '') ? $slug : '';
	endforeach;
	if( is_page() || is_single() ): $classes[] = get_post_type().'-'.get_the_ID(); endif;
	/* this can also be used for inside page headers css vs php */
	if($parent_page): $classes[] = 'parent-'.$parent_page; endif;
	if( is_404() ): $classes[] = 'page-404'; endif;
	if( get_post_type() == 'post'): $classes[] = 'post from-blog'; else: /*to style everything else but the blog*/ $classes[] = 'not-blog'; endif;
	if( this_is('gallery')): $classes[] = 'rmgallery'; else: /* to style everything else but the rmgallery */ $classes[] = 'not-rmgallery'; endif;
	if( this_is('gallery-child')): $classes[] = 'rmgallery-child'; endif;
	if( is_page() ): $classes[] = 'is-page'; endif;
	global $template,$post; 
	$templateType = basename($template , ".php");
	$templateType = 'tmpl_type_' . preg_replace('/(\.|_|-)/i', '_' , $templateType);
	$classes[] = $templateType;
	/*
	for if whatever reason you wanted to include a class / classes from within your own function
	example
	function new_body_classes(){
		// YOUR SUPER SPECIAL CODE HERE
		ob_start();
			echo $class;
		return ob_get_clean();
	}
	bodyClass($active_home_id , $home_name  , $extra_class = 'new_body_classes');

	*/
	if($override == TRUE ){
		$classes = ( is_callable($new_classes) ) ? call_user_func($new_classes , $classes) : '' ;
	}
	else{
		$classes[] = ( is_callable($new_classes) ) ? call_user_func($new_classes , $classes) : '' ; // testing this out
	}


  echo '<body class="'.join(' ' , $classes).'">';
} /* end */

/**
 * Find if a particular page is part of the blog
 *
 */
function is_blog () {
	global  $post;
	$posttype = get_post_type($post );
	return ( ((is_archive()) || (is_author()) || (is_category()) || (is_home()) || (is_single()) || (is_tag())) && ( $posttype == 'post')  ) ? true : false ;
}

/**
 * the_content() replacement that strips everything but the text
 *
 */
function content($num = '') {
	$theContent = get_the_content();
	$output = preg_replace('/<img[^>]+./','', $theContent);
	$output = preg_replace( '/<blockquote>.*<\/blockquote>/', '', $output );
	$output = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $output );
	$limit = $num+1;
	$content = explode(' ', $output, $limit);
	array_pop($content);
	$content = implode(" ",$content)."...";
	echo $content;
}

/**
 * Video Shortcode
 * Displays HTML5 video for use in the content of posts and pages
 * now part of 'includes/shortcodes'
 */


/**
 * Add thumbnail functionality
 *
 */
add_theme_support('post-thumbnails');

/**
 * Hide the admin bar when logged in
 *
 */
show_admin_bar(false);

/**
 * URL Shortcode
 * Allows the use of [url] shortcode inside the content instead of writing out the entire URL, similar to bloginfo('url') in template files. 
 * Once saved, this will display the proper URL the next time the content is accessed in WP for editing
 * Great for use in a development-to-production environment
 *
 */
function blogURL() {
	$href = get_bloginfo( 'url' );
	return $href;
}	

add_shortcode( 'url', 'blogURL' );

//Replace the "[url]" shortcode with the working URL in the editor
function replaceURL_Shortcode($content) {
	$href = get_bloginfo( 'url' );
	$content = str_ireplace('[url]',$href,$content);
	return $content;
}

add_filter( 'the_editor_content', 'replaceURL_Shortcode' );

//Translate the working URL into the "[url]" shortcode to be saved in the DB
function insert_Shortcode($content) {
	$href = site_url();
	$content = str_ireplace($href, '[url]', $content);
	return $content;
}

add_filter( 'content_save_pre', 'insert_Shortcode' );

/**
 * Customize the admin footer text
 *
 */
function custom_admin_footer() {
	echo 'Website Design by <a href="http://www.rosemontmedia.com/" target="_blank">Rosemont Media</a>';
} 
add_filter('admin_footer_text', 'custom_admin_footer');

/**
 * Link and feed clean-up
 * TO-DO: Verify need for each
 *
 */
remove_action('wp_head', 'rsd_salink');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

/*404 EXCLUDE UPDATES WP LIST PAGES WITH SQL ID ARRAY BY TITLE RATHER THAN USING EXCLUDE=1,2,3...*/
add_filter('wp_list_pages_excludes','page_filter');
function page_filter($exclude_array) {
	global $wpdb;
	$table = "wp_posts";
	$sql = "SELECT ID FROM ".$table." WHERE post_title ='Responder' OR post_title LIKE '%Landing%' OR post_title LIKE '%Review%' OR post_title LIKE '%giveaway%' OR post_title LIKE '%Contest%' OR post_title LIKE '%facebook%' OR post_title LIKE '%reviews%' OR post_title LIKE '%phone%'";
	$id_array = $wpdb->get_col($sql);
	$exclude_array=array_merge($id_array, $exclude_array);
	return $exclude_array;
}

/**
 * Custom excerpt length
 *
 */
function custom_excerpt_length($length) {
	return 20;
}
add_filter('excerpt_length', 'custom_excerpt_length');

/**
 * Custom excerpt limit
 * Echo like - <?=excerpt(120);?>
 *
 */
function excerpt( $limit ) {
	
	$excerpt = explode( ' ', get_the_excerpt(), $limit );
 	if ( count( $excerpt )>=$limit) {
		array_pop( $excerpt );
		$excerpt = implode(" ", $excerpt ).'...';
	} else {
		$excerpt = implode( " ", $excerpt ). '... <br/>' . '<a class="button" href="'. get_permalink() .'">Read More</a>';
	}
	$excerpt = preg_replace( '`\[[^\]]*\]`', '', $excerpt );
	
	return $excerpt;
}

/**
 * Use like: [bloginfo key='url']
 */

function digwp_bloginfo_shortcode( $atts ) {
	extract(shortcode_atts(array(
		'key' => '',
	), $atts));
	return get_bloginfo($key);
}

add_shortcode('bloginfo', 'digwp_bloginfo_shortcode');

/**
 * Custom admin login logo
 *
 */
function custom_login_logo() {
	echo '<style type="text/css">
	h1 a { background-image: url('.get_bloginfo('template_directory').'/custom-login-logo.png) !important; height:116px!important;}
	</style>';
}
add_action('login_head', 'custom_login_logo');
	
/*
	Custom Function that modifies the standard previous/next link for single/index templates
	tested on : index.php , single.php
*/
function prevnext__modify( $a , $attr = '' , $before = null , $after = null ){
	if(empty($a)) return;	
	$_attr = '';
	if(!empty( $attr ) && is_array($attr)):		
		foreach($attr as $key => $val):
			$_attr[] = "{$key}=\"{$val}\"";
		endforeach;
		$_attr = join(' ', $_attr );
	endif;
	if(!empty($before)):
		$a = preg_replace('/>(.*)</i', ">{$before} $1<" , $a );
	endif;
	if(!empty($after)):
		$a = preg_replace('/>(.*)</i', ">$1 {$after}<" , $a );
	endif;
	echo preg_replace('/(<a href="(.*)")/i', '$1 '  . $_attr , $a );
}


/// USE LIKE :
////////////////////////* index.php */

// prevnext__modify( get_previous_posts_link() , 
// 	$attributes = array(
// 		'class' => 'button alignleft',
// 	));

// prevnext__modify( get_next_posts_link() , 
// 	$attributes = array(
// 		'class' => 'button alignright',
//	));

////////////////////// /* SINGLE.php */

// prevnext__modify( get_previous_post_link( $format = "<span class=\"alignleft\">&laquo; %link</span>") , 
// 	$attributes = array(
// 		'class' => 'button',
// 	));

// prevnext__modify( get_next_post_link( $format = "<span class=\"alignright\">%link &raquo;</span>" ) , 
// 	$attributes = array(
// 		'class' => 'button',
// 	));


/**
*
* Convert an object to an array
*
* @param     object  $object The object to convert
* @reeturn   array
* @url       http://www.phpro.org/examples/Convert-Object-To-Array-With-PHP.html
*
*/

function objectToArray( $object ) {
    if( !is_object( $object ) && !is_array( $object ) ) {return $object; }
    if( is_object( $object ) ) { $object = get_object_vars( $object ); }
    return array_map( 'objectToArray', $object );
}


/**
* FILTERING : wraps ® , ™ , © in <sup></sup>
* the_excerpt , the_title , wp_list_pages , the_content
*/
function wpFilterMarks($data){
	$data = preg_replace('/(®|&reg;|™|&trade;|©|&copy;)/i', "<sup>$1</sup>", $data);
	return $data;
}
// to prevent double wrapping
function wpFilterMarksTitle($data){
	if(!preg_match('/<sup>(®|&reg;|™|&trade;|©|&copy;)/i', $data)){
		$data = preg_replace('/(®|&reg;|™|&trade;|©|&copy;)/i', "<sup>$1</sup>", $data);
	}
	return $data;
}

if(!is_admin()):
	add_filter('wp_list_pages', 'wpFilterMarksTitle');
	add_filter('the_title', 'wpFilterMarksTitle');
	add_filter('the_excerpt', 'wpFilterMarks');
	add_filter('the_content', 'wpFilterMarks');
endif;



