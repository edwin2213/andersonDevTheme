<?get_header()?>

<?if ( have_posts() ) : while ( have_posts() ) : the_post();?>
	<article class="content">
		<h1><?the_title();?></h1>
		<p class="meta-data">Posted on <?the_time('F j, Y');?> in <?the_category(', ');?></p>
		<?the_content();?>

		<?php edit_post_link( $link = __('<< EDIT >>'), $before = "<p>", $after ="</p>", $id ); ?>
	
	</article>
<?endwhile; endif;?>

<?get_sidebar()?>

<?get_footer()?>