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
		$sportid = $fromform->sportid;
		$moduleid= $fromform->moduleid;
		$dia = $fromform->day;
		for ($countingdays =1; $countingdays<6; $countingdays++){
			if($dia[$countingdays] == 1){
				$daytobeinsertesintodb = new stdCLass;
				$daytobeinsertesintodb->idsports = $sportid;
				$daytobeinsertesintodb->idmodules = $moduleid;
				$daytobeinsertesintodb->day = $countingdays;
				$DB->insert_record("sports_schedule", $daytobeinsertesintodb, $returnid=true, $bulk=false);
			}
		}
		$action = "view";
		$url = new moodle_url('/local/deportes/sportsmodulematch.php');
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

}
echo $OUTPUT->header();
if ($action == 'add'){
	$scheduleform->display();
}
if ($action == 'view'){
	echo $OUTPUT->single_button($urlbuttonout,"Out"); //lang
	echo $OUTPUT->single_button($urlbuttonfit,"Fit"); //lang
}
echo $OUTPUT->footer();