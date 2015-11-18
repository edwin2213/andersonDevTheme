<?get_header()?>

<?php if(have_posts()) : while (have_posts()) : the_post();?>
	<article class="content">
		<h1><?the_title();?></h1>
		<?php the_content(); ?>
		
		<?php edit_post_link( $link = __('<< EDIT >>'), $before = "<p>", $after ="</p>", $id ); ?>

	</article>
<?php endwhile; endif;?>

<?get_sidebar()?>

<?get_footer()?>