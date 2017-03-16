var calendarData;
$(document).ready(function() {
			
	$("#cal").hide();
	$("form").submit(function(event) {
		event.preventDefault();
		var data = {"firstDayOfYear":$("#firstDayOfYear").val(), "isExtraMoon":$("input[name='isExtraMoon']:checked").val(), "removalDays":[]};
		$.ajax({
			method:"POST",
			url: "/~annekent/capstone/Myaamia-Lunar-Calendar-App/Myaamia-Calender-Static-HTML-Mockup/ParamaterYear/getYear.php",
			data: JSON.stringify(data),
			dataType: 'json',
			success: function(data) {
				console.log(data);
				calendarData = data;
				$("#params").hide();
				generateCalendar(1);
				$("#cal").show();
			},
			error: function(status) {
				console.log(status);
			}
		});
	});
});

function generateCalendar(monthIndex) {
	var month = calendarData[monthIndex];
	var cal = "<tr id='calWeek-0'>";
	var daysOfWeek = ['eelaamini-kiišikahki','nkotakone','niišakone','nihsokone','niiyakone','yaalanokone','kaakaathsokone'];
	var i;
	for (i = 0; i < 7; i++) {
		if (month.daysInMonth[1].myaamiaName != daysOfWeek[i]) {
			cal += "<td><div class='miami-label'></div><div class='gregDate'></div></td>";
		} else {
			break;
		}
	}
	var dateIndex = 1;
	for (week = 1; week < 6; week++) {
		for (; i < 7; i++) {
			if (dateIndex > month.numOfDaysInMonth) {
				cal += "<td><div class='miami-label'></div><div class='gregDate'></div></td>";
			} else {
				var dateObj = new Date(month.daysInMonth[dateIndex].gregorianDate);
				gregDate = (dateObj.getUTCMonth() + 1) + "/" + dateObj.getUTCDate();
				cal += "<td><div class='miami-label'>"+month.daysInMonth[dateIndex].dayOfLunarMonth+"</div><div class='gregDate'>"+gregDate+"</div></td>";
				dateIndex++;
			}
		}
		cal += "</tr>";
		if (week != 5) {
			cal += "<tr id='calWeek-" + week + "'>";
		}
		i = 0;
	}
	$("#calendar h2").html(month.myaamiaName);
	$("#calendar h3").html(month.englishName);
	$("#monthNav").html("");
	for (i = 0; i < 5; i++) {
		$("#calWeek-" + i).remove();
	}
	$("#monthNav").append("<a href='#' id='prev' onclick='generateCalendar("+(monthIndex - 1)+")'><< Previous Month</a>");
	if (monthIndex == 1) {
		$("#prev").attr("onclick","");
		$("#prev").css("color", "grey");
		$("#prev").css("cursor", "default");
		$("#prev").css("text-decoration", "none");
	}
	$("#monthNav").append("<a href='#' id='next' style='float:right' onclick='generateCalendar("+(monthIndex + 1)+")'>Next Month >></a>");
	if (calendarData[monthIndex + 1] == undefined) {
		$("#next").attr("onclick","");
		$("#next").css("color", "grey");
		$("#next").css("cursor", "default");
		$("#next").css("text-decoration", "none");

	}  
	$("#calendar").append(cal);	
}
