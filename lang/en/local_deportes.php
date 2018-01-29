<?php
/**
 * Strings for component "sports", language "en"
 *
 * @package	deportes
 */

defined("MOODLE_INTERNAL") || die();

//$string[""] = "";

$string["pluginname"] = "deportes";
$string["view"] = "View";
$string["attendance"] = "Attendance";
$string["schedule"] = "Schedule";
$string["reserve"] = "Reserve";
$string["teacher"] = "Teacher";
$string["reserved"] = "Reserved";
$string["quota"] = "Quota";
$string["nav_title"] = "Sports";
$string["page_title"] = "Sports";
$string["page_heading"] = "Sports";
$string["token"] = "Token Omega";
$string["tokendesc"] = "token given by Omega for Api usage";
$string["urlasistenciasalumno"] = "URL AsistenciasAlumnos Service";
$string["urlasistenciasalumnodesc"] = "URL Omega Webapi to check the students sports attendance";
$string["urldeportesalumno"] = "URL DeportesAlumno Service";
$string["urldeportesalumnodesc"] = "URL Omega Webapi to check students available sports";
$string["startmonth"] = "Starting month";
$string["startmonthdesc"] = "First month of the sports period";
$string["endmonth"] = "Last month";
$string["endmonthdesc"] = "Last month of the sports period";
$string["startday"] = "Starting day";
$string["startdaydesc"] = "First day of the sports period. Students can start attending to sport classes from this day";
$string["endday"] = "Last day";
$string["enddaydesc"] = "Last day of the sports period. Any attendance beyond this day won't be counted towards the period's total";
$string["situation"] = "Situation";
$string["passed"] = "Passed";
$string["pending"] = "Pending";
$string["failed"] = "Failed";
$string["rules"] = "Rules";
$string["recommended"] = "Recommended";
$string["help"] = "Chart information";
$string["emailextension"] = "Email extension";
$string["emailextensiondesc"] = "Email extension used in the Omega platform";
$string["courseid"] = "General course ID";
$string["courseiddesc"] = "General information course ID used to link it on the UAI block. The link won't show if this value is 0";
$string["totalattendance"] = "Total attendances";
$string["totalattendancedesc"] = "Number of times the student must attend to sport classes during the semester to pass";
$string["nosportsleft"] = "There are no sports left for reservations";
$string["selectsport"] = "Select the sports type";
$string["sport_type"] = "Sports type";
$string["sport_type_help"] = "Select the type of sports the image represents. One image per sports type must be uploaded at a time.";
$string["uploadfile"] = "Upload file";
$string["sportsform"] = "Add a sports schedule file";
$string["selectfile"] = "Select file";
$string["must_uploadfile"] = "No file was uploaded. Please try again.";

$string["Jan"] = "January";
$string["Feb"] = "February";
$string["Mar"] = "March";
$string["Apr"] = "April";
$string["May"] = "May";
$string["Jun"] = "June";
$string["Jul"] = "July";
$string["Aug"] = "August";
$string["Sep"] = "September";
$string["Oct"] = "October";
$string["Nov"] = "November";
$string["Dec"] = "December";


$string["month"] = "Month";
$string["week"] = "Week";
$string["date"] = "Date";
$string["sport"] = "Sport";
$string["t_start"] = "Start time";
$string["t_end"] = "End time";
$string["fitness"] = "Fitness";
$string["outdoor"] = "Outdoor";

$string["calendarchartweek"] = "SMTWTFS";
$string["number"] = "#";
$string["totalattendance"] = "Total attendance";
$string["minimumattendance"] = "Required minimum";
$string["monthattendance"] = "This month";
$string["sportschart_title"] = "Sport classes";
$string["sportschart_subtitle"] = "Total attendances";

//module_form langs
$string["module_form"] = "Modules From";
$string["module_name"] = "Module name";
$string["module_name_help"] = "Enter a text for the module name";
$string["module_initialhour"] = "Module initial hour";
$string["module_initialhour_help"] = "Enter the initial hour for the module with format: 16:00, 08:00, 23:30, 10:15";
$string["module_endhour"] = "Module end hour";
$string["module_endhour_help"] = "Enter the end hour for the module with format: 16:00, 08:00, 23:30, 10:15";
$string["module_type"] = "Module type";
$string["module_type_help"] = "Select if the module is for fitness clases or outdoor clases";

$string["close"] = "Close";
$string["rules_title"] = "Sports rules";
$string["graphinfo"] = "Graph information";
$string["rules_content1"] = "Dear Student <br>
	To approve your sports credits you must do the following:<br>
	* Number of attendances: <b>26</b><br>
	* Semester starts: <b>";
$string["rules_content2"]= "</b><br>
	* Semester ends: <b>";
$string["rules_content3"] = "</b><br>
	You may do as many attendances as you like in a day, month or semester, but:<br>
	* <b>Only 1 attendance will be valid each day.</b><br>
	* A maximum of <b>8</b> attendances are valid per <b>month</b>.<br>
	* 26 valid attendances in the semester.<br>
	* It is the student's exclusive responsibility the amount of attendances each month.
	Keep in mind that you must have a total of 26 attendances by the end of the semester
	with a maximum of 8 <b>VALID</b> attendances each month.<br>
	* Reserving a class and not attending it will substract 1 attendance.<br>
	* Cancelling a class 90 minutes before it starts will substract half (0.5) an attendance.<br>
	* You can attend to sports as much as you like, yet we recommend you to attend 2 times each
	week to get the fisiologic benefits of continuous physical activity.";
$string["graphinfo_content"] = '<img src="img/row.png"><br>
	Each row represents a day of the week.
	As the image shows, the highlighted row corresponds to every wednesday of the year.<br>
	<img src="img/col.png"><br>
	Each column is a week, starting with sunday at the top and ending with saturday at the bottom.
	Every column has 7 spaces down, for the 7 days of the week.<br>
	<img src="img/punishment.png"><br>
	One cell corresponds to one day. When there are no attendances that day, it will have no color (grey background).
	One valid attendance (+1) in that day will display a blue colored cell, while one punishment (-1) will display an orange colored cell.
	Half an attendance (+0.5) has a light blue color, and half a punishment (-0.5) has a light orange color.<br>
	<img src="img/blank.png"><br>
	A null attendance (0) has a white color, as the image shows. They are slightly different from the grey, empty ones.<br>
	<b>Note</b> that the graph displays <b>valid</b> attendances. If you attend once (+1) but also get half a punishment (-0.5) that day,
	the graph will display the corresponding half attendance (+0.5).<br>
	Hovering the cursor over a cell will show the date and attendance value detail on that day.';