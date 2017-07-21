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
require_once($CFG->libdir . "/formslib.php");


class deportes_add_form_sports extends moodleform {
	public function definition() {
		$mform = $this->_form;
		$arraysportstype = array(
				3 => "Seleccione un tipo de deporte",
				0 => "Outdoors",
				1 => "Fitness"
		);
		$mform->addElement("text", "name", "Name");
		$mform->setType( "name", PARAM_TEXT);
		$mform->addHelpButton("name", "sports_name", "local_deportes");
		$mform->addElement("select", "type", "Type of Sport", $arraysportstype);
		$mform->setType("type", PARAM_INT);
		$mform->addHelpButton( "type", "sport_type", "local_deportes");

		$mform->addElement("hidden", "action", "add");
		$mform->setType("action", PARAM_TEXT);

		$this->add_action_buttons(true, "Agregar Deporte");
	}

	public function validation($data, $files){
		global $DB;
		$errors = array();

		$name = $data["name"];
		$type = $data["type"];

		$query = "Select id
				FROM {sports_classes}
				WHERE name = ?";
		
		if(empty($name)){
			$errors["name"] = "Qué deporte desea agregar?";//lang
		}
		else if(is_numeric($name)){
			$errors["name"] = "Ese nombre no es valido";//lang
		}
		else if($DB->get_record_sql($query, array($name))){
			$errors["name"] = "Ese deporte ya existe";//lang
		}
		if($type != 0 && $type != 1){
			$errors["type"] = "Qué tipo de deporte es?";//lang
		}
		return $errors;
	}
}
class deportes_edit_sportsform extends moodleform{
	function definition(){

		$mform = $this->_form;
		$instance = $this->_customdata;
		$edition = $instance["edition"];
		$mform->setType("edition", PARAM_TEXT);
		$arraysportstype = array(
				3 => "Seleccione un tipo de deporte",
				0 => "Outdoors",
				1 => "Fitness"
		);
		$mform->addElement("text", "name", "Name");
		$mform->setType( "name", PARAM_TEXT);
		$mform->addElement("select", "type", "Type of Sport", $arraysportstype);
		$mform->setType("type", PARAM_INT);

		$mform->addElement("hidden", "action", "edit");
		$mform->addElement("hidden", "edition", $edition);
		$mform->setType("action", PARAM_TEXT);

		$this->add_action_buttons(true, "Save");
	}

	public function validation($data, $files){
		$errors = array();

		$name = $data["name"];
		$type = $data["type"];
		$date = $data["date"];

		$query = "Select id
				FROM {sports_classes}
				WHERE name = ?";

		if(empty($name)){
			$errors["name"] = "Qué deporte desea editar?";//lang
		}
		else if(is_numeric($name)){
			$errors["name"] = "Ese nombre no es valido";//lang
		}
		else if($DB->get_record_sql($query, array($name))){
			$errors["name"] = "Ese deporte ya existe";//lang
		}
		if($type != 0 && $type != 1){
			$errors["type"] = "Qué tipo de deporte es?";
		}
		return $errors;
	}
}