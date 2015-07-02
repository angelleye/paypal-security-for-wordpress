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
				
                jQuery('#paypal_scan_response').html(data);
				
                SyntaxHighlighter.highlight();
                jQuery('.fancybox').fancybox();
                var $cls_sitegrade;
                var $result_total_cnt = jQuery(data).find('.div_tbl_total_count').html();
                var $site_score = jQuery(data).find('#txt_site_score').val();
                var $site_grade = jQuery(data).find('#txt_site_grade').val();
                var $clr_code = jQuery(data).find('#txt_clr_code').val();
                jQuery( ".div_site_score" ).addClass( "cls_site_with_border" );
                if ($site_grade == 'No buttons found...') {
                	
                    jQuery('.div_site_score').html('<div class="cls_site_score">'+$site_score+'</div><div class="'+$clr_code+' cls_site_grade_30">'+$site_grade+'</div>');
					jQuery( ".div_site_score" ).removeClass( "cls_min196" );
                    jQuery( ".div_site_score" ).addClass( "cls_min0" );
					
                }else {
                	jQuery( ".div_site_score" ).removeClass( "cls_min0" );
                	jQuery( ".div_site_score" ).addClass( "cls_min196" );
                	
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