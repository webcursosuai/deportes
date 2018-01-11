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
* @copyright  2017	Mark Michaelsen (mmichaelsen678@gmail.com)
* @copyright  2017	Javier Gonzalez (javiergonzalez@alumnos.uai.cl)
* @copyright  2017  Jorge Cabané (jcabane@alumnos.uai.cl) 
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

require_once(dirname(dirname(dirname(__FILE__))) . "/config.php");
require_once($CFG->dirroot."/local/deportes/locallib.php");
require_once ($CFG->libdir . '/tablelib.php');
require_once($CFG->libdir . "/formslib.php");
global $CFG, $DB, $OUTPUT, $PAGE;

require_once ($CFG->libdir . '/pdflib.php');
require_once ($CFG->dirroot . '/mod/assign/feedback/editpdf/fpdi/fpdi.php');
require_once ($CFG->dirroot . "/mod/assign/feedback/editpdf/fpdi/fpdi_bridge.php");
require_once ($CFG->dirroot . "/mod/assign/feedback/editpdf/fpdi/fpdi.php");

// User must be logged in.
require_login();
if (isguestuser()) {
	die();
}

$context = context_system::instance();
var_dump($context);

if(($email[1] == $CFG->deportes_emailextension) || is_siteadmin() || has_capability("local/deportes:edit", $context)){
	$url = new moodle_url("/local/deportes/schedule.php");
	$PAGE->navbar->add(get_string("nav_title", "local_deportes"));
	$PAGE->navbar->add(get_string("schedule", "local_deportes"), $url);
	$PAGE->set_context($context);
	$PAGE->set_url($url);
	$PAGE->set_pagelayout("standard");
	$PAGE->set_title(get_string("page_title", "local_deportes"));
	$PAGE->set_heading(get_string("page_heading", "local_deportes"));

	echo $OUTPUT->header();
	echo $OUTPUT->heading("DeportesUAI");
	echo $OUTPUT->tabtree(deportes_tabs(), "schedule");
	$fs = get_file_storage();
	
	if($fitnessresult = $DB->get_record_sql("SELECT contenthash FROM {files} WHERE ".$DB->sql_like("filename", ":img"), array("img" => "fitness.%"))) {
		var_dump($fitnessresult);
		$fitnessname = $fitnessresult->contenthash;
		$path1 = substr($fitnessname, 0, 2);
		$path2 = substr($fitnessname, 2, 2);
		$fitnesspath = $path1."/".$path2."/".$fitnessname;
		
		$fitnessfile = $CFG->dataroot."/filedir/".$fitnesspath;
	}
	
	if($outdoorsresult = $DB->get_record_sql("SELECT contenthash FROM {files} WHERE ".$DB->sql_like("filename", ":img"), array("img" => "outdoors.%"))) {
		var_dump($outdoorsresult);
		$outdoorsname = $outdoorsresult->contenthash;
		$path1 = substr($outdoorsname, 0, 2);
		$path2 = substr($outdoorsname, 2, 2);
		$outdoorspath = $path1."/".$path2."/".$outdoorsname;
		
		$outdoorsfile = $CFG->dataroot."/filedir/".$outdoorspath;
	}
	
	if(!file_exists($fitnessfile.".jpg")) {
		//rename($fitnessfile, replace_extension($fitnessfile, "jpg"));
	}
	
	if(!file_exists($outdoorsfile.".jpg")) {
		//rename($outdoorsfile, replace_extension($outdoorsfile, "jpg"));
	}
	
	//$fileoutdoors = $fs->get_file($context->id, "local_deportes", "draft", 0, "/", "outdoors.jpg");
	//$filefitness = $fs->get_file($context->id, "local_deportes", "draft", 0, "/", $fitnessname);
	var_dump($fileoutdoors);
	//$imgurlfitness = moodle_url::make_pluginfile_url($filefitness->get_contextid(), $filefitness->get_component(), $filefitness->get_filearea(), $filefitness->get_itemid(), $filefitness->get_filepath(), $filefitness->get_filename());
	//$imgurloutdoors = moodle_url::make_pluginfile_url($fileoutdoors->get_contextid(), $fileoutdoors->get_component(), $fileoutdoors->get_filearea(), $fileoutdoors->get_itemid(), $fileoutdoors->get_filepath(), $fileoutdoors->get_filename());
	
	
	echo html_writer::img("img/fitness.jpg", "Fitness");
	echo html_writer::img("img/outdoors.jpg", "Outdoors");

	echo $OUTPUT->footer();
} else {
	print_error(get_string("notallowed", "local_deportes"));
}

/* Code for the pdf schedule, for a future update
 $fs = get_file_storage();
 if ($fs->file_exists($context->id,"local_deportes", "draft", 0, "/", "fitness.pdf")) {
 var_dump($fs);
 }
 $scheduleurl = moodle_url::make_pluginfile_url($context->id, "local_deportes", "draft", 0, "/", "fitness.pdf");
 var_dump($scheduleurl);
 $viewerpdf = html_writer::nonempty_tag("embed", " ", array(
 "src" => $scheduleurl,
 "style" => "height:75vh; width:60vw"
 ));
 */