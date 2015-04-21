jQuery( document ).ready(function() {
    jQuery('#btn_pswp').click(function() {
        jQuery('#gifimg').css('visibility','visible');
        jQuery('#loader_gifimg').css('display','inline');
	
	
        var data = {
            'action': 'paypal_scan_action'
				
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data, function(response) {
			
            jQuery('#paypal_scan_response').html(response);
            jQuery('#loader_gifimg').css('display','none');
		
			
			
        });
    
    });







});
