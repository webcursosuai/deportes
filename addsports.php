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
require_once ($CFG->dirroot . "/local/deportes/forms/sports_form.php");
global $PAGE, $CFG, $OUTPUT, $DB, $USER;

$action = optional_param("action", "view", PARAM_TEXT);
$status = optional_param("status", null, PARAM_TEXT);
$edition = optional_param("edition", null, PARAM_INT);

require_login();
$userid = $USER->id;

$url = new moodle_url('/local/deportes/addsports.php');
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Add sports form'); //change with lang


//Add a new sport
if ($action == "add"){
	$addform = new deportes_add_form_sports();
	if ($addform->is_cancelled()) {
		$action = "view";
		redirect($url);
	}
	if ($fromform = $addform->get_data()) {
		//Takes the data from the form
		$createdsport = new stdClass();
		$createdsport->name = $fromform->name;
		$createdsport->type = $fromform->type;
		$createdsport->lastmodified = time();

		$insertaction = $DB->insert_record("sports_classes", $createdsport, $returnid=true, $bulk=false);
		//Inserts the data into the DB
		$status = "Deporte agregado satisfactoriamente"; //lang
		$action = "view";
		//Let's go see the new sport among the rest
		redirect($url);
	}
}
//End of adding a new sport

//Edit
if ($action == "edit"){
	if ($edition == null){
		$status =  "No hay nada seleccionado para editar"; //lang
		$action = "view";
		redirect($url);
	}
	else{
		//$edition has the id of the selected sport
		$newquery = "SELECT * FROM {sports_classes}
				WHERE id = ?";
		if($editsport = $DB->get_records_sql($newquery, array ("id"=>$edition))){
			//if there is an sport with such id
			$editform = new deportes_edit_sportsform(null, array("edition"=>$edition,
					"name"=>$editsport[$edition]->name,
					"type"=>$editsport[$edition]->type));
			$defaultdata = new stdClass();
			$defaultdata->name = $editsport[$edition]->name;
			$defaultdata->type = $editsport[$edition]->type;
			$defaultdata->lastmodified = time();
			$editform->set_data($defaultdata);
			//Fills the form with the data from the DB
			//NOTE: Fills the date with the current date, not the one from the sport
				
			if ($editform->is_cancelled()){
				$action = "view";
				redirect($url);
			}
			else if($edit = $editform->get_data()){
				$edited = new stdClass();
				$edited->id = $edition;
				$edited->name = $edit->name;
				$edited->type = $edit->type;
				$edited->lastmodified = time();
				//Takes the new data and updates it in the DB
				$DB->update_record("sports_classes", $edited);
				$action = "view";
				$status = "Deporte editado satisfactoriamente"; //lang
				redirect($url);
			}
		}
	}
}
//End of Edit

//"Delete"
if ($action == "delete"){
	if ($edition == null){
		$status = "No hay nada seleccionado para borrar";
		$action = "view";
		redirect($url);
	}
	else{
		$deleter = new stdClass();
		$deleter->id = $edition;

		$DB->delete_records("sports_classes", array("id" => $deleter->id));
		$action = "view";
		$status = "Deporte borrado satisfactoriamente"; //lang
		redirect($url);
	}
}
//End "delete"

//View sports
if ($action == "view"){

	$query = "SELECT * FROM {sports_classes}
			WHERE lastmodified != 0
			ORDER BY type";
	$getsports = $DB->get_records_sql($query, array (""));
	$sportcounter = count($getsports);
		$botonurl = new moodle_url("/local/deportes/addsports.php", array("action" => "add"));
	if($sportcounter == 0){
		$status = "No hay deportes"; //lang
	}
	if($sportcounter>0){
		//If there are sports in the DB which have not been deleted...
		$table = new html_table();
		$table->head = array("Name", "Tipo de deporte", "Editar", "Borrar");
		foreach($getsports as $currentsport){
			//Add a button for each sport for editing or deleting
			$urlsport = new moodle_url("/local/deportes/addsports.php", array(
					"action" => "edit",
					"edition" => $currentsport->id,
			));
			$editsporticon = new pix_icon("i/edit", "Editar");
			$urldelete = new moodle_url("/local/deportes/addsports.php", array(
					"action" => "delete",
					"edition" => $currentsport->id,
			));
			$deletesporticon = new pix_icon("t/delete", "Borrar");
			$currentsport->type = ($currentsport->type == 0) ? 'Outdoor' : 'Fitness';				
			$table->data[] = array(
					$currentsport->name,
					$currentsport->type,
					$OUTPUT->action_icon($urlsport, $editsporticon),
					$OUTPUT->action_icon($urldelete, $deletesporticon,
							new confirm_action("Esta completamente seguro de que quiere borrar este deporte?"))//lang
			); //Show the retrieved data into a table for viewing
		}
	}
}
//End of view


echo $OUTPUT->header();
if ($action == "add"){
	$addform->display();
}

if ($action == "edit"){
	$editform->display();
}

if ($action == "view"){
	if ($status != null){
		p($status, $strip=false);
		$status = null;
	}
	echo $OUTPUT->single_button($botonurl,"Agregar Deporte"); //lang
	echo html_writer::table($table);
}
echo $OUTPUT->footer();