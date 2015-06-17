jQuery( document ).ready(function() {
    jQuery('#btn_pswp').click(function() {
        jQuery('#gifimg').css('visibility','visible');
        jQuery('#loader_gifimg').css('display','inline');
        jQuery('#btn_pswp').css('margin-right','0px');
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                'action': 'paypal_scan_action',
                data: jQuery('form').serialize(),
            },
            dataType: "html",

            success: function(data) {

                jQuery('#paypal_scan_response').html(data);
                jQuery('#loader_gifimg').css('display','none');
            },
        });
    });
    
 

    var $checkboxes = jQuery('#frm_scan input[type="checkbox"]');
        
    $checkboxes.change(function(){
        var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
        if(countCheckedCheckboxes == 0) {
            jQuery('#btn_pswp').hide();
            jQuery('#notice').show();
        } else {
            jQuery('#btn_pswp').show();
            jQuery('#notice').hide();
        }
    });


    
     
    
    jQuery(document).on('click', ".cls_dialog", function () {
	
   
        var formhtml = jQuery(this).next().html();

        var newWindow = window.open("", "newWindow", "resizable=1,width=500,height=250");
        if(!newWindow.document.closed) {
            newWindow.document.write(formhtml);
        }
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
    
    var select_all = function(control){
       
        jQuery(control).focus().select();
        var copy = $(control).val();
    //window.prompt ("Copy to clipboard: Ctrl+C, Enter", copy);
    }
    jQuery(".txt_unsecuresource").click(function(){
        select_all(this);
    })
  

});