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
function deportes_get_modules_fitness($array){
	$nofmodules = count($array);
	$keys = array_keys($array);
	for ($i=0;$i<$nofmodules;$i++){
		if (array_values($array)[$i][0] == '1'){
			$hora = '8:15 - 9:15';
			$array[$keys[$i]][0] = $hora;
		}
		if (array_values($array)[$i][0] == '2'){
			$hora = '10:10 - 11:10';
			$array[$keys[$i]][0] = $hora;
		}
		if (array_values($array)[$i][0] == '3'){
			$hora = '11:40 - 12:40';
			$array[$keys[$i]][0] = $hora;
		}
		if (array_values($array)[$i][0] == '4'){
			$hora = '13:10 - 14:10';
			$array[$keys[$i]][0] = $hora;
		}
		if (array_values($array)[$i][0] == '5'){
			$hora = '15:10 - 16:10';
			$array[$keys[$i]][0] = $hora;
		}
		if (array_values($array)[$i][0] == '6'){
			$hora = '16:40 - 17:40';
			$array[$keys[$i]][0] = $hora;
		}
		if (array_values($array)[$i][0] == '1'){
			$hora = '18:10 - 19:10';
			$array[$keys[$i]][0] = $hora;
		}
	}
	return $array;
}