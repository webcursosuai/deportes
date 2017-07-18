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
/*
* @package    local
* @subpackage deportes
* @copyright  2017 Javier Gonzalez <javiergonzalez@alumnos.uai.cl>
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
function xmldb_local_sync_upgrade($oldversion) {
	global $CFG, $DB;
	$dbman = $DB->get_manager();
	if ($oldversion < 2017071801) {
	
		// Define field day to be added to sports_schedule.
		$table = new xmldb_table('sports_schedule');
		$field = new xmldb_field('day', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'idmodules');
	
		// Conditionally launch add field day.
		if (!$dbman->field_exists($table, $field)) {
			$dbman->add_field($table, $field);
		}
	
		// Deportes savepoint reached.
		upgrade_plugin_savepoint(true, 2017071801, 'local', 'deportes');
	}
	
}