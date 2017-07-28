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
* @copyright  2017	Javier Gonzalez (javiergonzalez@alumnos.uai.cl)
* @copyright  2017  Jorge Cabané (jcabane@alumnos.uai.cl) 
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

require_once(dirname(dirname(dirname(__FILE__))) . "/config.php");
require_once($CFG->dirroot."/local/deportes/locallib.php");
require_once ($CFG->libdir . '/tablelib.php');
require_once($CFG->libdir . "/formslib.php");
global $CFG, $DB, $OUTPUT, $PAGE;

// User must be logged in.
require_login();
if (isguestuser()) {
	die();
}

$context = context_system::instance();

$url = new moodle_url("/local/deportes/schedule.php");
$PAGE->navbar->add(get_string("nav_title", "local_deportes"));
$PAGE->navbar->add(get_string("schedule", "local_deportes"), $url);
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout("standard");
$PAGE->set_title(get_string("page_title", "local_deportes"));
$PAGE->set_heading(get_string("page_heading", "local_deportes"));
$PAGE->requires->jquery();
$PAGE->requires->jquery_plugin ( 'ui' );
$PAGE->requires->jquery_plugin ( 'ui-css' );
/*
$fs = get_file_storage();
if ($fs->file_exists($context->id,"local_deportes", "draft", 0, "/", "fitness.pdf")) {
	var_dump($fs);
}
$scheduleurl = moodle_url::make_pluginfile_url($context->id, "local_deportes", "draft", 0, "/", "fitness.pdf");
var_dump($scheduleurl);
$viewerpdf = html_writer::nonempty_tag("embed", " ", array(
		"src" => $scheduleurl,
		"style" => "height:75vh; width:60vw"
));
*/

echo $OUTPUT->header();
echo $OUTPUT->heading("DeportesUAI");
echo $OUTPUT->tabtree(deportes_tabs(), "schedule");
//echo $viewerpdf;

echo "<img src='img/fitness.jpg'>";
echo "<img src='img/outdoors.jpg'>";

