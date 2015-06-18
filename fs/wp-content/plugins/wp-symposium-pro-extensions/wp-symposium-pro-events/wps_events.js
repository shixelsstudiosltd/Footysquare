jQuery(document).ready(function() {

	jQuery('#wps_event_start').datetimepicker({
		format:'Y-m-d',
		timepicker:false,
		closeOnDateSelect:true
	});

	jQuery('#wps_event_start_time').datetimepicker({
		format:'H:i',
		datepicker:false
	});

	jQuery('#wps_event_end').datetimepicker({
		format:'Y-m-d',
		timepicker:false,
		closeOnDateSelect:true		
	});

	jQuery('#wps_event_end_time').datetimepicker({
		format:'H:i',
		datepicker:false
	});

});
