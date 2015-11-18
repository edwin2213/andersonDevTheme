<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<title>Page Not Found</title>
	<?wp_head()?>
	
	<link rel="icon" href="<?bloginfo('url');?>/favicon.png" type="image/x-icon">
	<link rel="shortcut icon" href="<?bloginfo('url');?>/favicon.png" type="image/x-icon">
	
	<style>
		.clear {
			clear:both;
		}
	
		html {
			background: #464545; /* DARK color */
			color: #fff; /* BODY text color */
			font-family: Helvetica, Arial, Verdana, sans-serif;
			font-size: 36px;
			line-height: 1em;
			padding:0;
			margin:0;
		}
		
		body {
			background:none;
			padding:0;
			margin:0;
		}
		
		a {
			color: #fff; /* BODY LINK text color */
		}
		
		h1, h2, h3, h4, h5, p {
			margin: 0 0 40px 0;
			line-height:1em;
		}
		
		#container {
			margin: 0 auto;
			max-width: 600px;
			display:block;
			padding:100px 15px 0 15px;
		}
		
		#error_txt {
			width:100%;
			margin: 0;
		}
		
		#footerwrap {
			border-top: 20px solid #202020;  /* LIGHT color */
			border-bottom: 20px solid #202020;  /* LIGHT color */
			text-align:center;
			width:100%;
			background: #D7D8D4; /* LINKS background color */
		}
		
		footer {
			max-width:600px;
			width: 100%;
			margin: 0 auto;
			color: #000;
			font-size: 14px;
			line-height:1em;
		}
		
		footer a {
			color: #000;
		}
		
		#menu-main li ul  { display: none; }
		
		footer ul {
			display:block;
			border-top: 1px solid #464545; /* DARK color */
			text-align: left;
			padding:0;
			margin: 20px 15px;
		}
		
		footer ul li {
			display:block;
			height:auto;
			border-bottom: 1px solid #464545; /* DARK color */
			list-style: none;
			font-size:26px;
			line-height:1em;
		}
		
		footer ul li a {
			display: block;
			padding: 10px 5px;
			height:auto;
			text-decoration: none;
		}
		
		footer ul li a:hover {
			background: #464545;  /* DARK color */
			color: #fff; /* LINKS text hover color */
		}
		
		.copy {
			font-size: 14px;
			line-height:1.2em;
			text-align: center;
			margin: 40px auto;
		}
		
	</style>
</head>
<body>
	<div id="container">
		<div id="error_txt">
			<h2>Page Not Found</h2>
			<p>We apologize for the inconvenience, but it seems that you’ve stumbled upon a page that doesn’t exist here.</p>
		</div>
	</div>
	<div id="footerwrap">
		<footer>
			<?php wp_nav_menu(array('menu' => 'Main'));?>
			<div class="clear"></div>
		</footer>
	</div>
	<p class="copy">Copyright &copy; <?=date("Y")?> <?bloginfo('name');?><br/>
		Website Developed by <a href="http://www.rosemontmedia.com/" target="_blank">Rosemont Media</a></p>
</body>
</html>