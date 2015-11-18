		
			
	<p>Copyright &copy; <?=date("Y")?> <?bloginfo('title');?></p>
				
	<p class="rm-sig"><a href="http://www.rosemontmedia.com/products/website-design-services/medical-specialty" target="_blank">Medical Website Design</a> by <a href="http://www.rosemontmedia.com/" target="_blank"><!-- <img src="<?bloginfo('template_directory');?>/images/rm-sig.png" alt="Rosemont Media"/> --></a></p>

	
	<!-- Enqueued JavaScript at the bottom for fast page loading -->
	<?wp_footer();?>
	
	<?php if( preg_match('(localhost|rosemontdev)', $_SERVER['SERVER_NAME']) ): ?>
		<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js"></' + 'script>')</script>
	<?php endif; ?>
	
</body>
</html>