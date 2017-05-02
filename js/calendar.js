//Js file to save new calendar and publish to production
$(window).on('load', function() {		
	$("#cal").hide();
	$("form").submit(function(event) {
		event.preventDefault();
		var data = {"firstDayOfYear":$("#firstDayOfYear").val(), "isExtraMoon":$("input[name='isExtraMoon']:checked").val(), "removalDays":[], "daysInFirstMonth":$("input[name='daysInFirstMonth']:checked").val()};
		$.ajax({
			method:"POST",
			url: "ParamaterYear/getYear.php",
			data: JSON.stringify(data),
			dataType: 'json',
			success: function(data) {
				calendarData = data;
				init();
				$("#params").hide();
			},
			error: function(status) {
				console.log(status);
			}
		});
	
		// If "publish" is clicked, then confirm button appears, if yes, calls publish function	
		$("#publishLink").click(function(event) {
			$.confirm({
				title: 'Warning!',
				content: 'Are you sure you want to publish this calendar? (Any existing calendar data will be overwritten)',
				buttons: {
					confirm: function() {publishLink();},
					cancel: function() {}
				}
			});
		});		
	});

	// publishes calendar by ajax call to php file
	function publishLink() {
		var year = "" + parseInt(calendarData[1].daysInMonth[1].gregorianDate);
		var data = {"year": year, "data":calendarData};
		$.ajax({
			method:"POST",
			url: "prod/getProdData.php",
			data: JSON.stringify(data),
			dataType: 'json',
			success: function(data) {
				$.alert('Successfully published!');
			},
			error: function(status) {
				$.alert('Something went wrong... Contact IT if issue persists');
			}
		});
	}

});

