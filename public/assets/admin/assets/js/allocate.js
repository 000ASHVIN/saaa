'use strict';
var Allocate = function() {
	var datePickerHandler = function() {
		$('#date_of_payment').datepicker({
			format:  "d-mm-yyyy",
			autoclose: true,
			todayHighlight: true
		});
	};

	return {
		init: function() {
			datePickerHandler();
		}
	};
}();
