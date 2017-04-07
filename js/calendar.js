$(document).ready(function() {
			
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

	function publishLink() {
		var data = {"year":"2017", "data":calendarData};
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

