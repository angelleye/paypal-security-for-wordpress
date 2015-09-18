jQuery(document).ready(function () {
    var progressbar_time = '';
    jQuery('#btn_pswp').click(function () {
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
                                per_post_run_time = 2.8;
                            } else if(obj.count > 20) {
                                per_post_run_time = 2.7;
                            } else if(obj.count > 30) {
                                per_post_run_time = 2.6;
                            } else if(obj.count > 40) {
                                per_post_run_time = 2.5;
                            }  else if(obj.count > 50) {
                                per_post_run_time = 2.6;
                            } else if(obj.count > 60) {
                                per_post_run_time = 2.5;
                            } else if(obj.count > 80) {
                                per_post_run_time = 2.3;
                            } else if(obj.count > 100) {
                                per_post_run_time = 2.2;
                            }
                            progressbar_time = (per_post_run_time * 3) * 20;
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
                                    jQuery('.fancybox').fancybox();
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

    jQuery(document).on('click', ".cls_dialog", function (url) {
    });



    function progress() {
        var val = jQuery("#progressbar").progressbar("value") || 0;
        jQuery("#progressbar").progressbar("value", val + 2);
        if (val < 88) {
            setTimeout(progress, jQuery("#progressbar_timeout").val());
        }

    }
    var pluginurl = paypal_security_plugin_url.plugin_url;
    jQuery("#dt_start_from").datepicker({
        showOn: "button",
        buttonImage: pluginurl + '/partials/images/calendar.gif',
        buttonImageOnly: true
    });
    jQuery("#dt_to").datepicker({
        showOn: "button",
        buttonImage: pluginurl + '/partials/images/calendar.gif',
        buttonImageOnly: true
    });
    jQuery(".cls_dialog_source").dialog({
        autoOpen: false
    });
    var select_all = function (control) {
        jQuery(control).focus().select();
        var copy = $(control).val();
    }
    jQuery(".txt_unsecuresource").click(function () {
        select_all(this);
    })
});