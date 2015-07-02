jQuery( document ).ready(function() {

    jQuery('#btn_pswp').click(function() {
        jQuery('#gifimg').css('visibility','visible');
        jQuery('#loader_gifimg').css('display','inline');
        jQuery('#btn_pswp').css('margin-right','0px');
        var offlineTable;
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                'action': 'paypal_scan_action',
                data: jQuery('form').serialize(),
            },
            dataType: "html",

            success: function(data) {
            	
            	<link rel='stylesheet' id='paypal-securitytwo-css'  href='http://bmw.dev2.in/wp-content/plugins/paypal-security/admin/css/shCoreDefault.css' type='text/css' media='all' />
				<script type='text/javascript' src='http://bmw.dev2.in/wp-content/plugins/paypal-security/admin/js/shCore.js'></script>
				<script type='text/javascript' src='http://bmw.dev2.in/wp-content/plugins/paypal-security/admin/js/shBrushJScript.js'></script>
				
				SyntaxHighlighter.all();
            
                jQuery('#paypal_scan_response').html(data);
                var $cls_sitegrade;
                var $result_total_cnt = jQuery(data).find('.div_tbl_total_count').html();
                var $site_score = jQuery(data).find('#txt_site_score').val();
                var $site_grade = jQuery(data).find('#txt_site_grade').val();
                var $clr_code = jQuery(data).find('#txt_clr_code').val();
                if ($site_grade == 'No buttons found...') {
                    jQuery('.div_site_score').html('<div class="cls_site_score">'+$site_score+'</div><div class="'+$clr_code+' cls_site_grade_30">'+$site_grade+'</div>');
                	
                    }else {
                    jQuery('.div_site_score').html('<div class="cls_site_score">'+$site_score+'</div><div class="'+$clr_code+' cls_site_grade">'+$site_grade+'</div>');
                }
                jQuery('.div_get_totalscan').html($result_total_cnt);
              	
              	
              	
              	
              	
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



       
     
    
    jQuery(document).on('click', ".cls_dialog", function (url) {
	
   
        var formhtml = jQuery(this).next().next().html();
		var width  = 300;
 var height = 200;
 var left   = (screen.width  - width)/2;
 var top    = (screen.height - height)/2;
 var params = 'width='+width+', height='+height;
 params += ', top='+top+', left='+left;
 params += ', directories=no';
 params += ', location=no';
 params += ', menubar=no';
 params += ', resizable=no';
 params += ', scrollbars=no';
 params += ', status=no';
 params += ', toolbar=no';
        //var newWindow = window.open("", "newWindow", "resizable=1,width=500,height=250");
       var newWindow=window.open("",'windowname5', params);
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