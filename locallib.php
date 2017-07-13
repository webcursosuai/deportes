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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.


/**
 * @package    local
 * @subpackage deportes
 * @copyright  2017	Mark Michaelsen (mmichaelsen678@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function deportes_tabs() {
	$tabs = array();
	// Create sync
	$tabs[] = new tabobject(
			"attendance",
			new moodle_url("/local/deportes/attendance.php"),
			get_string("attendance", "local_deportes")
			);
	// Records.
	$tabs[] = new tabobject(
			"schedule",
			new moodle_url("/local/deportes/schedule.php"),
			get_string("schedule", "local_deportes")
			);
	// History
	$tabs[] = new tabobject(
			"reserve",
			new moodle_url("/local/deportes/reserve.php"),
			get_string("reserve", "local_deportes")
			);
	return $tabs;
}
function deportes_get_schedule($orderby, $type){
	global $DB;
	
	$query = " SELECT id,
	name,
	day,
	module
	FROM {deportes}
	WHERE type = ?
	$orderby";
	$getschedule = $DB->get_records_sql($query, array($type)); 
	return $getschedule;
}
function deportes_get_modules_fitness($array){
	$nofmodules = count($array);
	$keys = array_keys($array);
	for ($counterofmodules=0;$counterofmodules<$nofmodules;$counterofmodules++){
		if (array_values($array)[$counterofmodules][0] == '1'){
			$hora = '8:15 - 9:15';
			$array[$keys[$counterofmodules]][0] = $hora;
		}
		if (array_values($array)[$counterofmodules][0] == '2'){
			$hora = '10:10 - 11:10';
			$array[$keys[$counterofmodules]][0] = $hora;
		}
		if (array_values($array)[$counterofmodules][0] == '3'){
			$hora = '11:40 - 12:40';
			$array[$keys[$counterofmodules]][0] = $hora;
		}
		if (array_values($array)[$counterofmodules][0] == '4'){
			$hora = '13:10 - 14:10';
			$array[$keys[$counterofmodules]][0] = $hora;
		}
		if (array_values($array)[$counterofmodules][0] == '5'){
			$hora = '15:10 - 16:10';
			$array[$keys[$counterofmodules]][0] = $hora;
		}
		if (array_values($array)[$counterofmodules][0] == '6'){
			$hora = '16:40 - 17:40';
			$array[$keys[$counterofmodules]][0] = $hora;
		}
		if (array_values($array)[$counterofmodules][0] == '1'){
			$hora = '18:10 - 19:10';
			$array[$keys[$counterofmodules]][0] = $hora;
		}
	}
	return $array;
}
function deportes_arrayforschedule($getschedule, $nofsports){
	$counterofsports=0;
	$module;
	$array = array();
	$modulearray = array("","","","","","");
	for ($counterofsports = 0; $counterofsports < $nofsports; $counterofsports++){
		$module = array_values($getschedule)[$counterofsports]->module;
		$modulearray[0] = $module;
		if ($modulearray[array_values($getschedule)[$counterofsports]->day] != ""){
			/*		$temporaryarray = array();
			 $temporaryarray[] = $modulearray[array_values($getschedulefitness)[$counterofsports]->day];
			 $temporaryarray[count($modulearray[array_values($getschedulefitness)[$counterofsports]->day])] = array_values($getschedulefitness)[$counterofsports]->name;
			 $modulearray[array_values($getschedulefitness)[$counterofsports]->day] = $temporaryarray;
			 */
			$modulearray[array_values($getschedule)[$counterofsports]->day] = $modulearray[array_values($getschedule)[$counterofsports]->day]."<br>".array_values($getschedule)[$counterofsports]->name;
		}
		else {
			$modulearray[array_values($getschedule)[$counterofsports]->day] = array_values($getschedule)[$counterofsports]->name;
		}
		if ($counterofsports+1 == $nofsports){
			$array[count($array)] = $modulearray;
		}
		else if (array_values($getschedule)[$counterofsports+1]->module != $module){
			$array[count($array)] = $modulearray;
			$modulearray = array("","","","","","");
		}
	}
	return $array;
}
function deportes_get_modules_outdoors($array){
	$nofmodules = count($array);
	$keys = array_keys($array);
	for ($counterofmodules=0;$counterofmodules<$nofmodules;$counterofmodules++){
		if (array_values($array)[$counterofmodules][0] == '1'){
			$hora = '10:00 - 11:00';
			$array[$keys[$counterofmodules]][0] = $hora;
		}
		if (array_values($array)[$counterofmodules][0] == '2'){
			$hora = '11:30 - 12:30';
			$array[$keys[$counterofmodules]][0] = $hora;
		}
		if (array_values($array)[$counterofmodules][0] == '3'){
			$hora = '12:30 - 13:30';
			$array[$keys[$counterofmodules]][0] = $hora;
		}
		if (array_values($array)[$counterofmodules][0] == '4'){
			$hora = '13:30 - 14:30';
			$array[$keys[$counterofmodules]][0] = $hora;
		}
		if (array_values($array)[$counterofmodules][0] == '5'){
			$hora = '15:00 - 16:00';
			$array[$keys[$counterofmodules]][0] = $hora;
		}
		if (array_values($array)[$counterofmodules][0] == '6'){
			$hora = '16:40 - 17:40';
			$array[$keys[$counterofmodules]][0] = $hora;
		}
		if (array_values($array)[$counterofmodules][0] == '1'){
			$hora = '17:40 - 20:40';
			$array[$keys[$counterofmodules]][0] = $hora;
		}
	}
	return $array;
}