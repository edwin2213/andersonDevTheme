<!DOCTYPE html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
 	<meta name="viewport" content=" maximum-scale=1.0, user-scalable=0, width=device-width">

	<title><?php wp_title("");?></title>
	
	<?php wp_head()?>
	
	<?php if( !preg_match('(localhost|rosemontdev)', $_SERVER['SERVER_NAME']) && !is_user_logged_in() ): ?>
		<?php mini_styles( 'style.css' ); ?>
	<?php else: ?>
		<link href="<?php bloginfo( 'template_directory' ); ?>/style.css" rel="stylesheet" />
	<?php endif; ?>
		
	<link rel="icon" href="<?php echo site_url('favicon.ico');?>" type="image/x-icon">
	<link rel="shortcut icon" href="<?php echo site_url('favicon.ico');?>" type="image/x-icon">

</head>
<?php bodyClass(); ?>	