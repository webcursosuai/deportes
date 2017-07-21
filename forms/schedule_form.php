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
defined('MOODLE_INTERNAL') || die();
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once($CFG->libdir . "/formslib.php");


class deportes_schedule_form extends moodleform {
	public function definition() {
		global $DB;
		$mform = $this->_form;

		$instance = $this->_customdata;
		$type = $instance["type"];
		$mform->setType("type", PARAM_INT);

		$datasports = array();
		$getdatasports = $DB->get_records("sports_classes", array("type" => $type));
		foreach($getdatasports as $getsport){
			$datasports[$getsport->id] = $getsport->name;
		}

		$datamodules = array();
		$getmodulesquery = "SELECT id,
				CONCAT (name, ' -> ', starttime, ' - ',endtime) as name
				FROM {sports_modules}
				WHERE type = ?";
		$getdatamodules = $DB->get_records_sql($getmodulesquery, array("type" => $type));
		foreach($getdatamodules as $getmodule){
			$datamodules[$getmodule->id] = $getmodule->name;
		}

		$mform->addElement("select", "idsports", "Select a sport", $datasports);//lang


		$mform->addElement("select", "idmodules", "Select a module", $datamodules );//lang

		$arraydaysform = array();
		$arraydays = array(get_string('monday', 'local_deportes'), get_string('tuesday', 'local_deportes'), get_string('wednesday', 'local_deportes'), get_string('thursday', 'local_deportes'), get_string('friday', 'local_deportes'));
		for ($i = 1; $i<6; $i++){
			$arraydaysform[] = $mform->createElement('advcheckbox', $i,'',$arraydays[$i-1] );
		}


		$mform->addGroup($arraydaysform, 'day', "dia");

		$mform->addElement("hidden", "type", $type);
		$mform->setType("type", PARAM_INT);

		$mform->addElement("hidden", "action", "add");
		$mform->setType("action", PARAM_TEXT);

		$this->add_action_buttons(true, get_string('addtoschedule', 'local_deportes'));
	}

