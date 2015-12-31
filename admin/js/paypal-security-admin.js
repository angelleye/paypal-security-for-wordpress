jQuery(document).ready(function () {
    var progressbar_time = '';
    SyntaxHighlighter.highlight();
    jQuery('#btn_pswp').click(function () {
        jQuery('#btn_pswp').hide();
        jQuery('#gifimg').css('visibility', 'visible');
        jQuery('#loader_gifimg').css('display', 'inline');
        jQuery('#btn_pswp').css('margin-right', '0px');
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                'action': 'paypal_scan_action',
                data: jQuery('form').serialize(),
            },
            dataType: "html",
            success: function (data) {
                if (data) {
                    try {
                        var obj = jQuery.parseJSON(data);
                        var per_post_run_time = '';
                        if (typeof (obj.count) != "undefined" && obj.count !== null) {
                            jQuery('#loader_gifimg').hide();
                            jQuery('#progressbar').show();
                            if(obj.count > 10) {
                                per_post_run_time = 2.9;
                            } else if(obj.count > 20) {
                                per_post_run_time = 2.8;
                            } else if(obj.count > 30) {
                                per_post_run_time = 2.7;
                            } else if(obj.count > 40) {
                                per_post_run_time = 2.6;
                            }  else if(obj.count > 50) {
                                per_post_run_time = 2.5;
                            } else if(obj.count > 60) {
                                per_post_run_time = 2.4;
                            } else if(obj.count > 80) {
                                per_post_run_time = 2.3;
                            } else if(obj.count > 100) {
                                per_post_run_time = 2.2;
                            } else {
                                per_post_run_time = 3;
                            }
                            progressbar_time = (obj.count * per_post_run_time) * 20;
                            jQuery("#progressbar_timeout").val(parseInt(progressbar_time));
                            jQuery("#progressbar").progressbar({
                                value: false,
                                change: function () {
                                    jQuery(".progress-label").text(jQuery("#progressbar").progressbar("value") + "%");
                                },
                                complete: function () {
                                    jQuery(".progress-label").text("Complete!");
                                }
                            });
                            progress();
                            jQuery("#progressbar").progressbar("value", 0);
                            jQuery.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    'action': 'paypal_scan_data',
                                    data: obj,
                                },
                                success: function (data) {
                                    jQuery("#progressbar").progressbar("value", 98);
                                    setTimeout(function () {
                                        jQuery("#progressbar").progressbar("value", 100);
                                    }, 200);
                                    setTimeout(function () {
                                        jQuery("#progressbar").hide();
                                    }, 1000);
                                    jQuery('#paypal_scan_response').html(data);
                                    SyntaxHighlighter.highlight();
                                    var pss_recommendation_data = jQuery(data).find('#pss_recommendation_data').html();
                                    var $result_total_cnt = jQuery(data).find('.div_tbl_total_count').html();
                                    var $site_score = jQuery(data).find('#txt_site_score').val();
                                    var $site_grade = jQuery(data).find('#txt_site_grade').val();
                                    var $clr_code = jQuery(data).find('#txt_clr_code').val();
                                    jQuery(".div_site_score").addClass("cls_site_with_border");
                                    if ($site_grade == 'No buttons found...') {
                                        jQuery('.div_site_score').html('<div class="cls_site_score">' + $site_score + '</div><div class="' + $clr_code + ' cls_site_grade_30">' + $site_grade + '</div>');
                                        jQuery(".div_site_score").removeClass("cls_min196");
                                        jQuery(".div_site_score").addClass("cls_min0");
                                    } else {
                                        jQuery(".div_site_score").removeClass("cls_min0");
                                        jQuery(".div_site_score").addClass("cls_min196");
                                        jQuery('.div_site_score').html('<div class="cls_site_score">' + $site_score + '</div><div class="' + $clr_code + ' cls_site_grade">' + $site_grade + '</div>');
                                    }
                                    jQuery('.div_get_totalscan').html($result_total_cnt);
                                    if(typeof(pss_recommendation_data) != "undefined" && pss_recommendation_data !== null) {
                                        jQuery('#pps_recommendation').html(pss_recommendation_data);
                                        jQuery('#pps_recommendation').show();
                                    } else {
                                        jQuery('#pps_recommendation').html('');
                                        jQuery('#pps_recommendation').hide();
                                    }
                                     setTimeout(function () {
                                         jQuery('#btn_pswp').show();
                                    }, 1000);
                                }
                            });
                        }
                    } catch (e) {
                        alert(e);
                    }
                }
            },
        });
    });

    var $checkboxes = jQuery('#frm_scan input[type="checkbox"]');
    $checkboxes.change(function () {
        var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
        if (countCheckedCheckboxes == 0) {
            jQuery('#btn_pswp').hide();
            jQuery('#notice').show();
        } else {
            jQuery('#btn_pswp').show();
            jQuery('#notice').hide();
        }
    });

   function progress() {
        var val = jQuery("#progressbar").progressbar("value") || 0;
        jQuery("#progressbar").progressbar("value", val + 2);
        if (val < 88) {
            setTimeout(progress, jQuery("#progressbar_timeout").val());
        }

    }
    var pluginurl = paypal_security_plugin_url.plugin_url;
    var select_all = function (control) {
        jQuery(control).focus().select();
        var copy = $(control).val();
    }
    jQuery(".txt_unsecuresource").click(function () {
        select_all(this);
    })
    
   jQuery("#delete_ps_history").live("click", function () {
        if (!confirm("Are you sure want to delete PayPal security scan history?")) {
            return false;
        } else {
            var data = {
                action: 'pss_delete_paypal_scan_history',
                value: 'yes'
            };
            jQuery.post(ajaxurl, data, function (response) {
                var responseOb = JSON.parse(response);
                if(responseOb.statusmsg == 'success') {
                    window.location.reload();
                        return;
                }
            });
        }
    });
});