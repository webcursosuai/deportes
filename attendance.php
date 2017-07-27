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
$email = $USER->email;
$context = context_system::instance();

if(($email[1] == $CFG->deportes_emailextension) || is_siteadmin() || has_capability("local/deportes:edit", $context)){
	


	$url = new moodle_url("/local/deportes/attendance.php");
	$PAGE->navbar->add(get_string("nav_title", "local_deportes"));
	$PAGE->navbar->add(get_string("attendance", "local_deportes"), $url);
	$PAGE->set_context($context);
	$PAGE->set_url($url);
	$PAGE->set_pagelayout("standard");
	$PAGE->set_title(get_string("page_title", "local_deportes"));
	$PAGE->set_heading(get_string("page_heading", "local_deportes"));
	
	
	
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		echo html_writer::div(get_string("notvalidemail","local_deportes"),"alert alert-info", array("role"=>"alert"));
	}
	
	$curl = curl_init();
	$url = $CFG->deportes_urlasistenciasalumno;
	$token = $CFG->sync_token;
	$fields = array(
			"token" => $token,
			"email" => "mscalvini@alumnos.uai.cl"
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
				"5%",
				"7%",
				"7%",
				"15%",
				"20%",
				"15%",
				"15%",
				"16%"
		);
		
		$data = $result->asistencias->asistencias;
		$attendancechart = array();
		$sports = array();
		$sportschart = array();
		$repeated = 0;
		$totalattendance = 0;
		$monthlyattendance = array(
				1 => 0,
				2 => 0,
				3 => 0,
				4 => 0,
				5 => 0,
				6 => 0,
				7 => 0,
				8 => 0,
				9 => 0,
				10 => 0,
				11 => 0,
				12 => 0
		);
		$today = date("m", time());
		$counter = $page * $perpage + 1;
		$date = 0;
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
				
				$month = date("m",strtotime($attendance->HoraInicio));
				$monthlyattendance[(int)$month] += $attendance->Asistencia;
			}
			
			$attendancechart[] = $attendancechartinfo;
			$repeated = 0;
			
			if($attendance->Asistencia == 1) {
				if(isset($sports[$attendance->Deporte])) {
					$sports[$attendance->Deporte] += 1;
				} else {
					$sports[$attendance->Deporte] = 1;
				}
			}
			
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
		
		// Limit monthly attendance to a maximum of 8
		foreach($monthlyattendance as $month => $monthattendance) {
			$monthlyattendance[$month] = ($monthattendance > 8) ? 8 : $monthattendance;
			$totalattendance += $monthlyattendance[$month];
		}
		
		$firstmonth = $CFG->deportes_startmonth;
		$lastmonth = $CFG->deportes_endmonth;
		$actualmonth = (int)$today;
		$elapsedmonths = $actualmonth - $firstmonth;
		$monthsremaining = ($lastmonth - $firstmonth + 1) - $elapsedmonths;
		$minimumpermonth = array();
		$total = 26;
		
		$completedmonth = 0;
		while($elapsedmonths > 0) {
			$minimumpermonth[$firstmonth + $completedmonth] = $monthlyattendance[$firstmonth + $completedmonth];
			$elapsedmonths--;
			$completedmonth++;
		}
		
		$counter = 0;
		$failed = false;
		while(array_sum($minimumpermonth) < $total) {
			if(count($minimumpermonth) > 4 || ($monthsremaining - 1) < $counter) {
				$failed = true;
				break;
			} else {
				if(!isset($minimumpermonth[$lastmonth - $counter])) {
					$difference = max($total - array_sum($minimumpermonth), 0);
					$minimumpermonth[$lastmonth - $counter] = ($difference > 8) ? 8 : $difference;
				}
				
				$counter++;
			}
		}
		
		$minimumrequired = (isset($minimumpermonth[$actualmonth])) ? $minimumpermonth[$actualmonth] : 0;
		
		var_dump($minimumpermonth);
		var_dump($failed);
		
		
		foreach($sports as $sportname => $quantity) {
			$sportschart[] = array(
					$sportname,
					$quantity
			);
		}
		
		$situation = ($failed) ? get_string("failed", "local_deportes") : get_string("pending", "local_deportes");
		
		$headingtable = new html_table("p");
		$headingtable->data[] = array(
				html_writer::tag('h4',get_string('totalattendance','local_deportes').": ".$totalattendance),
				html_writer::tag('h4',get_string('situation','local_deportes').": ".$situation),
				html_writer::tag('h4',get_string('minimumattendance','local_deportes').": ".$minimumrequired),
				html_writer::tag('h4',get_string('monthattendance','local_deportes').": ".$monthlyattendance[$actualmonth])
		);
	}
	
	echo $OUTPUT->header();
	echo $OUTPUT->heading("DeportesUAI");
	echo $OUTPUT->tabtree(deportes_tabs(), "attendance");
	if(!(count($result->asistencias->asistencias)>0)){
		echo html_writer::div(get_string("noattendance","local_deportes"),"alert alert-info", array("role"=>"alert"));
	}else{
		echo html_writer::tag('div','', array('id' => 'calendar_basic', 'style' => 'overflow-x: auto; height:30vh;'));
		echo html_writer::tag('div','', array('id' => 'sports_chart', 'style' => 'height:30vh;'));
		
		echo html_writer::table($headingtable);
		echo html_writer::table($table);
	}
	echo $OUTPUT->footer();
	?>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript">
	google.charts.load("current", {packages:["calendar", "corechart", "bar"]});
	google.charts.setOnLoadCallback(drawChart);
	google.charts.setOnLoadCallback(drawMaterial);
	
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
			title: "<?php echo get_string('attendance','local_deportes')?>",
			width: '920',
			height: '200',
			colorAxis:{
				minValue:-1,
				maxValue:1
			},
			calendar: {
			      dayOfWeekRightSpace: 10,
			      daysOfWeek: '<?php echo get_string('calendarchartweek', 'local_deportes');?>',
			    }
		};
		
		chart.draw(dataTable, options);
	}

	function drawMaterial() {
		var sportsData = <?php echo json_encode($sportschart); ?>
		
      	var data = new google.visualization.DataTable();
      	data.addColumn('string', 'Deporte');
      	data.addColumn('number', 'Asistencias');

		data.addRows(sportsData);

      	var options = {
        	title: 'Motivation and Energy Level Throughout the Day',
        	bar: {groupWidth: '20'},
        	hAxis: {
          		title: 'Time of Day',
          		viewWindow: {
            		min: [7, 30, 0],
            		max: [17, 30, 0]
          		}
        	},
        	vAxis: {
          		title: 'Rating (scale of 1-10)'
        	}
      	};

      var materialChart = new google.charts.Bar(document.getElementById('sports_chart'));
      materialChart.draw(data, options);
    }
	</script>
<?php 
}else{
	print_error(get_string("notallowed", "local_deportes"));
}
?>