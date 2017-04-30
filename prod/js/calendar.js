$(document).ready(function() {
	// Gets calendar data from php file to start page load
 	// Gets current date and moves calendar to current date		
	console.log("Attempting to furnish data...");
	$.ajax({
		method:"GET",
		url: "getProdData.php",
		dataType: 'json',
		success: function(data) {
			if (data.status === undefined || data.status != "OK") {
				$("#cal").html("Something went wrong...");
			} else {
				console.log("Success");
				calendarData = data.result.data;
				$("#subHeading").html(data.result.year + " myaamia kiilhsooki");
				monthIndex = calendarData['curMonthIndex'];
				console.log(data);
				$("#firstDayOfYear").val(calendarData[1].daysInMonth[1].gregorianDate);
				init();
			}
		},
		error: function(status) {
			console.log("Failure");
			console.log(status);
			$("#cal").html("Something went wrong...");
		}
	});
});

