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
* @subpackage sync
* @copyright  2018 Mark Michaelsen (mmichaelsen678@gmail.com)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
defined("MOODLE_INTERNAL") || die();
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/config.php");
require_once($CFG->libdir . "/formslib.php");

// Form definition for synchronization creation
class deportes_form extends moodleform {
	public function definition() {
		global $DB, $OUTPUT;
		
		$mform = $this->_form;
		
		$result = $DB->get_records("deportes_config", array());
		$settings = array();
		
		foreach($result as $config) {
			$settings[$config->name] = $config->value;
		}
		
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
		
		$days = array();
		for($day = 1; $day <= 31; $day++){
			$days[$day] = $day;
		}
		
		$startmonthselect = $mform->addElement("select", "month_start", get_string("startmonth", "local_deportes"), $months);
		$startmonthselect->setSelected($settings["month_start"]);
		$mform->addHelpButton("month_start", "startmonth", "local_deportes");
		
		$startdayselect = $mform->addElement("select", "day_start", get_string("startday", "local_deportes"), $days);
		$startdayselect->setSelected($settings["day_start"]);
		$mform->addHelpButton("day_start", "startday", "local_deportes");
		
		$endmonthselect = $mform->addElement("select", "month_end", get_string("endmonth", "local_deportes"), $months);
		$endmonthselect->setSelected($settings["month_end"]);
		$mform->addHelpButton("month_end", "endmonth", "local_deportes");
		
		$enddayselect = $mform->addElement("select", "day_end", get_string("endday", "local_deportes"), $days);
		$enddayselect->setSelected($settings["day_end"]);
		$mform->addHelpButton("day_end", "endday", "local_deportes");
		
		$mform->addElement("text", "totalattendance", get_string("totalattendance", "local_deportes"));
		$mform->setDefault("totalattendance", $settings["totalattendance"]);
		$mform->addHelpButton("totalattendance", "totalattendance", "local_deportes");
		$mform->setType("totalattendance", PARAM_INT);
		
		$this->add_action_buttons($cancel = true);
		
		/*
		if(count($result) == 0) {
			echo $OUTPUT->notification(get_string("error_communication", "local_sync"));
		} else {
			$periods = array();
			foreach($result as $period) {
				$object = new stdClass();
				$object->id = $id = $period->periodoAcademicoId;
				$object->name = $period->periodoAcademico;
				$object->campus = $period->sede;
				$object->type = $period->tipo;
				$object->year = $period->AnoPeriodo;
				$object->number = $period->NumeroPeriodo;
				
				$periods[$id] = $object;
			}
			
			krsort($periods);
			
			$select = array();
			foreach($periods as $period) {
				$select[$period->id."|".$period->campus."|".$period->type."|".$period->year."|".$period->name."|".$period->number] = $period->id." | ".
					$period->name." | ".$period->campus." | ".$period->type;
			}
			
			$beginning = array(get_string("omega_default", "local_sync"));
			$options = $beginning + $select;
			
			$mform->addElement("select", "period", get_string("omega","local_sync"), $options);
			$mform->setType("period" , PARAM_TEXT);
			$categoriessql = "SELECT cc.id AS id,
					cc.name AS name,
					cc.path AS path
					FROM {course_categories} AS cc
					WHERE visible = ?";
			
			$params = array(1);
			
			$categoriesset = $DB->get_recordset_sql($categoriessql, $params);
			
			$categories = array();
			$categories[0] = get_string("webc_default", "local_sync");
			
			$unpathedcategories = array();
			$path = array();
			
			foreach($categoriesset as $category) {
				$unpathedcategories[$category->id] = $category->name;
				$path[$category->id] = explode("/", $category->path);
			}
			
			$categoriesset->close();
			
			foreach($unpathedcategories as $id => $name) {
				$finalpath = "$id";
				foreach($path[$id] as $pathid) {
					if($pathid != "") {
						$finalpath .= " | ".$unpathedcategories[$pathid];
					}
				}
				$categories[$id] = $finalpath;
			}
			
			$mform->addElement("select", "category", get_string("webc", "local_sync"), $categories);
			$mform->setType("category" , PARAM_TEXT);
			
			$statusoptions = array(
					get_string("inactive", "local_sync"),
					get_string("active", "local_sync")
			);
			
			$mform->addElement("select", "status", get_string("status", "local_sync"), $statusoptions);
			$mform->setType("status", PARAM_INT);
			
			$mform->addElement("text", "responsible", get_string("in_charge", "local_sync")); 
	        $mform->setType("responsible", PARAM_NOTAGS);
	        $mform->addHelpButton("responsible", "in_charge", "local_sync");
			
			$this->add_action_buttons($cancel = true, $submitlabel= get_string("buttons", "local_sync"));
		}*/
		
	}
	
	public function validation($data, $files) {
		global $DB;
		$errors = array();
		/*
		$academicperiod = $data["period"];
		$category = $data["category"];
		$responsible = $data["responsible"];
		
		if (!isset($academicperiod) || empty($academicperiod) || $academicperiod == 0 || $academicperiod == null) {
			$errors["period"] = get_string("error_period", "local_sync");
		} else if($DB->record_exists("sync_data", array(
				"academicperiodid" => intval(explode("|", $academicperiod)[0]), 
				"status" => 1
		))) {
			$errors["period"] = get_string("error_period_active", "local_sync");
		} else if($DB->record_exists("sync_data", array(
				"academicperiodid" => intval(explode("|", $academicperiod)[0]), 
				"status" => 0
		))) {
			$errors["period"] = get_string("error_period_inactive", "local_sync");
		}
		
		if (!isset($category) || empty($category) || $category == 0 || $category == null) {
			$errors["category"] = get_string("error_omega", "local_sync");
		} else if($DB->record_exists("sync_data", array(
				"categoryid" => $category,
				"status" => 1
		))) {
			$errors["category"] = get_string("error_omega_active", "local_sync");
		} else if($DB->record_exists("sync_data", array(
				"categoryid" => $category,
				"status" => 0
		))) {
			$errors["category"] = get_string("error_omega_inactive", "local_sync");
		}
		
		if($responsible != "") {
			if(explode("@", $responsible)[1] != "uai.cl") {
				$errors["responsible"] = get_string("error_responsible_invalid", "local_sync");
			} else if(!$DB->record_exists("user", array("email" => $responsible))) {
				$errors["responsible"] = get_string("error_responsible_nonexistent", "local_sync");
			}
		}
		*/
		return $errors;
	}
}