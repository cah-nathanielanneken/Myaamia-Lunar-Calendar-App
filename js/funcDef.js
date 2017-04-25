var calendarData;
var eventData;
var summaryArray = [];
var gregDateArray = [];
var descArray = [];
var monthIndex;

function init() {
	var x;
    gapi.client.setApiKey('AIzaSyBgO3m5gD5bahtn4LlULE9VnL3q6sKk7Kg');
	var request = gapi.client.request({"path":"https://www.googleapis.com/calendar/v3/calendars/miamioh.edu_8lcvil6egdbsbjrggjuvrgsft4%40group.calendar.google.com/events?timeMin="+ $("#firstDayOfYear").val() +"T00%3A00%3A00%2B00%3A00", "method":"GET"});     //var request = gapi.client.request({"path":"calendars"});
    request.then(function(response) {
	eventData = (JSON.stringify(response.result.items));
	eventData = JSON.parse(eventData);
	for (var i = 0; i < eventData.length; i++) {
		summaryArray.push(eventData[i].summary);
		if (!("description" in eventData[i])) {
			eventData[i].description = "No description available";
		}
		descArray.push(eventData[i].description);
	}
	for ( var i = 0; i < eventData.length; i++) {
		if (eventData[i].start == null)
			gregDateArray.push(0);
		else
			gregDateArray.push(eventData[i].start.date);
	}
	if (monthIndex === undefined) {
		generateCalendar(1);
	} else {
		generateCalendar(monthIndex);
	}
	$("#cal").show();
    }, function(reason) {
		console.log(reason);
    });
}

function getFirstDayOfPhase(dayArr, perfPhase) {
	var day = dayArr[0], moonSize = parseFloat(dayArr[0].attributes['data-moon-phase'].value);
	for (var i = 1; i < dayArr.length; i++) {
		var moonSizetmp = parseFloat(dayArr[i].attributes['data-moon-phase'].value);
		if (Math.abs(moonSizetmp - perfPhase) < Math.abs(moonSize - perfPhase)) {
			day = dayArr[i];
			moonSize = moonSizetmp;
		}
	}
	return day;
}

function generateCalendar(monthIndex) {
	
	var eventDescription = "";
	var dateMatch = -1, id = "";
	
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
					day = dateObj.getDate() + 1;
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
					eventDetails = descArray[dateMatch];
				} else {
					eventDescription == "";
					eventDetails = "";
				}

				id = "";

				if (eventDescription.toLowerCase() == "beginning of napale") {
					id = "firstQ";
				} else if (eventDescription.toLowerCase() == "beginning of waawiyiisita") {
					id = "secondQ";
				} else if (eventDescription.toLowerCase() == "beginning of napale neepiki") {
					id = "thirdQ";
				}
					
			
				cal += "<td id='" + id + "' data-moon-phase-name='" + month.daysInMonth[dateIndex].moonPhaseName +"' data-moon-phase="+ month.daysInMonth[dateIndex].moonPhase  +"><div class='miami-label'>"+month.daysInMonth[dateIndex].dayOfLunarMonth+"</div><div class='events'><div class='description'><b>"+ eventDescription + "</b><br>" + eventDetails  +"</div><div class='event-name'>"+ eventDescription +"</div></div><div class=";
	
				cal += "'gregDate'>" + gregDate + "</div></td>";
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

	var fullMoon = $("td[data-moon-phase-name='Full Moon']"), firstQuarter = $("td[data-moon-phase-name='First Quarter']"), thirdQuarter = $("td[data-moon-phase-name='Third Quarter']");

	var day = $("#secondQ")[0] === undefined ? getFirstDayOfPhase(fullMoon, 0.5) : $("#secondQ")[0];	
	$("<div class='moon-pic full'><div class='moon-wrapper'><img src='res/images/waawiyiisita.png'></div></div>").insertBefore($(day).children().last());
	
	day = $("#firstQ")[0] === undefined ? getFirstDayOfPhase(firstQuarter, 0.25) : $("#firstQ")[0];
	$("<div class='moon-pic'><div class='moon-wrapper'><img src='res/images/napale.png'></div></div>").insertBefore($(day).children().last());

	day = $("#thirdQ")[0] === undefined ? getFirstDayOfPhase(thirdQuarter, 0.75) : $("#thirdQ")[0];
	$("<div class='moon-pic'><div class='moon-wrapper'><img src='res/images/napale-neepiki.png'></div></div>").insertBefore($(day).children().last());



	//This allows the popup div to appear/disappear

	$('.events').mouseover(function() {
	  $(this).children(".description").show();
	}).mouseout(function() {
	  $(this).children(".description").hide();
	});	
}
