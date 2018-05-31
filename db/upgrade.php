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
 * This file keeps track of upgrades to the evaluaciones block
 *
 * Sometimes, changes between versions involve alterations to database structures
 * and other major things that may break installations.
 *
 * The upgrade function in this file will attempt to perform all the necessary
 * actions to upgrade your older installation to the current version.
 *
 * If there's something it cannot do itself, it will tell you what you need to do.
 *
 * The commands in here will all be database-neutral, using the methods of
 * database_manager class
 *
 * Please do not forget to use upgrade_set_timeout()
 * before any action that may take longer time to finish.
 *
 * @package    local
 * @subpackage facebook
 * @copyright  2018 Mark Michaelsen (mmichaelsen678@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
   
/**
 *
 * @param int $oldversion
 * @param object $block
 */
   
   
function xmldb_local_deportes_upgrade($oldversion) {
	global $CFG, $DB;
   
   	$dbman = $DB->get_manager();
   	
   	if ($oldversion < 2018012301) {
   		
   		// Define table deportes_files to be created.
   		$table = new xmldb_table('deportes_files');
   		
   		// Adding fields to table deportes_files.
   		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
   		$table->add_field('name', XMLDB_TYPE_CHAR, '20', null, null, null, null);
   		$table->add_field('type', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
   		$table->add_field('uploaddate', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
   		$table->add_field('iduser', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
   		
   		// Adding keys to table deportes_files.
   		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
   		$table->add_key('iduser', XMLDB_KEY_FOREIGN, array('iduser'), 'user', array('id'));
   		
   		// Conditionally launch create table for deportes_files.
   		if (!$dbman->table_exists($table)) {
   			$dbman->create_table($table);
   		}
   		
   		// Deportes savepoint reached.
   		upgrade_plugin_savepoint(true, 2018012301, 'local', 'deportes');
   	}
   	
   	if ($oldversion < 2018012302) {
   		
   		// Changing precision of field name on table deportes_files to (150).
   		$table = new xmldb_table('deportes_files');
   		$field = new xmldb_field('name', XMLDB_TYPE_CHAR, '150', null, null, null, null, 'id');
   		
   		// Launch change of precision for field name.
   		$dbman->change_field_precision($table, $field);
   		
   		// Deportes savepoint reached.
   		upgrade_plugin_savepoint(true, 2018012302, 'local', 'deportes');
   	}
   	
   	if ($oldversion < 2018053101) {
   	    
   	    // Define table deportes_config to be created.
   	    $table = new xmldb_table('deportes_config');
   	    
   	    // Adding fields to table deportes_config.
   	    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
   	    $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
   	    $table->add_field('value', XMLDB_TYPE_TEXT, null, null, null, null, null);
   	    
   	    // Adding keys to table deportes_config.
   	    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
   	    
   	    // Conditionally launch create table for deportes_config.
   	    if (!$dbman->table_exists($table)) {
   	        $dbman->create_table($table);
   	    }
   	    
   	    // Deportes savepoint reached.
   	    upgrade_plugin_savepoint(true, 2018053101, 'local', 'deportes');
   	}
}
   	