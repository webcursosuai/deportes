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
require_once ($CFG->dirroot . "/local/deportes/forms/sports_rulesform.php");
require_once(dirname(__FILE__) . "/locallib.php");
global $PAGE, $CFG, $OUTPUT, $DB, $USER;

$action = optional_param("action", "addfile", PARAM_TEXT);
$edition = optional_param("edition", null, PARAM_INT);

require_login();
$userid = $USER->id;

$url = new moodle_url('/local/deportes/uprules.php');
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Add rules form'); //change with lang

$urlattendance = new moodle_url('/local/deportes/attendance.php');
$addform = new deportes_filepicker();
if ($action == "addfile"){
	$addform = new deportes_filepicker();
	if ($addform->is_cancelled()) {
		$action = "view";
		redirect($urlattendance);
	}

	if ($addform->get_data()) {
		$fromform = $addform->get_data();
		//Takes the data from the form
		$newfile = new stdClass();
		$path = $CFG->dataroot. "/temp/local/deportes";
			if(!file_exists($path . "/rules/")) {
				mkdir($path . "/rules/", 0777, true);
			}
		$filename = $addform->get_new_filename("rulesfile");
		$explodename = explode(".",$filename);
		$countnamefile= count($explodename);
		$extension = $explodename[$countnamefile-1];
		if ($extension != "pdf"){
			print_r("ERROR: Tipo de archivo no aceptado");
			redirect($url);
		}
		else{
			$fs = get_file_storage();
			$file_record = array(
					"contextid" => $context->id,
					"component" => "local_deportes",
					"filearea" => "draft",
					"itemid" => 0,
					"filepath" => "/",
					"filename" => "rules.".$extension,
					"timecreated" => time(),
					"timemodified" => time(),
					"userid" => $USER->id,
					"author" => $USER->firstname." ".$USER->lastname,
					"license" => "allrightsreserved"
			);
			if ($fs->file_exists($context->id,"local_deportes", "draft", 0, "/", "rules.".$extension)) {
				$previousfile = $fs->get_file($context->id, "local_deportes", "draft", 0, "/", "rules.".$extension);
				$previousfile->delete();
				foreach(glob("{$path}/outdoors/*") as $file)
				{
					unlink($file);
				}
			}
			$file = $addform->save_file("rulesfile", $path."/rules/rules.".$extension,false);
			$uploadfile = $path . "/rules/".$file_record["filename"];
			$fileinfo = $fs->create_file_from_pathname($file_record, $uploadfile);
			
			$newfile->editiondate = $file_record["timemodified"];
			$newfile->uploaddate = $file_record["timecreated"];
			$newfile->path = $file_record["filepath"]."deportes/".explode(".", $file_record["filename"])[0]."/".$file_record["filename"];
			$newfile->iduser = $file_record["userid"];
			$DB->insert_record('sports_files', $newfile);
			redirect($urlattendance);
		}
	}
}
echo $OUTPUT->header();
if ($action == "addfile") {
	$addform->display();
}
echo $OUTPUT->footer();