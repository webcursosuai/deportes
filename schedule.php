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

echo $OUTPUT->header();
echo $OUTPUT->heading("DeportesUAI");
echo $OUTPUT->tabtree(deportes_tabs(), "schedule");

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
		"module",
		"lunes",
		"martes",
		"miercoles",
		"jueves",
		"viernes"
));
$tablefitness->sortable(true, "module");
$tablefitness->no_sorting("lunes");
$tablefitness->no_sorting("martes");
$tablefitness->no_sorting("miercoles");
$tablefitness->no_sorting("jueves");
$tablefitness->no_sorting("viernes");
$tablefitness->pageable(true);
$tablefitness->setup();
$orderbyfit = "ORDER BY module";
if ($tablefitness->get_sql_sort()){
	$orderbyfit = 'ORDER BY '. $tablefitness->get_sql_sort();
}

$getschedulefitness = deportes_get_schedule($orderbyfit, 1);

/*
$nofsports = count($getschedulefitness);
$counterofsports=0;
$module;
$array = array();
$modulearray = array("","","","","","");
for ($counterofsports = 0; $counterofsports < $nofsports; $counterofsports++){
	$module = array_values($getschedulefitness)[$counterofsports]->module;
	$modulearray[0] = $module;
	if ($modulearray[array_values($getschedulefitness)[$counterofsports]->day] != ""){
/*		$temporaryarray = array();
		$temporaryarray[] = $modulearray[array_values($getschedulefitness)[$counterofsports]->day];
		$temporaryarray[count($modulearray[array_values($getschedulefitness)[$counterofsports]->day])] = array_values($getschedulefitness)[$counterofsports]->name;
		$modulearray[array_values($getschedulefitness)[$counterofsports]->day] = $temporaryarray;
	
		$modulearray[array_values($getschedulefitness)[$counterofsports]->day] = $modulearray[array_values($getschedulefitness)[$counterofsports]->day]."<br>".array_values($getschedulefitness)[$counterofsports]->name;
	}
	else {
		$modulearray[array_values($getschedulefitness)[$counterofsports]->day] = array_values($getschedulefitness)[$counterofsports]->name;
	}
	if ($counterofsports+1 == $nofsports){
		$array[count($array)] = $modulearray;
	}
	else if (array_values($getschedulefitness)[$counterofsports+1]->module != $module){
		$array[count($array)] = $modulearray;
		$modulearray = array("","","","","","");
	}
}
*/
$nofsports = count($getschedulefitness);
$array = deportes_arrayforschedule($getschedulefitness, $nofsports);
$array = deportes_get_modules_fitness($array);
foreach($array as $modulararray){
	$tablefitness->add_data(array(
			"<span>".$modulararray[0]."</span>",
			"<span class='fitness'>".$modulararray[1]."</span>",
			"<span class='fitness'>".$modulararray[2]."</span>",
			"<span class='fitness'>".$modulararray[3]."</span>",
			"<span class='fitness'>".$modulararray[4]."</span>",
			"<span class='fitness'>".$modulararray[5]."</span>"
	));
}
echo "<html>";
echo "<head><B>Horario Fitness</B></head>";
echo "<body>";
echo "<div id='fitnesstable'>";
if ($nofsports>0){
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
		"module",
		"lunes",
		"martes",
		"miercoles",
		"jueves",
		"viernes"
));
$tableoutdoors->sortable(true, "module");
$tableoutdoors->no_sorting("lunes");
$tableoutdoors->no_sorting("martes");
$tableoutdoors->no_sorting("miercoles");
$tableoutdoors->no_sorting("jueves");
$tableoutdoors->no_sorting("viernes");
$tableoutdoors->pageable(true);
$tableoutdoors->setup();
$orderbyout = "ORDER BY module";
if ($tableoutdoors->get_sql_sort()){
	$orderbyout = 'ORDER BY '. $tablefitness->get_sql_sort();
}

$getscheduleoutdoors = deportes_get_schedule($orderbyout, 0);
$nofsports = count($getscheduleoutdoors);
$array = deportes_arrayforschedule($getscheduleoutdoors, $nofsports);
$array = deportes_get_modules_outdoors($array);
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
echo "<body>";
echo "<div id='outdoorstable'>";
if ($nofsports>0){
	$tableoutdoors->finish_html();
}
else{
	print "Table is empty";
}
echo "</div>";
echo "<form action='' id='out'>";
echo "<input type = 'checkbox' name = 'checkoutdoors' value = 'Futbolito'>Futbolito<br>";
echo "<input type = 'checkbox' name = 'checkoutdoors' value = 'Cross Training'>Cross Training<br>";
echo "<input type = 'checkbox' name = 'checkoutdoors' value = 'Padel'>Padel<br>";
echo "<input type = 'checkbox' name = 'checkoutdoors' value = 'Almuerzo'>Almuerzo<br>";
echo "<input type = 'checkbox' name = 'checkoutdoors' value = 'Futbol Tennis'>Futbol Tennis <br>";
echo "<input type = 'checkbox' name = 'checkoutdoors' value = 'Treking'>Treking<br>";
echo "<input type = 'checkbox' name = 'checkoutdoors' value = 'Futbolito Mujeres'>Futbolito Mujeres<br>";
echo "<input type = 'checkbox' name = 'checkoutdoors' value = 'Campeonato UAI'>Campeonato UAI<br>";
echo "</form>";
echo "</body>";





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

	var td =$("span[class='outdoors']");
	$.each(td, function( index, value ) {
			
			if ($(this).text() === 'Futbolito'){
				$(this).parent().css({'font-weight':'bold',
					'color':'black',
					'background-color':'yellow'});
				}
			if ($(this).text() === 'Padel'){
				$(this).parent().css({'font-weight':'bold',
					'color':'white',
					'background-color':'SteelBlue'});
				}
			if ($(this).text() === 'Cross Training'){
				$(this).parent().css({'font-weight':'bold',
					'color':'White',
					'background-color':'Turqoise'});
				}
			if ($(this).text() === 'Basquetbol'){
				$(this).parent().css({'font-weight':'bold',
					'color':'Blakc',
					'background-color':'green'});
				}
			if ($(this).text() === 'Futbol Tennis'){
				$(this).parent().css({'font-weight':'bold',
					'color':'White',
					'background-color':'DarkOrange'});
				}
			if ($(this).text() === 'Almuerzo'){
				$(this).parent().css({'font-weight':'bold',
					'color':'White',
					'background-color':'MediumAquamarine'});
				}
			if ($(this).text() === 'Campeonato UAI'){
				$(this).parent().css({'font-weight':'bold',
					'color':'Black',
					'background-color':'Grey'});
				}
			if ($(this).text() === 'Treking'){
				$(this).parent().css({'font-weight':'bold',
					'color':'White',
					'background-color':'SaddleBrown'});
				}
			if ($(this).text() === 'Futbolito Mujeres'){
				$(this).parent().css({'font-weight':'bold',
					'color':'Black',
					'background-color':'LightCoral'});
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


</script>
