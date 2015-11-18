<?get_header()?>

<div class="content">

	<h1><?the_time('F Y')?></h1>
	
	<?if ( have_posts() ) : while ( have_posts() ) : the_post();?>
		<article>
			<h2><a href="<?the_permalink();?>"><?the_title();?></a></h2>
			<p class="meta-data">Posted on <?the_time('F j, Y');?> in <?the_category(', ');?></p>
			<?the_content();?>
		</article>
	<?endwhile; endif;?>
	
</div>

<?get_sidebar()?>

<?get_footer()?>