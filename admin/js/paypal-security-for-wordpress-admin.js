jQuery( document ).ready(function() {
	jQuery('#btn_pswp').click(function() {
		jQuery('#gifimg').css('visibility','visible');
		jQuery('#loader_gifimg').css('display','inline');
		jQuery('.progress-label').html('Loading...');
		jQuery('#frm_scan .ui-progressbar').css('background','#fff');
		jQuery('#btn_pswp').css('margin-right','0px');

		var progressbar = jQuery( "#progressbar" ),
		progressLabel = jQuery( ".progress-label" );

		progressbar.progressbar({
			value: false,
			change: function() {
				progressLabel.text( progressbar.progressbar( "value" ) + "%" );
			},
			complete: function() {
				//progressLabel.text( "Complete!" );
			}
		});
		function progress() {
			var val = progressbar.progressbar( "value" ) || 0;

			progressbar.progressbar( "value", val + 3 );
			jQuery('#frm_scan .ui-progressbar').css('background','#fff');
			if ( val < 99 ) {
				setTimeout( progress, 100 );

			}
		}

		setTimeout( progress, 500 );





		jQuery.ajax({
			url: ajaxurl,
			type: "POST",

			data: {'action': 'paypal_scan_action'},
			dataType: "html",

			success: function(data) {
				var val = progressbar.progressbar( "value" );

				if (val <=99){
					var left_val = 99 - val;
					progressbar.progressbar( "value", val + left_val );
				}
				setTimeout(
				function()
				{
					progressLabel.text( "Complete!" );
					
					jQuery('#btn_pswp').css('margin-right','35px');
					jQuery('#paypal_scan_response').html(data);
					jQuery('#loader_gifimg').css('display','none');
					

				}, 1000);
				
				
			},
		});



	});







});
