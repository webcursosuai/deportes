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
 * @package local
 * @subpackage deportes
 * @copyright 2017 Mihail Pozarski (mpozarski944@gmail.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(dirname(__FILE__))))."/config.php");
require_once ($CFG->libdir . "/formslib.php");
require_once($CFG->dirroot.'/course/moodleform_mod.php');

class sports_module_form extends moodleform {

	public function definition(){
		global $DB;
		
		$mform = $this->_form;
		
		$mform->addElement('header', 'module_form', get_string('module_form', 'local_deportes'));
		$mform->addElement('text', 'name', get_string('module_name','local_deportes'));
		$mform->setType( 'name', PARAM_TEXT);
		$mform->addHelpButton('name', 'module_name', 'local_deportes');
		$mform->addElement('text', 'initialhour', get_string('module_initialhour', 'local_deportes'));
		$mform->setType( 'initialhour', PARAM_TEXT);
		$mform->addHelpButton('initialhour', 'module_initialhour', 'local_deportes');
		$mform->addElement('text', 'endhour', get_string('module_endhour', 'local_deportes'));
		$mform->setType( 'endhour', PARAM_TEXT);
		$mform->addHelpButton('endhour', 'module_endhour', 'local_deportes');
		$mform->addElement('select', 'type', get_string('module_type', 'local_deportes'), array(get_string('fitness','local_deportes'),get_string('outdoor','local_deportes')));
		$mform->setType( 'type', PARAM_TEXT);
		$mform->addHelpButton('type', 'module_type', 'local_deportes');
		$mform->addElement("hidden", "action", "add");
		$mform->setType("action", PARAM_TEXT);
		
		$this->add_action_buttons(true);
	}
	public function validation($data, $files){
		
		$errors = array();
		
		return $errors;
		
	}
}