echo $OUTPUT->footer();
/*
 * ************************************************************************************************************************
 * 
$tablefitness = new flexible_table("Sports");
$tablefitness->define_baseurl($url);
$tablefitness->define_headers(array(
		"Hora",
		"Lunes",
		"Martes",
		"Miercoles",
		"Jueves",
		"Viernes"
));
$tablefitness->define_columns(array(
		"starttime",
		"lunes",
		"martes",
		"miercoles",
		"jueves",
		"viernes"
));
$tablefitness->sortable(true, "starttime");
$tablefitness->no_sorting("lunes");
$tablefitness->no_sorting("martes");
$tablefitness->no_sorting("miercoles");
$tablefitness->no_sorting("jueves");
$tablefitness->no_sorting("viernes");
$tablefitness->pageable(true);
$tablefitness->setup();
$orderbyfit = "ORDER BY m.starttime";
if ($tablefitness->get_sql_sort()){
	$orderbyfit = 'ORDER BY m.'. $tablefitness->get_sql_sort();
}

$getschedulefitness = deportes_get_schedule($orderbyfit, 1);
$nofsports = count($getschedulefitness);
if ($nofsports>0){
	$array = deportes_arrayforschedule($getschedulefitness, $nofsports);
	foreach($array as $modulararray){
		$tablefitness->add_data(array(
				"<span>".$modulararray[0]."</span>",
				"<span class='fitness'>".$modulararray[1]."</span>",
				"<span class='fitness'>".$modulararray[2]."</span>",
				"<span class='fitness'>".$modulararray[3]."</span>",
				"<span class='fitness'>".$modulararray[4]."</span>",
				"<span class='fitness'>".$modulararray[5]."</span>",
		));
	}
	echo "<html>";
	echo "<head><B>Horario Fitness</B></head>";
	$tablefitness->finish_html();
}
else{
	print "Table is empty";
}
echo "</div>";
echo "<form action='' id='papa'>";
echo "<input type = 'checkbox' name = 'checkfitness' value = 'Body Pump'>Body Pump <br>";
echo "<input type = 'checkbox' name = 'checkfitness' value = 'Body Attack'>Body Attack <br>";
echo "<input type = 'checkbox' name = 'checkfitness' value = 'Fitball'>Fitball <br>";
echo "<input type = 'checkbox' name = 'checkfitness' value = 'Baile Entretenido'>Baile Entretenido <br>";
echo "<input type = 'checkbox' name = 'checkfitness' value = 'RPM'>RPM <br>";
echo "<input type = 'checkbox' name = 'checkfitness' value = 'Yoga'>Yoga <br>";
echo "<input type = 'checkbox' name = 'checkfitness' value = 'Dance Pad'>Dance Pad <br>";
echo "<input type = 'checkbox' name = 'checkfitness' value = 'Body Combat'>Body Combat <br>";
echo "<input type = 'checkbox' name = 'checkfitness' value = 'Body Step'>Body Step <br>";
echo "<input type = 'checkbox' name = 'checkfitness' value = 'Power Jump'>Power Jump <br>";
echo "<input type = 'checkbox' name = 'checkfitness' value = 'Body Balance'>Body Balance <br>";
echo "</form>";
echo "</body>";


$tableoutdoors= new flexible_table("Outdoors");
$tableoutdoors->define_baseurl($url);
$tableoutdoors->define_headers(array(
		"Hora",
		"Lunes",
		"Martes",
		"Miercoles",
		"Jueves",
		"Viernes"
));
$tableoutdoors->define_columns(array(
		"starttime",
		"lunes",
		"martes",
		"miercoles",
		"jueves",
		"viernes"
));
$tableoutdoors->sortable(true, "starttime");
$tableoutdoors->no_sorting("lunes");
$tableoutdoors->no_sorting("martes");
$tableoutdoors->no_sorting("miercoles");
$tableoutdoors->no_sorting("jueves");
$tableoutdoors->no_sorting("viernes");
$tableoutdoors->pageable(true);
$tableoutdoors->setup();
$orderbyout = "ORDER BY m.starttime";
if ($tableoutdoors->get_sql_sort()){
	$orderbyout = 'ORDER BY m.'. $tableoutdoors->get_sql_sort();
}

$getscheduleoutdoors = deportes_get_schedule($orderbyout, 0);
$nofsports = count($getscheduleoutdoors);
if ($nofsports>0){
	$array = deportes_arrayforschedule($getscheduleoutdoors, $nofsports);
	foreach($array as $modulararray){
		$tableoutdoors->add_data(array(
				"<span>".$modulararray[0]."</span>",
				"<span class='outdoors'>".$modulararray[1]."</span>",
				"<span class='outdoors'>".$modulararray[2]."</span>",
				"<span class='outdoors'>".$modulararray[3]."</span>",
				"<span class='outdoors'>".$modulararray[4]."</span>",
				"<span class='outdoors'>".$modulararray[5]."</span>"
		));
	}
	
	
	
	echo "<html>";
	echo "<head><B><br><br>Horario Outdoors</B></head>";
	echo "<style>";
	$arraysports = deportes_getsports(0);
	$form ='';
	foreach ($arraysports as $sports){
		$form.= html_writer::empty_tag('input', array('type'=>'checkbox', 'name'=>'checkoutdoors', 'value'=>$sports->name)).$sports->name.'<br>';
		echo ".$sports->name{
			color : white;
			font-weight:bold;
			background-color:$sports->backgroundcolor}";
	}
	echo "</style>";
	$tableoutdoors->finish_html();
	echo $form;
}
else{
	print "Table is empty";
}
echo $OUTPUT->footer();
?>

<script>

$(document).ready(function(){
	 var td =$("span[class='fitness']");
	$.each(td, function( index, value ) {
			
			if ($(this).text() === 'Yoga'){
				$(this).parent().css({'font-weight':'bold',
					'color':'white',
					'background-color':'green'});
				}
			if ($(this).text() === 'Body Pump'){
				$(this).parent().css({'font-weight':'bold',
					'background-color':'DeepSkyBlue'});
				}
			if ($(this).text() === 'RPM'){
				$(this).parent().css({'font-weight':'bold',
					'color':'White',
					'background-color':'OrangeRed'});
				}
			if ($(this).text() === 'Body Attack'){
				$(this).parent().css({'font-weight':'bold',
					'color':'White',
					'background-color':'HotPink'});
				}
			if ($(this).text() === 'Fitball'){
				$(this).parent().css({'font-weight':'bold',
					'color':'Black',
					'background-color':'LimeGreen'});
				}
			if ($(this).text() === 'Baile Entretenido'){
				$(this).parent().css({'font-weight':'bold',
					'color':'White',
					'background-color':'DarkOrange'});
				}
			if ($(this).text() === 'Body Combat'){
				$(this).parent().css({'font-weight':'bold',
					'color':'Black',
					'background-color':'DarkSalmon'});
				}
			if ($(this).text() === 'Dance Pad'){
				$(this).parent().css({'font-weight':'bold',
					'color':'White',
					'background-color':'Teal'});
				}
			if ($(this).text() === 'Body Step'){
				$(this).parent().css({'font-weight':'bold',
					'color':'Black',
					'background-color':'Yellow'});
				}
			if ($(this).text() === 'Body Balance'){
				$(this).parent().css({'font-weight':'bold',
					'color':'White',
					'background-color':'Magenta'});
				}
			if ($(this).text() === 'Power Jump'){
				$(this).parent().css({'font-weight':'bold',
					'color':'White',
					'background-color':'DarkViolet'});
				}
			
        
	});

		
});


$(':checkbox').change(function() {
	var td =$("span[class='fitness']");
	td.hide();
	$.each($(':checkbox'), function( index, value ) {
		var valor = $(this).val();
        if (this.checked) {
			$.each(td, function( index, value ) {
  				if($(this).text() === valor ){
					$(this).show();
					$(this).parent().show();
                } 
			});	
        }
	});	
});
$(':checkbox').change(function() {
	var td =$("span[class='outdoors']");
	td.hide();
	td.parent().css({'background-color':'white'});
	$.each($(':checkbox'), function( index, value ) {
		var valor = $(this).val();
        if (this.checked) {
			$.each(td, function( index, value ) {
  				if($(this).text() === valor ){
					$(this).show();
                }
			});	
        }
	});	
});


</script>
*/