jQuery( document ).ready(function() {
    jQuery('#btn_pswp').click(function() {
        jQuery('#gifimg').css('visibility','visible');
        jQuery('#loader_gifimg').css('display','inline');
        jQuery('#btn_pswp').css('margin-right','0px');
		

        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                'action': 'paypal_scan_action'
            },
            dataType: "html",

            success: function(data) {

                jQuery('#paypal_scan_response').html(data);
                jQuery('#loader_gifimg').css('display','none');


            },
        });



    });

    var pluginurl = paypal_security_plugin_url.plugin_url;
	
    jQuery( "#dt_start_from" ).datepicker({
        showOn:"button",
        buttonImage: pluginurl+'/partials/images/calendar.gif',
        buttonImageOnly: true
    });
    jQuery( "#dt_to" ).datepicker({
        showOn:"button",
        buttonImage: pluginurl+'/partials/images/calendar.gif',
        buttonImageOnly: true
    });
     
    jQuery(".cls_dialog_source").dialog({
        autoOpen: false
    });
    
    jQuery( ".cls_dialog" ).click(function() {
        //alert('test');
        });
  

});