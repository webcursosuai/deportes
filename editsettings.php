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
* @copyright  2018	Mark Michaelsen (mmichaelsen678@gmail.com)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

require_once(dirname(dirname(dirname(__FILE__))) . "/config.php");
require_once($CFG->dirroot."/local/deportes/locallib.php");
require_once ($CFG->dirroot . "/local/deportes/forms/editsettings_form.php");
global $CFG, $DB, $OUTPUT, $PAGE, $USER;

// User must be logged in.
require_login();
if (isguestuser()) {
	die();
}

$context = context_system::instance();

if(!has_capability("local/deportes:edit", $context)) {
	print_error("ACCESS DENIED");
}

$url = new moodle_url('/local/deportes/editsettings.php');
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout("standard");
$PAGE->set_title(get_string("page_title", "local_deportes"));
$PAGE->set_heading(get_string("page_heading", "local_deportes"));

$editform = new deportes_form();

if($editform->is_cancelled()) {
	$redirecturl = new moodle_url("/local/deportes/attendance.php");
	redirect($redirecturl);
} else if($formdata = $editform->get_data()) {
	$insertdata = array();
	
	if($startmonthdata = $DB->get_record("deportes_config", array("name" => "month_start"))) {
		$startmonthdata->value = $formdata->month_start;
		$DB->update_record("deportes_config", $startmonthdata);
	} else {
		$startmonthdata = new stdClass();
		$startmonthdata->name = "month_start";
		$startmonthdata->value = $formdata->month_start;
		
		$insertdata[] = $startmonthdata;
	}
	
	if($startdaydata = $DB->get_record("deportes_config", array("name" => "day_start"))) {
		$startdaydata->value = $formdata->day_start;
		$DB->update_record("deportes_config", $startdaydata);
	} else {
		$startdaydata = new stdClass();
		$startdaydata->name = "day_start";
		$startdaydata->value = $formdata->day_start;
		
		$insertdata[] = $startdaydata;
	}
	
	if($endmonthdata = $DB->get_record("deportes_config", array("name" => "month_end"))) {
		$endmonthdata->value = $formdata->month_end;
		$DB->update_record("deportes_config", $endmonthdata);
	} else {
		$endmonthdata = new stdClass();
		$endmonthdata->name = "month_end";
		$endmonthdata->value = $formdata->month_end;
		
		$insertdata[] = $endmonthdata;
	}
	
	if($enddaydata = $DB->get_record("deportes_config", array("name" => "day_end"))) {
		$enddaydata->value = $formdata->day_end;
		$DB->update_record("deportes_config", $enddaydata);
	} else {
		$enddaydata = new stdClass();
		$enddaydata->name = "day_end";
		$enddaydata->value = $formdata->day_end;
		
		$insertdata[] = $enddaydata;
	}
	
	if($attendancedata = $DB->get_record("deportes_config", array("name" => "totalattendance"))) {
		$attendancedata->value = $formdata->totalattendance;
		$DB->update_record("deportes_config", $attendancedata);
	} else {
		$attendancedata = new stdClass();
		$attendancedata->name = "totalattendance";
		$attendancedata->value = $formdata->totalattendance;
		
		$insertdata[] = $attendancedata;
	}
	
	$redirecturl = new moodle_url("/local/deportes/attendance.php");
	redirect($redirecturl);
}

echo $OUTPUT->header();
echo $OUTPUT->heading("DeportesUAI");

$editform->display();

echo $OUTPUT->footer();