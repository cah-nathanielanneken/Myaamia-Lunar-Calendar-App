var calendarData;
var eventData;
var summaryArray = [];
var gregDateArray = [];
var descArray = [];
var monthIndex;
var shortNames = ["eel.", "nko.", "nii.", "nih.", "nii.", "yaa.", "kaa."];
var longNames = ["eelaamini-kiišikahki", "nkotakone", "niisšakone", "nihsokone", "niiyakone", "yaalanokone", "kaakaathsokone"];

// Function for toggling between short and long Myaamia day names
function shortenNames() {
	if ($(window).width() < 645) {
		var i = 0;
		$("#dayNames").children().each(function() {
			$(this).html(shortNames[i++]);
		});
	} else {
		var i = 0;
		$("#dayNames").children().each(function() {
			$(this).html(longNames[i++]);
		});
	}
}

// Function for toggling date colors for mobile view
function colorMobile() {
	$("td").each(function() {
		if ($(this).children(".mobile-event").length > 0) {
			$(this).children(".miami-label").addClass("white");
		}
	});

}

// Trigger name shorter method if window is resized
$(window).resize(function() {
	shortenNames();
});

function init() {
	// Get calendar data from Google Calendar
	var x;
    gapi.client.setApiKey('AIzaSyBgO3m5gD5bahtn4LlULE9VnL3q6sKk7Kg');
	var request = gapi.client.request({"path":"https://www.googleapis.com/calendar/v3/calendars/miamioh.edu_8lcvil6egdbsbjrggjuvrgsft4%40group.calendar.google.com/events?timeMin="+ $("#firstDayOfYear").val() +"T00%3A00%3A00%2B00%3A00", "method":"GET"});    
    request.then(function(response) {
	// On success, save data, and parse description data from Google
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
			if (eventData[i].start.date !== undefined) {
				var tmp = new Date(eventData[i].end.date), tmp2 = new Date(eventData[i].start.date);
				var str = (tmp.getFullYear()) + "-" + (tmp.getMonth() + 1 < 10 ? "0" + (tmp.getMonth() + 1) : (tmp.getMonth() + 1)) + "-" + ((tmp.getDate() < 10 ? "0" + (tmp.getDate()) : (tmp.getDate())));
				
				var str2 = (tmp2.getFullYear()) + "-" + (tmp2.getMonth() + 1 < 10 ? "0" + (tmp2.getMonth() + 1) : (tmp2.getMonth() + 1)) + "-" + ((tmp2.getDate() + 1 < 10 ? "0" + (tmp2.getDate() + 1) : (tmp2.getDate() + 1)));
				var incr1 = new Date(eventData[i].start.date), incr2 = new Date(eventData[i].end.date);
				incr1.setDate(incr1.getDate() + 1);
				
				var eventDetails = "<b>Description:</b> " + descArray[i] + "<br><br><b>Time:</b> ";
				if (incr1.getFullYear() == incr2.getFullYear() && incr1.getMonth() == incr2.getMonth() && incr1.getDate() == incr2.getDate()) {
					eventDetails += incr1.toDateString();
				} else {
					eventDetails += incr1.toDateString() + "-" + incr2.toDateString();
				}
				
				gregDateArray.push({"start":str2, "end":str, "printTime":eventDetails});
			} else {
				var tmp = new Date(eventData[i].start.dateTime), tmp2 = new Date(eventData[i].end.dateTime);
				var year1 = tmp.getFullYear(), year2 = tmp2.getFullYear();
				var month1 = tmp.getMonth() + 1, month2 = tmp2.getMonth() + 1;
				var day1 = tmp.getDate(), day2 = tmp2.getDate();
				if (month1 < 10) {
					month1 = "0" + month1;
				}
				if (month2 < 10) {
					month2 = "0" + month2;
				}
				if (day1 < 10) {
					day1 = "0" + day1;
				}
				if (day2 < 10) {
					day2 = "0" + day2;
				}
					
				var str = year1 + "-" + month1 + "-" + day1, str2 = year2 + "-" + month2 + "-" + day2;

				var incr1 = new Date(eventData[i].start.dateTime), incr2 = new Date(eventData[i].end.dateTime);
				incr1.setDate(incr1.getDate());
				var eventDetails = "<b>Description:</b> " + descArray[i] + "<br><br><b>Time:</b> " + incr1.toDateString() + " " + incr1.toLocaleTimeString() + "-" + incr2.toDateString() + " " + incr2.toLocaleTimeString();
	
				
				gregDateArray.push({"start":str, "end":str2, "printTime":eventDetails});
			}
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

// Calculates the first day of a given moon phase by checking for the midnight whose
// moon size is the closest to the value of the perfect moon phase
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

// UI display logic for creating the calendar on the DOM
function generateCalendar(monthIndex) {
	shortenNames();	
	var eventDescription = "";
	var dateMatch = -1, id = "", eventIdCount = 0;
	
	var month = calendarData[monthIndex];
	var cal = "<tr id='calWeek-0'>";
	var daysOfWeek = ['eelaamini-kiišikahki','nkotakone','niišakone','nihsokone','niiyakone','yaalanokone','kaakaathsokone'];
	var i, y = 0;
	// Iterate over blank days until first day of lunar month is found
	for (i = 0; i < 7; i++) {
		if (month.daysInMonth[1].myaamiaName != daysOfWeek[i]) {
			cal += "<td><div class='miami-label'></div><div class='gregDate'></div></td>";
		} else {
			break;
		}
	}
	
	// Check if extra calendar row should be printed for extra day
	var printExtraWeek =  ((i == 6  && parseInt(month.numOfDaysInMonth) == 30) ? true : false);

	// Iterate over all weeks
	var dateIndex = 1;
	for (week = 1; (week < 6) || (printExtraWeek && week < 7); week++) {
		for (; i < 7; i++) {
			dateMatch = [];
			var eventDescription = "", eventDetails = "";
			// Check to ensure more days are in month
			if (dateIndex > month.numOfDaysInMonth) {
				cal += "<td><div class='miami-label'></div><div class='gregDate'></div></td>";
			} else {
				// Look for any Google Calendar data that matches the current date
				var dateObj = new Date(month.daysInMonth[dateIndex].gregorianDate);
				gregDate = (dateObj.getUTCMonth() + 1) + "/" + dateObj.getUTCDate();
				for (var j = 0; j < gregDateArray.length; j++) {
					var month1, day, year;
					var tmpDateObj = new Date(dateObj);
					tmpDateObj.setYear(dateObj.getFullYear());
					tmpDateObj.setMonth(dateObj.getMonth());
					tmpDateObj.setDate(dateObj.getDate() + 1);

					month1 = tmpDateObj.getMonth() + 1;
					day = tmpDateObj.getDate();
					year = tmpDateObj.getFullYear();
					var convertedDate = year + "-" + month1 + "-" + day;
					var dateObjS = new Date(gregDateArray[j].start);
					var dateObjE = new Date(gregDateArray[j].end);
					dateObjS.setDate(dateObjS.getDate() + 1);
					dateObjE.setDate(dateObjE.getDate() + 1);
					if (dateObjS.getFullYear() <= year && dateObjE.getFullYear() >= year && dateObjS.getMonth() + 1 <= month1 && dateObjE.getMonth() + 1 >= month1 && dateObjS.getDate() <= day && dateObjE.getDate() >= day) {
						dateMatch.push(j);
					}
				}
				if (dateMatch.length >= 1) {
					eventDescription = summaryArray[dateMatch[0]];
					eventDetails = gregDateArray[dateMatch[0]].printTime;
				} 

				id = "";

				// Check to see if Google Calendar data exists for moon phases, if it does, manually override programatic calculations
				if (eventDescription.toLowerCase() == "beginning of napale") {
					id = "firstQ";
				} else if (eventDescription.toLowerCase() == "beginning of waawiyiisita") {
					id = "secondQ";
				} else if (eventDescription.toLowerCase() == "beginning of napale neepiki") {
					id = "thirdQ";
				}
					
				var curDate = new Date(), extraClass = "";
				dateObj.setDate(dateObj.getDate() + 1);
				if (dateObj.getMonth() == curDate.getMonth() && dateObj.getFullYear() == dateObj.getFullYear() && dateObj.getDate() == curDate.getDate()) {
					extraClass = "current-day";
				}			

				// Add day to calendar
				cal += "<td id='" + id + "' class='" + extraClass + "' data-moon-phase-name='" + month.daysInMonth[dateIndex].moonPhaseName +"' data-moon-phase="+ month.daysInMonth[dateIndex].moonPhase  +"><div class='miami-label'><div class='miami-label-inner'>"+month.daysInMonth[dateIndex].dayOfLunarMonth+"</div></div><div class='events'>";

				// If day has more than one event in it, add Bootstrap popup
				if (dateMatch.length > 1) {
					cal += "<div class='modal fade' id='myModal"+i+"' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'> <div class='modal-dialog' role='document'> <div class='modal-content'> <div class='modal-header'> <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <h4 class='modal-title' id='myModalLabel'>Events</h4> </div> <div class='modal-body'>";

					// Add all events to popup
					var x = 0;
					while (x < dateMatch.length) {
						eventDetails = gregDateArray[dateMatch[x]].printTime;
	
						cal += "<p class='text-primary' data-toggle='collapse' href='#eventId-" + eventIdCount + "' aria-expanded='false' aria-controls='eventId-" + x + "'>" + summaryArray[dateMatch[x]] + "</p><div class='collapse' id='eventId-" + eventIdCount + "'>" + eventDetails + "</div>";
						x++;
						eventIdCount++;
					}

					cal += "</div><div class='modal-footer'> <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button> </div> </div> </div> </div>";
	
					cal += "<div type='button' class='event-name second' data-toggle='modal' data-target='#myModal"+i+"'>" + (dateMatch.length - 1) + " more... </div>";
				}

				// Add proper hover icon for desktop view to view event details
				if (week == 5) {
					if (i == 5 || i == 6) {
						cal += "<div class='description descriptionUpLeft'>";
					} else {
						cal += "<div class='description descriptionUp'>"; 
					}
				} else if (i == 5 || i == 6) { 
					cal += "<div class='description descriptionLeft'>";
				} else {
					cal += "<div class='description'>";
				}
	
				cal += "<b><div class='descriptionHeader'>"+ eventDescription + "</b><br></div>" + eventDetails  +"</div><div class='event-name'>"+ eventDescription +"</div></div></div>";

				// If day has an event in it, add mobile popup for mobile view
				if (dateMatch.length > 0) {
					cal += "<div class='mobile-event'><div class='modal fade' id='myModal"+y+"-mobile' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'> <div class='modal-dialog' role='document'> <div class='modal-content'> <div class='modal-header'> <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <h4 class='modal-title' id='myModalLabel'>Events</h4> </div> <div class='modal-body'>";
			
					// Add all events to popup
					var x = 0;	
					while (x < dateMatch.length) {
						eventDetails = gregDateArray[dateMatch[x]].printTime;

						cal += "<p class='text-primary' data-toggle='collapse' href='#eventId-" + eventIdCount + "-mobile' aria-expanded='false' aria-controls='eventId-" + eventIdCount + "-mobile'>" + summaryArray[dateMatch[x]] + "</p><div class='collapse' id='eventId-" + eventIdCount + "-mobile'>" + eventDetails + "</div>";
						x++;
						eventIdCount++;
					}

					cal += "</div><div class='modal-footer'> <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button> </div> </div> </div> </div>";

					cal += "<div type='button' class='event-name second' data-toggle='modal' data-target='#myModal"+y+"-mobile'><div class='mobile-circle'></div></div></div>";
					y++;
				}

				// Add gregorian day to calendar day
				cal += "<div class=gregDate>" + gregDate + "</div></td>";
				dateIndex++;
			}
		}
		cal += "</tr>";
		if (week != 5 || (printExtraWeek && week != 6)) {
			cal += "<tr id='calWeek-" + week + "'>";
		}
		i = 0;
	}

	// Insert calendar to DOM
	$("#calendar h2").html(month.myaamiaName);
	$("#calendar h3").html(month.englishName);
	$("#monthNav").html("");
	for (i = 0; i < 7; i++) {
		$("#calWeek-" + i).remove();
	}
	$("#monthNav").append("<a href='#' id='prev' class='btn btn-default'  onclick='generateCalendar("+(monthIndex - 1)+")'><< Previous Month</a>");
	if (monthIndex == 1) {
		$("#prev").addClass('disabled');
		$("#prev").css('color', 'grey');
	}
	$("#monthNav").append("<a href='#' id='next' class='btn btn-default'  style='float:right' onclick='generateCalendar("+(monthIndex + 1)+")'>Next Month >></a>");
	if (calendarData[monthIndex + 1] == undefined) {
		$("#next").addClass('disabled');
		$("#next").css('color', 'grey');
	}  
	$("#calendar").append(cal);

	// Insert moon phase icons based upon either programatic calculations or Google Calendar override
	var fullMoon = $("td[data-moon-phase-name='Full Moon']"), firstQuarter = $("td[data-moon-phase-name='First Quarter']"), thirdQuarter = $("td[data-moon-phase-name='Third Quarter']");

	var day = $("#secondQ")[0] === undefined ? getFirstDayOfPhase(fullMoon, 0.5) : $("#secondQ")[0];	
	$("<div class='moon-pic full'><div class='moon-wrapper'><img src='res/images/waawiyiisita.png'><div class='wrap-moon'></div></div></div>").insertBefore($(day).children().last());
	
	day = $("#firstQ")[0] === undefined ? getFirstDayOfPhase(firstQuarter, 0.25) : $("#firstQ")[0];
	$("<div class='moon-pic'><div class='moon-wrapper'><img src='res/images/napale.png'><div class='wrap-moon'></div></div></div>").insertBefore($(day).children().last());

	day = $("#thirdQ")[0] === undefined ? getFirstDayOfPhase(thirdQuarter, 0.75) : $("#thirdQ")[0];
	$("<div class='moon-pic'><div class='moon-wrapper'><img src='res/images/napale-neepiki.png'><div class='wrap-moon'></div></div></div>").insertBefore($(day).children().last());

	// Color mobile dates white if they have one event in them
	colorMobile();

	//This allows the popup div to appear/disappear

	$('.event-name').not('.second').mouseover(function() {
	  $(this).siblings('.description').show();
	}).mouseout(function() {
	  if (!$(this).siblings('.description').is(':hover')) {
	    $(this).siblings('.description').hide();
	  } else {
	    $(this).siblings('.description').mouseout(function() { $(this).hide(); });
	  }
	});

	$('.wrap-moon').mouseover(function() {
		$(this).parent().parent().siblings('.events').children('.description').show();
	}).mouseout(function() {
		$(this).parent().parent().siblings('.events').children('.description').hide();
	});

	// Allows for mouse-over or clicking of events to display data
	$(document).on('click touchstart', function() {
		$('.description').hide();
	});

	// Function to bring up pane for multiple events
	$('#myModal').on('shown.bs.modal', function () {
  		$('#myInput').focus()
	});
}
