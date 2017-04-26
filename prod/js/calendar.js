$(document).ready(function() {
			
	console.log("Attempting to furnish data...");
	$.ajax({
		method:"GET",
		url: "getProdData.php",
		dataType: 'json',
		success: function(data) {
			console.log("Success");
			calendarData = data.result.data;
			$("#subHeading").html(data.result.year + " myaamia kiilhsooki");
			monthIndex = calendarData['curMonthIndex'];
			console.log(data);
			$("#firstDayOfYear").val(calendarData[1].daysInMonth[1].gregorianDate);
			init();
		},
		error: function(status) {
			console.log("Failure");
		}
	});
});

