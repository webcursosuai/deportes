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

defined("MOODLE_INTERNAL") || die();
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/config.php");
require_once(dirname(dirname(__FILE__)) . "/locallib.php");
require_once($CFG->libdir . "/formslib.php");

class deportes_filepicker extends moodleform{
	public function definition(){
		global $DB, $CFG;
		$mform = $this->_form;
		$arraysportstype = array(
				0 => "Seleccione un tipo de deporte",
				1 => "Outdoors",
				2 => "Fitness"
		);
		$mform->addElement("filepicker", "userfile", "Subir", null,
				array("maxbytes" => 5000000, "accepted_types" => array("*.pdf")));
		$mform->setType("userfile", PARAM_FILE);
		
		$mform->addHelpButton( "type", "sport_fileupload", "local_deportes");
		
		$mform->addElement("select", "type", "Type of Sport", $arraysportstype);
		$mform->setType("type", PARAM_INT);
		$mform->addHelpButton( "type", "sport_type", "local_deportes");
		
		
		$mform->addElement("hidden", "action", "addfile");
		$mform->setType("action", PARAM_TEXT);
		$this->add_action_buttons(true, ("uploadfile, local_deportes"));//crear lang
	}
	public function validation($data, $files){
		$errors = array();
		
		$userfile = $data["userfile"];
		$type = $data["type"];
		
		if (empty($userfile)){
			$errors["userfile"] = get_string("mustuploadfile", "local_deportes"); //lang
		}/*
		$filename = $addform->get_new_filename("userfile");
		$explodename = explode(".",$userfile->get_new_filename("userfile"););
		$countnamefile= count($explodename);
		$extension = $explodename[$countnamefile-1];
		*/
		
		if($type != 1 && $type != 2){
			$errors["type"] = "Qué tipo de deporte es?";//lang
		}
		return $errors;
	}
}
