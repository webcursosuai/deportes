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
}