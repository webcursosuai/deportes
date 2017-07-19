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

class sports_addmodule_form extends moodleform {

	public function definition(){
		
		$mform = $this->_form;
		
		$mform->addElement('header', 'module_form', get_string('module_form', 'local_deportes'));
		$mform->addElement('text', 'name', get_string('module_name','local_deportes'));
		$mform->setType( 'name', PARAM_TEXT);
		$mform->addHelpButton('name', 'module_name', 'local_deportes');
		$mform->addElement('text', 'starttime', get_string('module_initialhour', 'local_deportes'));
		$mform->setType( 'starttime', PARAM_TEXT);
		$mform->addHelpButton('starttime', 'module_initialhour', 'local_deportes');
		$mform->addElement('text', 'endtime', get_string('module_endhour', 'local_deportes'));
		$mform->setType( 'endtime', PARAM_TEXT);
		$mform->addHelpButton('endtime', 'module_endhour', 'local_deportes');
		$mform->addElement('select', 'type', get_string('module_type', 'local_deportes'), get_string('outdoor','local_deportes'),array(get_string('fitness','local_deportes')));
		$mform->setType( 'type', PARAM_TEXT);
		$mform->addHelpButton('type', 'module_type', 'local_deportes');
		$mform->addElement("hidden", "action", "add");
		$mform->setType("action", PARAM_TEXT);
		
		$this->add_action_buttons(true);
	}
	public function validation($data, $files){
		global $DB;
		
		$errors = array();
		
		if (isset($data ["name"]) && ! empty($data ["name"]) && $data ["name"] != "" && $data ["name"] != null) {
			if (! $DB->get_record_select("sports_modules", "name = ?", array(trim($data ["name"])))) {
				if (! ctype_alnum($data['name'])) {
					$errors ['name'] = get_string('alphanumericplease', 'local_deportes');
				}
			}else{
				$errors ['name'] = get_string('alreadyexist', 'local_deportes');
			}
		}else{
			$errors ['name'] = get_string('required','local_deportes');
		}
		if (isset($data ["name"]) && ! empty($data ["name"]) && $data ["name"] != "" && $data ["name"] != null) {
			if(! preg_match('#^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#', $data['starttime'])){
				$errors ['starttime'] = get_string('hourformatplease','local_deportes');
			}
		}else{
			$errors ['starttime'] = get_string('required','local_deportes');
		}
		if (isset($data ["name"]) && ! empty($data ["name"]) && $data ["name"] != "" && $data ["name"] != null) {
			if(! preg_match('#^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#', $data['endtime'])){
				$errors ['endtime'] = get_string('hourformatplease','local_deportes');
			}
		}else{
			$errors ['endtime'] = get_string('required','local_deportes');
		}
		if(! (strtotime($data['starttime']) < strtotime($data['endtime']))){
			if(! isset($errors ['endtime']) && ! isset($errors ['starttime'])){
				$errors ['endtime'] = get_string('biggerthanstartime','local_deportes');
			}
		}
		
		return $errors;
		
	}
}
class sports_editmodule_form extends moodleform {
	public function definition(){
		global $DB;
		
		$mform = $this->_form;
		$instance = $this->_customdata;
		$editid = $instance["id"];
		
		$mform->addElement('header', 'module_form', get_string('module_form', 'local_deportes'));
		$mform->addElement('text', 'name', get_string('module_name','local_deportes'));
		$mform->setType( 'name', PARAM_TEXT);
		$mform->addHelpButton('name', 'module_name', 'local_deportes');
		$mform->addElement('text', 'starttime', get_string('module_initialhour', 'local_deportes'));
		$mform->setType( 'starttime', PARAM_TEXT);
		$mform->addHelpButton('starttime', 'module_initialhour', 'local_deportes');
		$mform->addElement('text', 'endtime', get_string('module_endhour', 'local_deportes'));
		$mform->setType( 'endtime', PARAM_TEXT);
		$mform->addHelpButton('endtime', 'module_endhour', 'local_deportes');
		$mform->addElement('select', 'type', get_string('module_type', 'local_deportes'), array(get_string('fitness','local_deportes'),get_string('outdoor','local_deportes')));
		$mform->setType( 'type', PARAM_TEXT);
		$mform->addHelpButton('type', 'module_type', 'local_deportes');
		$mform->addElement("hidden", "action", "edit");
		$mform->setType("action", PARAM_TEXT);
		$mform->addElement("hidden", "editid", $editid);
		$mform->setType("editid", PARAM_TEXT);
		$this->add_action_buttons(true);
	}
	public function validation($data, $files){
		global $DB;
		
		$errors = array();
		
		if (isset($data ["name"]) && ! empty($data ["name"]) && $data ["name"] != "" && $data ["name"] != null) {
			if (! $DB->get_record_select("sports_modules", "name = ? AND id != ".$data['editid'], array(trim($data ["name"])))) {
				if (! ctype_alnum($data['name'])) {
					$errors ['name'] = get_string('alphanumericplease', 'local_deportes');
				}
			}else{
				$errors ['name'] = get_string('alreadyexist', 'local_deportes');
			}
		}else{
			$errors ['name'] = get_string('required','local_deportes');
		}
		if (isset($data ["name"]) && ! empty($data ["name"]) && $data ["name"] != "" && $data ["name"] != null) {
			if(! preg_match('#^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#', $data['starttime'])){
				$errors ['starttime'] = get_string('hourformatplease','local_deportes');
			}
		}else{
			$errors ['starttime'] = get_string('required','local_deportes');
		}
		if (isset($data ["name"]) && ! empty($data ["name"]) && $data ["name"] != "" && $data ["name"] != null) {
			if(! preg_match('#^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#', $data['endtime'])){
				$errors ['endtime'] = get_string('hourformatplease','local_deportes');
			}
		}else{
			$errors ['endtime'] = get_string('required','local_deportes');
		}
		if(! (strtotime($data['starttime']) < strtotime($data['endtime']))){
			if(! isset($errors ['endtime']) && ! isset($errors ['starttime'])){
				$errors ['endtime'] = get_string('biggerthanstartime','local_deportes');
			}
		}
		
		return $errors;
		
	}
}