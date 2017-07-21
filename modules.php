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
* @subpackage deportes
* @copyright  2017	Mihail Pozarski (mpozarski@gmail.com)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

require_once(dirname(dirname(dirname(__FILE__))) . "/config.php");
require_once($CFG->dirroot."/local/deportes/locallib.php");
require_once($CFG->dirroot . '/local/deportes/forms/module_form.php');
global $CFG, $DB, $OUTPUT, $PAGE, $USER;

// User must be logged in.
require_login();
if (isguestuser()) {
	die();
}

$action = optional_param("action", "view", PARAM_TEXT);
$status = optional_param("status", null, PARAM_TEXT);
$editid = optional_param("editid", null, PARAM_INT);

$context = context_system::instance();

if(($email[1] == $CFG->deportes_emailextension) || is_siteadmin() || has_capability("local/deportes:edit", $context)){
	
	$url = new moodle_url("/local/deportes/modules.php");
	$PAGE->navbar->add(get_string("nav_title", "local_deportes"));
	$PAGE->navbar->add(get_string("modules", "local_deportes"), $url);
	$PAGE->set_context($context);
	$PAGE->set_url($url);
	$PAGE->set_pagelayout("standard");
	$PAGE->set_title(get_string("page_title", "local_deportes"));
	$PAGE->set_heading(get_string("page_heading", "local_deportes"));
	
	if($action == 'add'){
		$moduleform = new sports_addmodule_form();
		
		if ($moduleform->is_cancelled()) {
			$backtolist = new moodle_url('/local/deportes/modules.php', array(
					'action' => 'view'));
			redirect($backtolist);
			
		} elseif ($data = $moduleform->get_data()) {
			// Saves the form info in to variables.
			$module = new stdClass();
			$module->name = $data->name;
			$module->starttime = $data->starttime;
			$module->endtime = $data->endtime;
			$module->type = $data->type;
			$module->timecreated = time();
			$module->timemodified = time();
			
			if($DB->insert_record('sports_modules', $module)){
				$status = 'module inserted';
			}
			$backtolist = new moodle_url('/local/deportes/modules.php', array(
					'action' => 'view'));
			redirect($backtolist);
		}
	}
	
	if($action == 'view'){
		if($modules = $DB->get_records('sports_modules')){
			$status = 'existen registros';
			$table = new html_table();
			$table->head = array(
					get_string('name','local_deportes'),
					get_string('initialtime','local_deportes'),
					get_string('endtime','local_deportes'),
					get_string('type','local_deportes'),
					get_string('edit','local_deportes'),
					get_string('delete','local_deportes')
			);
			foreach($modules as $module){
				$type = ($module->type == 0) ? 'Outdoor' : 'Fitness';
				//Add a button for each sport for editing or deleting
				$urledit = new moodle_url("/local/deportes/modules.php", array(
						"action" => "edit",
						"editid" => $module->id,
				));
				$editmoduleicon = new pix_icon("i/edit", "Editar");
				$urldelete = new moodle_url("/local/deportes/modules.php", array(
						"action" => "delete",
						"editid" => $module->id,
				));
				$deletemoduleicon = new pix_icon("t/delete", "Borrar");
				$table->data[] = array(
						$module->name,
						$module->starttime,
						$module->endtime,
						$type,
						$OUTPUT->action_icon($urledit, $editmoduleicon),
						$OUTPUT->action_icon($urldelete, $deletemoduleicon, new confirm_action(get_string('delete_confirmation','local_deportes')))
				);
			}
		}
	}
	if($action == 'edit'){
		if($module = $DB->get_record('sports_modules',array("id"=>$editid))){
			$editform = new sports_editmodule_form(null, array(
					"id"=>$module->id
			));
			$editform->set_data($module);
			if ($editform->is_cancelled()){
				$action = "view";
				redirect($url);
			}elseif($data = $editform->get_data()){
				$module = new stdClass();
				$module->id = $data->editid;
				$module->name = $data->name;
				$module->starttime = $data->starttime;
				$module->endtime = $data->endtime;
				$module->type = $data->type;
				$module->timemodified = time();
				if($DB->update_record('sports_modules', $module)){
					
				}
				$backtolist = new moodle_url('/local/deportes/modules.php', array(
						'action' => 'view'));
				redirect($backtolist);
			}
			
		}else{
			$backtolist = new moodle_url('/local/deportes/modules.php', array(
					'action' => 'view'));
			redirect($backtolist);
		}
	}
	if($action == 'delete'){
		if($editid != null){
			$DB->delete_records('sports_modules', array("id"=>$editid));
			$backtolist = new moodle_url('/local/deportes/modules.php', array(
					'action' => 'view'));
			redirect($backtolist);
		}else{
			$backtolist = new moodle_url('/local/deportes/modules.php', array(
					'action' => 'view'));
			redirect($backtolist);
		}
	}
	
	echo $OUTPUT->header();
	echo $OUTPUT->heading("DeportesUAI");
	
	if($action == 'add'){
		$moduleform->display();
	}
	if($action == 'view'){
		if($status != null){
			echo html_writer::table($table);
		}
		$botonurl = new moodle_url('/local/deportes/modules.php', array('action' => 'add'));
		echo $OUTPUT->single_button($botonurl,get_string('addmodule','local_deportes'));
	}
	if($action == 'edit'){
		$editform->display();
	}
	echo $OUTPUT->footer();
}else{
	print_error(get_string("notallowed", "local_deportes"));
}