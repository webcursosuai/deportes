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
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once ($CFG->dirroot . "/local/deportes/forms/schedule_form.php");
require_once ($CFG->dirroot . "/local/deportes/forms/sports_form.php");
global $PAGE, $CFG, $OUTPUT, $DB, $USER;

$action = optional_param("action", "view", PARAM_TEXT);
$status = optional_param("status", null, PARAM_TEXT);
$type = optional_param("type", null, PARAM_INT);
$editid = optional_param("editid", null, PARAM_INT);

require_login();
$userid = $USER->id;

$url = new moodle_url('/local/deportes/sportsmodulematch.php');
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Create schedule form'); //change with lang

//$addform = new deportes_add_form_sports();

if($action == "add"){
	$scheduleform = new deportes_schedule_form(null, array("type"=>$type));
	if($scheduleform->is_cancelled()){
		$action = "view";
		$url = new moodle_url('/local/deportes/sportsmodulematch.php');
		redirect($url);
	}
	else if($fromform = $scheduleform->get_data()){
		$idsports = $fromform->idsports;
		$idmodules= $fromform->idmodules;
		$dia = $fromform->day;
		for ($countingdays =1; $countingdays<6; $countingdays++){
			if($dia[$countingdays] == 1){
				$daytobeinsertesintodb = new stdCLass;
				$daytobeinsertesintodb->idsports = $idsports;
				$daytobeinsertesintodb->idmodules = $idmodules;
				$daytobeinsertesintodb->day = $countingdays;
				$DB->insert_record("sports_schedule", $daytobeinsertesintodb, $returnid=true, $bulk=false);
			}
		}
		$action = "view";
		$url = new moodle_url('/local/deportes/sportsmodulematch.php');
		redirect($url);
	}
}

//Edit
if ($action == "edit"){
	if ($editid == null){
		$status =  "No hay nada seleccionado para editar"; //lang
		$action = "view";
		redirect($url);
	}
	else{
		//$editid has the id of the selected sport
		$newquery = "SELECT s.id,
				s.idsports,
				s.idmodules,
				s.day,
				m.type
				FROM {sports_schedule} AS s
				INNER JOIN {sports_modules} AS m ON (s.idmodules = m.id)
				WHERE s.id = ?";
		if($editschedule = $DB->get_records_sql($newquery, array ("id"=>$editid))){
			//if there is a cell with such id
			$editform = new deportes_edit_scheduleform(null, array("editid"=>$editid,
					"type"=>$editschedule[$editid]->type,
					"idsports"=>$editschedule[$editid]->idsports,
					"idmodules"=>$editschedule[$editid]->idmodules,
					"day"=>$editschedule[$editid]->day));
			$defaultdata = new stdClass();
			$defaultdata->idsports = $editschedule[$editid]->idsports;
			$defaultdata->idmodules = $editschedule[$editid]->idmodules;
			$defaultdata->day = $editschedule[$editid]->day;
			$editform->set_data($defaultdata);
			//Fills the form with the data from the DB

			if ($editform->is_cancelled()){
				$action = "view";
				redirect($url);
			}
			else if($edit = $editform->get_data()){
				$edited = new stdClass();
				$edited->id = $editid;
				$edited->idsports = $edit->idsports;
				$edited->idmodules = $edit->idmodules;
				$edited->day = $edit->day;
				//Takes the new data and updates it in the DB
				$DB->update_record("sports_schedule", $edited);
				$action = "view";
				$status = "Deporte editado satisfactoriamente"; //lang
				redirect($url);
			}
		}
	}
}


//"Delete"
if ($action == "delete"){
	if ($editid == null){
		$status = "No hay nada seleccionado para borrar";
		$action = "view";
		redirect($url);
	}
	else{
		$deleter = new stdClass();
		$deleter->id = $editid;

		$DB->delete_records("sports_schedule", array("id" => $deleter->id));
		$action = "view";
		$status = "Horario borrado satisfactoriamente"; //lang
		redirect($url);
	}
}

if ($action == 'view'){
	$urlbuttonout = new moodle_url("/local/deportes/sportsmodulematch.php", array(
			"action" => "add",
			"type" => 0
	));
	$urlbuttonfit = new moodle_url("/local/deportes/sportsmodulematch.php", array(
			"action" => "add",
			"type" => 1
	));

	$query = "SELECT s.id,
			c.name,
			s.day,
			c.type,
			CONCAT(m.starttime,' - ',m.endtime) AS starttime 
			FROM mdl_sports_classes as c
			INNER JOIN mdl_sports_schedule AS s ON (c.id = s.idsports)
			INNER JOIN mdl_sports_modules AS m ON (s.idmodules = m.id)
			ORDER BY c.name, s.day, m.starttime
			";
	$getschedule = $DB->get_records_sql($query, array (""));
	$schedulecounter = count($getschedule);
	if($schedulecounter == 0){
		$status = "No hay deportes en el horario"; //lang
	}
	if($schedulecounter>0){
		//If there are sports in the DB which have not been deleted...
		$table = new html_table();
		$table->head = array("Deporte", "Horario", "Dia", "Tipo", "Editar", "Borrar");//lang
		foreach($getschedule as $currentschedule){
			//Add a button for each sport for editing or deleting
			$urlsport = new moodle_url("/local/deportes/sportsmodulematch.php", array(
					"action" => "edit",
					"editid" => $currentschedule->id,
			));
			$editsporticon = new pix_icon("i/edit", "Editar");
			$urldelete = new moodle_url("/local/deportes/sportsmodulematch.php", array(
					"action" => "delete",
					"editid" => $currentschedule->id,
			));
			$deletesporticon = new pix_icon("t/delete", "Borrar");
			if ($currentschedule->day == 1){
				$currentschedule->day = 'Lunes';//lang
			}else if ($currentschedule->day == 2){
				$currentschedule->day = 'Martes';//lang
			}else if ($currentschedule->day == 3){
				$currentschedule->day = 'Miercoles';//lang
			}else if ($currentschedule->day == 4){
				$currentschedule->day = 'Jueves';//lang
			}else if ($currentschedule->day == 5){
				$currentschedule->day = 'Viernes';//lang
			}
			$currentschedule->type = ($currentschedule->type == 0) ? 'Outdoor' : 'Fitness';
			$table->data[] = array(
					$currentschedule->name,
					$currentschedule->starttime,
					$currentschedule->day,
					$currentschedule->type,
					$OUTPUT->action_icon($urlsport, $editsporticon),
					$OUTPUT->action_icon($urldelete, $deletesporticon,
							new confirm_action("Esta completamente seguro de que quiere borrar este deporte?"))//lang
			); //Show the retrieved data into a table for viewing
		}
	}
}
echo $OUTPUT->header();
if ($action == 'add'){
	$scheduleform->display();
}
if ($action == "edit"){
	$editform->display();
}
if ($action == 'view'){
	echo $OUTPUT->single_button($urlbuttonout,"New Outdoors"); //lang
	echo $OUTPUT->single_button($urlbuttonfit,"New Fitness"); //lang
	echo html_writer::table($table);
}
echo $OUTPUT->footer();