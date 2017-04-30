# Myaamia-Lunar-Calendar-App

This project allows for the creation of a Myaamia Lunar Calendar to be generated based on
the Google Calendar Myaamia Lunar Calendar (calendar ID: miamioh.edu_8lcvil6egdbsbjrggjuvrgsft4@group.calendar.google.com).
This project is a combination of PHP, JS, HTML, and CSS files that all work together to generate the calendar.
As an overview, the PHP files begin to generate what the calendar should look like based on our algorithm. Once the
page has loaded, the Javascript files are used to call the Google Calendar API to pull the events from the calendar.
Once it has pulled the events, they are populated on the Lunar calendar on their respective lunar dates. From there, the
user can mouse over the events and see a more detailed description of the event. If there are multiple events,
the user can click on the "[number of events] more" button to display all events and their details for that day in a popup.
The website is also created responsively for mobile view.
