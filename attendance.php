<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 *
*
* @package    local
* @subpackage deportes
* @copyright  2017	Mark Michaelsen (mmichaelsen678@gmail.com)
* @copyright  2017	Mihail Pozarski (mpozarski944@gmail.com)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

require_once(dirname(dirname(dirname(__FILE__))) . "/config.php");
require_once($CFG->dirroot."/local/deportes/locallib.php");
require_once($CFG->libdir . "/tablelib.php");
global $CFG, $DB, $OUTPUT, $PAGE, $USER;

// User must be logged in.
require_login();
if (isguestuser()) {
	die();
}

$page = optional_param('page', 0, PARAM_INT);
$perpage = 15;

$context = context_system::instance();

$url = new moodle_url("/local/deportes/attendance.php");
$PAGE->navbar->add(get_string("nav_title", "local_deportes"));
$PAGE->navbar->add(get_string("attendance", "local_deportes"), $url);
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout("standard");
$PAGE->set_title(get_string("page_title", "local_deportes"));
$PAGE->set_heading(get_string("page_heading", "local_deportes"));

$email = $USER->email;

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
	echo html_writer::div(get_string("notvalidemail","local_deportes"),"alert alert-info", array("role"=>"alert"));
}

$curl = curl_init();
$url = $CFG->deportes_urlasistenciasalumno;
$token = $CFG->sync_token;
$fields = array(
		"token" => $token,
		"email" => $email
);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_POST, TRUE);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
$result = json_decode(curl_exec($curl));
curl_close($curl);

if(count($result->asistencias->asistencias)>0){
	$table = new html_table("p");
	
	$table->head = array(
			get_string("number", "local_deportes"),
			get_string("month", "local_deportes"),
			get_string("week", "local_deportes"),
			get_string("date", "local_deportes"),
			get_string("sport", "local_deportes"),
			get_string("t_start", "local_deportes"),
			get_string("t_end", "local_deportes"),
			get_string("attendance", "local_deportes")
	);
	
	$table->size = array(
			"7%",
			"7%",
			"7%",
			"13%",
			"20%",
			"15%",
			"15%",
			"16%"
	);
	
	$data = $result->asistencias->asistencias;
	$attendancechart = array();
	$repeated = 0;
	$totalattendance = 0;
	$monthattendance = 0;
	$today = date("m",strtotime(time()));
	$counter = $page * $perpage + 1;
	foreach($data as $attendance) {
		if(date('Y-m-d',strtotime($attendance->HoraInicio . ' +1 day')) == $date){
				$repeated = 1;
		}
		if($repeated != 1){
			$date = date('Y-m-d',strtotime($attendance->HoraInicio . ' +1 day'));
			$attendancechartinfo = array(
					$date,
					$attendance->Asistencia
			);
			if($today == date("m",strtotime($attendance->HoraInicio))){
				$monthattendance = $monthattendance + $attendance->Asistencia;
			}
			$totalattendance = $totalattendance + $attendance->Asistencia;
		}
		$attendancechart[] = $attendancechartinfo;
		$repeated = 0;
		
		$attendanceinfo = array(
				$counter,
				date('F', mktime(0, 0, 0, $attendance->Mes, 10)),
				$attendance->Semana,
				date("d-m-Y",strtotime($attendance->HoraInicio)),
				$attendance->Deporte,
				date("H:i",strtotime($attendance->HoraInicio)),
				date("H:i",strtotime($attendance->HoraTermino)),
				$attendance->Asistencia
		);
		$counter++;
		
		$table->data[] = $attendanceinfo;
		
	}
}

echo $OUTPUT->header();
echo $OUTPUT->heading("DeportesUAI");
echo $OUTPUT->tabtree(deportes_tabs(), "attendance");
if(!(count($result->asistencias->asistencias)>0)){
	echo html_writer::div(get_string("noattendance","local_deportes"),"alert alert-info", array("role"=>"alert"));
}else{
	echo html_writer::tag('div','', array('id' => 'calendar_basic'));
	echo html_writer::tag('h3',get_string('totalattendance','local_deportes').": ".$totalattendance, array("style"=>"display:inline; float:left;"));
	echo html_writer::tag('h3',get_string('monthattendance','local_deportes').": ".$monthattendance, array("style"=>"display:inline; float:right;"));
	echo html_writer::table($table);
}
echo $OUTPUT->footer();
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load("current", {packages:["calendar"]});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

	var dataarray = <?php echo  json_encode($attendancechart);?>;
	var arraylength = dataarray.length;
	var startdate = 0;
	
	for (var i = 0; i < arraylength; i++) {
		startdate = dataarray[i][0]
		dataarray[i][0] = new Date(startdate);
	}
	
	var dataTable = new google.visualization.DataTable();
	dataTable.addColumn({ type: 'date', id: 'Date' });
	dataTable.addColumn({ type: 'number', id: 'Won/Loss' });
	dataTable.addRows(dataarray);
	
	var chart = new google.visualization.Calendar(document.getElementById('calendar_basic'));
	
	var options = {
		title: "attendance",
		width: '920',
		colorAxis:{
			minValue:-1,
			maxValue:1
		},
		calendar: {
		    dayOfWeekLabel: {
		        fontName: 'Times-Roman',
		        fontSize: 12,
		        color: '#000000',
		        bold: true,
		        italic: true,
		    },
			yearLabel: {
		        color: '#000000',
		        bold: true,
		        italic: true
	      	},
	      	monthLabel: {
	            color: '#000000',
	            bold: true,
	            italic: true
	        },
		      dayOfWeekRightSpace: 10,
		      daysOfWeek: '<?php echo get_string('calendarchartweek', 'local_deportes');?>',
		    }
	};
	
	chart.draw(dataTable, options);
}
</script>