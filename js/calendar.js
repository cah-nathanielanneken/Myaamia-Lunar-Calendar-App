var calendarData;
var eventData;
var summaryArray = [];
var gregDateArray = [];
$(document).ready(function() {
			
	$("#cal").hide();
	$("form").submit(function(event) {
		event.preventDefault();
		var data = {"firstDayOfYear":$("#firstDayOfYear").val(), "isExtraMoon":$("input[name='isExtraMoon']:checked").val(), "removalDays":[]};
		$.ajax({
			method:"POST",
			url: "/~annekent/capstone/Myaamia-Lunar-Calendar-App/ParamaterYear/getYear.php",
			data: JSON.stringify(data),
			dataType: 'json',
			success: function(data) {
				init();
				console.log(eventData);
				console.log(data);
				calendarData = data;
				$("#params").hide();
			},
			error: function(status) {
				console.log(status);
			}
		});
		
		//console.log(events)
	});
});

function init() {
	var x;
    gapi.client.setApiKey('AIzaSyBgO3m5gD5bahtn4LlULE9VnL3q6sKk7Kg');
	var request = gapi.client.request({"path":"https://www.googleapis.com/calendar/v3/calendars/miamioh.edu_8lcvil6egdbsbjrggjuvrgsft4%40group.calendar.google.com/events?timeMin="+ $("#firstDayOfYear").val() +"T00%3A00%3A00%2B00%3A00&fields=items(summary%2Cstart%2Fdate%2Cend%2Fdate)", "method":"GET"});     //var request = gapi.client.request({"path":"calendars"});
    request.then(function(response) {
		eventData = (JSON.stringify(response.result.items));
		eventData = JSON.parse(eventData);
		console.log(eventData);
		console.log("logging before calendar is generated");
		for (var i = 0; i < eventData.length; i++) {
			summaryArray.push(eventData[i].summary)
	}
	for ( var i = 0; i < eventData.length; i++) {
		if (eventData[i].start == null)
			gregDateArray.push(0);
		else
			gregDateArray.push(eventData[i].start.date);
	}
		generateCalendar(1);
		$("#cal").show();
    }, function(reason) {
		console.log(reason);
    });
}


function generateCalendar(monthIndex) {
	
	var eventDescription = "";
	var dateMatch = -1;
	
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
	var dateIndex = 1, firstQSet = false, fullSet = false, secondQSet = false;
	for (week = 1; week < 6; week++) {
		for (; i < 7; i++) {
			dateMatch = -1;
			eventDescription = ""
			if (dateIndex > month.numOfDaysInMonth) {
				cal += "<td><div class='miami-label'></div><div class='gregDate'></div></td>";
			} else {
				var dateObj = new Date(month.daysInMonth[dateIndex].gregorianDate);
				gregDate = (dateObj.getUTCMonth() + 1) + "/" + dateObj.getUTCDate();
				
				for (var j = 0; j < gregDateArray.length; j++) {
					var month1, day, year;
					month1 = dateObj.getMonth() + 1;
					day = dateObj.getDate();
					year = dateObj.getFullYear();
					if (day < 10)
						day = "0" + day
					if (month1 < 10)
						month1 = "0" + month1
					var convertedDate = year + "-" + month1 + "-" + day;
					if (gregDateArray[j] == convertedDate)
						dateMatch = j;
				}
				if (dateMatch > -1) {
					eventDescription = summaryArray[dateMatch];
				} else {
					eventDescription == "";
				}
					
			
				cal += "<td><div class='miami-label'>"+month.daysInMonth[dateIndex].dayOfLunarMonth+"</div><div class='events'>"+ eventDescription +"</div><div class=";
	
				//change stuff here for adding in events
				if (month.daysInMonth[dateIndex].moonPhaseName == "Full Moon" && !fullSet) {
				  cal += "'moon-word'></div><div class='moon-pic full'><img src='res/images/waawiyiisita.png'></div><div class='gregDate'>" + gregDate + "</div></td>";
				  fullSet = true;
				} else if (month.daysInMonth[dateIndex].moonPhaseName == "First Quarter" && !firstQSet) {
				  cal += "'moon-word'></div><div class='moon-pic'><img src='res/images/napale.png'></div><div class='gregDate'>" + gregDate + "</div></td>";
				  firstQSet = true;
				} else if (month.daysInMonth[dateIndex].moonPhaseName == "Third Quarter" && !secondQSet) {
				  cal += "'moon-word'></div><div class='moon-pic'><img src='res/images/napale-neepiki.png'></div><div class='gregDate'>" + gregDate + "</div></td>";
				  secondQSet = true;
				} else {
				  cal += "'gregDate'>" + gregDate + "</div></td>";
				}
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