	public function validation($data, $files){
		global $DB;
		$errors = array();

		$idsports = $data["idsports"];
		$idmodules = $data["idmodules"];
		$day = $data["day"];
		$auxiliaryvariableday = 0;

		if(empty($idsports)){
			$errors["idsports"] = get_string("mustselectsport", "local_deportes");//Debe seleccionar un deporte
		}
		if(empty($idmodules)){
			$errors["idmodules"] = get_string("mustselectmodule", "local_deportes");//Debe seleccionar un modulo
		}
		foreach($day as $d){
			if ($d == 1){
				$auxiliaryvariableday++;
			}
		}
		if ($auxiliaryvariableday == 0){
			$errors["day"] = get_string("daymustbeselected", "local_deportes");//Se debe seleccionar al menos un dia
		}
		if ($auxiliaryvariableday > 0){
			for ($counterofdays = 1; $counterofdays<6; $counterofdays++){
				if ($day[$counterofdays] == 1){
					if($DB->get_record("sports_schedule", array("idsports" => $idsports, "idmodules" => $idmodules, "day" => $counterofdays))){
						if ($counterofdays == 1){
							if (isset($errors["day"])){
								$errors["day"].=get_string("dayalreadyexistsmonday", "local_deportes");//Ya existe este deporte para el lunes en este horario
							}
							else{
								$errors["day"] = get_string("dayalreadyexistsmonday", "local_deportes");//Ya existe este deporte para el lunes en este horario
							}
						}if ($counterofdays == 2){
							if (isset($errors["day"])){
								$errors["day"].=get_string("dayalreadyexiststuesday", "local_deportes");//Ya existe este deporte para el martes en este horario
							}
							else{
								$errors["day"] = get_string("dayalreadyexiststuesday", "local_deportes");//Ya existe este deporte para el martes en este horario
							}
						}if ($counterofdays == 3){
							if (isset($errors["day"])){
								$errors["day"].=get_string("dayalreadyexistswednesday", "local_deportes");//Ya existe este deporte para el miercoles en este horario
							}
							else{
								$errors["day"] = get_string("dayalreadyexistswednesday", "local_deportes");//Ya existe este deporte para el miercoles en este horario											}
							}
						}if ($counterofdays == 4){
							if (isset($errors["day"])){
								$errors["day"].=get_string("dayalreadyexiststhursday", "local_deportes");//Ya existe este deporte para el jueves en este horario
							}
							else{
								$errors["day"] = get_string("dayalreadyexiststhursday", "local_deportes");//Ya existe este deporte para el jueves en este horario
							}
						}if ($counterofdays == 5){
							if (isset($errors["day"])){
								$errors["day"].=get_string("dayalreadyexistsfriday", "local_deportes");//Ya existe este deporte para el vierens en este horario
							}
							else{
								$errors["day"] = get_string("dayalreadyexistsfriday", "local_deportes");//Ya existe este deporte para el viernes en este horario					}
							}
						}
					}
				}
			}
		}
		return $errors;
	}
}
class deportes_edit_scheduleform extends moodleform {
	public function definition() {
		global $DB;
		$mform = $this->_form;
		
		$instance = $this->_customdata;
		$type = $instance["type"];
		$mform->setType("type", PARAM_INT);
	
		$editinstance = $this->_customdata;
		$editid = $editinstance["editid"];
		$mform->setType("editid", PARAM_TEXT);
		
		$datasports = array();
		$getdatasports = $DB->get_records("sports_classes", array("type" => $type));
		foreach($getdatasports as $getsport){
			$datasports[$getsport->id] = $getsport->name;
		}
	
		$datamodules = array();
		$getmodulesquery = "SELECT id, 
				CONCAT (name, ' -> ', starttime, ' - ',endtime) as name 
				FROM {sports_modules} 
				WHERE type = ?";
		$getdatamodules = $DB->get_records_sql($getmodulesquery, array("type" => $type));
		foreach($getdatamodules as $getmodule){
			$datamodules[$getmodule->id] = $getmodule->name;
		}
		
		$arraydays = array(
				1 => get_string('monday', 'local_deportes'),
				2 => get_string('tuesday', 'local_deportes'),
				3 => get_string('wednesday', 'local_deportes'),
				4 => get_string('thursday', 'local_deportes'),
				5 => get_string('friday', 'local_deportes'),
		);
		
		$mform->addElement("select", "idsports", "Select a sport", $datasports);//lang
	
		$mform->addElement("select", "idmodules", "Select a module", $datamodules );//lang
		
		$mform->addElement("select", "day", "Select a day", $arraydays);//lang
	
//		$mform->addElement("hidden", "type", $type);
//		$mform->setType("type", PARAM_INT);
			
		$mform->addElement("hidden", "action", "edit");
		$mform->addElement("hidden", "editid", $editid);
		$mform->setType("action", PARAM_TEXT);
	
		$this->add_action_buttons(true, get_string('addtoschedule', 'local_deportes'));
	}
	
	public function validation($data, $files){
		global $DB;
		$errors = array();
	
		$idsports = $data["idsports"];
		$idmodules = $data["idmodules"];
		$day = $data["day"];
		$auxiliaryvariableday = 0;
	
		if(empty($idsports)){
			$errors["idsports"] = get_string("mustselectsport", "local_deportes");//Debe seleccionar un deporte
		}
		if(empty($idmodules)){
			$errors["idmodules"] = get_string("mustselectmodule", "local_deportes");//Debe seleccionar un modulo
		}
		
		if(empty($day)){
			$errors["day"] = get_string("mustselectday", "local_deportes");//Debe seleccionar un modulo
		}
		$dayerrorsarray = array(
				1 => get_string("dayalreadyexistsmonday", "local_deportes"),
				2 => get_string("dayalreadyexiststuesday", "local_deportes"),
				3 => get_string("dayalreadyexistswednesday", "local_deportes"),
				4 => get_string("dayalreadyexiststhursday", "local_deportes"),
				5 => get_string("dayalreadyexistsfriday", "local_deportes"),
		);
		for ($counterofdays = 1; $counterofdays<6; $counterofdays++){
			if ($day == $counterofdays){
				if($DB->get_record("sports_schedule", array("idsports" => $idsports, "idmodules" => $idmodules, "day" => $counterofdays))){
					$errors["day"] = $dayerrorsarray[$counterofdays];//Ya existe este deporte para el lunes en este horario
				}
			}
		}
		
	return $errors;
	}
	
}