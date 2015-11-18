<?require( '../../../wp-load.php' );?>
<?
	// Popup
	
	$url = urldecode($_GET['url']);
	
?>
<style>
		
		#notice-body {
			width: 100%;
			height: 100%;
			display:inline-block;
		}
		
		#notice-body-everything {
			max-width: 500px;
			height: auto;
			padding:20px;
			display:inline-block;
		}
		
		#notice-body-everything h2 {
			text-transform: uppercase;
			font-size: 40px;
			margin:20px 0 30px 0;
			color: #ffffff;
		}
		
		#notice-body p {
			margin:0 0 30px 0;
			color: #ffffff;
		}
		
		#notice-body-everything #buttons {
			display: block;
			text-align: center;
			box-shadow: none;
		}

		#notice-body-everything #buttons a{
			box-shadow:1px 1px 1px 0px #333;
		}
	</style>
<div id="notice-body">
	<div id="notice-body-everything">
		<h2>Notice</h2>
		<p>The photo gallery page you have requested may contain nudity. If you are at least 18 years of age and wish to continue, please click the 'OK' button now.</p>
		<div id="buttons"><a href="javascript:parent.jQuery.fancybox.close();" class="popupbtn button">OK</a></div>
		<!-- <div id="buttons"><a href="<?php echo $url;?>" class="popupbtn button">OK</a></div> -->
	</div>
</div>