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
* @copyright  2017 Mihail Pozarski (mpozarski944@gmail.com)				
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
defined('MOODLE_INTERNAL') || die;
if ($hassiteconfig) {
	
	$settings = new admin_settingpage('local_deportes', 'Sports');
	
	$ADMIN->add('localplugins', $settings);
	$settings->add(
			new admin_setting_configtext(
					"deportes_token",
					get_string("token", "local_deportes"),
					get_string("tokendesc", "local_deportes"),
					"",
					PARAM_ALPHANUM
	));	
	$settings->add(
			new admin_setting_configtext(
					"deportes_urlasistenciasalumno",
					get_string("urlasistenciasalumno", "local_deportes"),
					get_string("urlasistenciasalumnodesc", "local_deportes"),
					"",
					PARAM_URL
	));	
	$settings->add(
			new admin_setting_configtext(
					"deportes_urldeportesalumno",
					get_string("urldeportesalumno", "local_deportes"),
					get_string("urldeportesalumnodesc", "local_deportes"),
					"",
					PARAM_URL
					));
	$settings->add(
			new admin_setting_configtext(
					"deportes_emailextension",
					get_string("emailextension", "local_deportes"),
					get_string("emailextensiondesc", "local_deportes"),
					"alumnos.uai.cl",
					PARAM_TEXT
					));
	
	$months = array(
			1 => get_string("Jan", "local_deportes"),
			2 => get_string("Feb", "local_deportes"),
			3 => get_string("Mar", "local_deportes"),
			4 => get_string("Apr", "local_deportes"),
			5 => get_string("May", "local_deportes"),
			6 => get_string("Jun", "local_deportes"),
			7 => get_string("Jul", "local_deportes"),
			8 => get_string("Aug", "local_deportes"),
			9 => get_string("Sep", "local_deportes"),
			10 => get_string("Oct", "local_deportes"),
			11 => get_string("Nov", "local_deportes"),
			12 => get_string("Dec", "local_deportes")
	);
	
	$settings->add(
			new admin_setting_configselect(
					"deportes_startmonth",
					get_string("startmonth", "local_deportes"),
					get_string("startmonthdesc", "local_deportes"),
					3,
					$months
					));
	$settings->add(
			new admin_setting_configselect(
					"deportes_endmonth",
					get_string("endmonth", "local_deportes"),
					get_string("endmonthdesc", "local_deportes"),
					6,
					$months
					));
	
	$days = array();
	for($day = 0; $day < 32; $day++){
		$days[$day] = $day;
	}
	
	$settings->add(
			new admin_setting_configselect(
					"deportes_startday",
					get_string("startday", "local_deportes"),
					get_string("startdaydesc", "local_deportes"),
					1,
					$days
					));
	$settings->add(
			new admin_setting_configselect(
					"deportes_endday",
					get_string("endday", "local_deportes"),
					get_string("enddaydesc", "local_deportes"),
					30,
					$days
					));
	$settings->add(
			new admin_setting_configtext(
					"deportes_courseid",
					get_string("courseid", "local_deportes"),
					get_string("courseiddesc", "local_deportes"),
					0,
					PARAM_INT
					));	
}