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
* @copyright  2018 Mark Michaelsen (mmichaelsen678@gmail.com)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once ($CFG->dirroot . "/local/deportes/forms/sports_filepicker.php");
require_once(dirname(__FILE__) . "/locallib.php");
global $PAGE, $CFG, $OUTPUT, $DB, $USER;

$action = optional_param("action", "addfile", PARAM_TEXT);
$status = optional_param("status", null, PARAM_TEXT);
$edition = optional_param("edition", null, PARAM_INT);

require_login();

// Headers that prevent the page to save cache files (schedule images)
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$userid = $USER->id;
$url = new moodle_url('/local/deportes/addsportfile.php');
$context = context_system::instance();
$PAGE->navbar->add(get_string("nav_title", "local_deportes"));
$PAGE->navbar->add(get_string("schedule", "local_deportes"), $url);
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout("standard");
$PAGE->set_title(get_string("page_title", "local_deportes"));
$PAGE->set_heading(get_string("page_heading", "local_deportes"));

$urlschedule = new moodle_url('/local/deportes/schedule.php');

if ($action == "addfile"){
	$addform = new deportes_filepicker();
	if ($addform->is_cancelled()) {
		$action = "view";
		redirect($url);
	}
	
	if ($fromform = $addform->get_data()) {
		$timecreated = time();
		
		//Takes the data from the form
		$newfile = new stdClass();
		$path = $CFG->dirroot. "/local/deportes/img";
		
		$filename = $addform->get_new_filename("userfile");
		$explodename = explode(".",$filename);
		$countnamefile= count($explodename);
		$extension = $explodename[$countnamefile-1];
		
		if ($fromform->type == 1){
			$newfile->name = "outdoors".$timecreated.".".$extension;
			$newfile->type = 1;
		}
		elseif ($fromform->type == 2){
			$newfile->name = "fitness".$timecreated.".".$extension;
			$newfile->type = 2;
		}
		
		if($newfile->type == 1){
			// Delete the previous outdoors file
			foreach(glob("$path/outdoors*") as $file) {
				unlink(realpath($file));
			}
		}
		else if($newfile->type == 2){
			// Delete the previous fitness file
			foreach(glob("$path/fitness*") as $file) {
				unlink(realpath($file));
			}
		}
		
		$file = $addform->save_file("userfile", $path."/".$newfile->name, true);
		
		$newfile->uploaddate = $timecreated;
		$newfile->iduser = $userid;
		$DB->insert_record('deportes_files', $newfile);
			
		redirect($urlschedule);
	}
}

echo $OUTPUT->header();
echo $OUTPUT->heading("DeportesUAI");

if ($action == "addfile") {
	$addform->display();
}

echo $OUTPUT->footer();