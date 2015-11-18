<?php  
/**
 * Video Shortcode
 * Displays HTML5 video for use in the content of posts and pages
 *
 */
function video( $atts = null , $content = null ){
			/**
			 * Video Shortcode
			 * Displays HTML5 video for use in the content of posts and pages
			 *
			 */
			// VIDEO MULTIPLAYER
			/*
			Available options for shortcode
			[video file="" autoplay="" height="" width="" wrap_class="" wrap="" float="" popup=""]
					autoplay IF yes == autoplay 
					autoplay IF no or empty == [empty]
					
					param > wrap 
						yes = wraps in a div with default classes 
						no  = no div wrap, just video html

					functionality to add ??
			*/
			//extract & set defaults(?)
			// should set defaults (?)
			extract( shortcode_atts( array(
			'file'		    =>	'',
			'height'	    =>	'auto',
			'width'		    =>	'',
			'thumbwidth'	=> '225px',
			'thumbheight'	=> '',
			'autoplay'    	=>	'no',
			'caption'		=> '',
			'wrap'        	=> 'yes',
			'wrap_class'  	=> '',
			'thumbalign'	=> '',
			'vid_id'		=> '',
			'img_override'	=> '',
			'video_type'	=> 'html5',
			'popup'			=> false,
			'linkclass' 	=> '',
			), $atts ) );

			$popup = filter_var($popup, FILTER_VALIDATE_BOOLEAN);
			
			$upload_dir = wp_upload_dir();////
			
			$upload_path = $upload_dir['baseurl'];
			$upload_path =  $upload_path.'/video/';//
			$location = $upload_path;
			
			$name = $file;
			$file = $location . $file;
			$autoplay = ( $autoplay == 'yes' || $autoplay == 'true') ? 'autoplay' : '';
			$front = ( is_front_page() ) ? ' front' : '';
			$poster = (!empty($img_override)) ? $location .$img_override : $file;
			

			$wrap_class_default = array('rm-video');

			$wrap_class_default[] = $wrap_class;

			$wrap_class = implode(' ', $wrap_class_default);

			ob_start();
			if( $popup == false || empty($popup) ):
				$video = ( $wrap == 'yes' ) ? '<div class="'. $wrap_class . $front . '">' : '';
				$video .= '<video class="html5vid" id="'.$vid_id.'" width="' . $width . '" height="' . $height . '" poster="' . $poster . '.jpg" controls preload="none" '.$autoplay.'>';
				$video .= '<source src="' . $file . '.mp4" type="video/mp4" />';
				$video .= '<source src="' . $file . '.ogg" type="video/ogg" />';
				$video .= '<object id="' . $name .'" name="' . $name .'" width="' . $width . '" height="' . $height . '" type="application/x-shockwave-flash" data="' . $location . 'player.swf?image=' . $file . '.jpg&amp;file=' . $file . '.mp4">';
				$video .= '<param name="allowScriptAccess" value="always" />';
				$video .= '<param name="wmode" value="transparent">';
				$video .= '<param name="movie" value="' . $location . 'player.swf?image=' . $file . '.jpg&amp;file=' . $file . '.mp4" />';
				$file = (!empty($img_override)) ? $location .$img_override : $file;
				$video .= '<img src="' . $file . '.jpg" width="' . $width . '" height="' . $height . '" alt="' . $file . '" title="No video playback capabilities, please download the video below" />';
				$video .= '</object>';
				$video .= '</video>';
				$video .= ( $wrap == 'yes') ? '</div>' : '';
				echo $video;
			elseif( $popup == true ):

				$data_array = array( 'ajaxurl' => admin_url('admin-ajax.php') );
				wp_localize_script( $handle = 'rmv-popupvideo-js', $object_name = 'rmvData', $l10n = $data_array );
				
				//wp_enqueue_script( "rmv-popupvideo-js" );
				
				/**
				 * data-video-type : html5 , youtube
				 */
				$poster = $poster . '.jpg';
				
				$upload_path = $upload_dir['basedir'];
				$upload_path =  $upload_path.'/video/';
				
				$poster_path =  $upload_path.$name.'.jpg';
				
				if(file_exists($poster_path)):
					$postersize = getimagesize($poster_path);
					$caption = (!empty($caption)) ? "<span style=\"display:block;\">{$caption}</span>": '' ;
					$_style = ( empty($content) ) ? 'display:inline-block;width:'. $thumbwidth : '';
					?>
						<a style="<?php echo $_style;?>"
						class="popup--video <?php echo $linkclass;?> <?php echo $thumbalign;?>" 
						href="#" 
						data-video-height="<?php echo (!empty($height)) ? $height : $postersize[0]  ;?>" 
						data-video-width="<?php echo (!empty($width)) ? $width : $postersize[1]  ;?>" 
						data-video-wrap="<?php echo $wrap;?>"
						data-video-type="<?php echo $video_type;?>" 
						data-video-file="<?php echo $name;?>"
						>
						<?php if(empty($content)): ?>
						<img class="" width="100%" height="<?php echo ( !empty($thumbheight) ) ?  $thumbheight : '' ;?>" src="<?php echo $poster;?>" alt=""><?php echo $caption;?>
						<?php else: ?>
							<?php echo $content; ?>
						<?php endif; ?>
						</a>
					<?php
				endif;
			endif;
			return ob_get_clean();
}
add_shortcode('video', 'video');
