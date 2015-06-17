jQuery(document).ready(function() {

    jQuery("#wps_check_all_extensions").click(function (event) {
        jQuery('.wps_extension_checkbox').prop('checked', true);
    });

    jQuery("#wps_uncheck_all_extensions").click(function (event) {
        jQuery('.wps_extension_checkbox').prop('checked', false);
    });

});

