(function($) {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		$('html').addClass('is--device');
		if(/iPad/i.test(navigator.userAgent)){
			$('html').addClass('is--ipad');
		} 	
	}
	else{
		$('html').addClass('not--device');
	}

	$(function() {//doc.ready[shorthand] start

		$("a.fancybox").fancybox({
			'titlePosition'  : 'inside'
		});

	});// end of doc.ready
})(jQuery);