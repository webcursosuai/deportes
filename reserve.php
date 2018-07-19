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
* @copyright  2017	Mihail Pozarski (mpozarski944@gmail.com)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

require_once(dirname(dirname(dirname(__FILE__))) . "/config.php");
require_once($CFG->dirroot."/local/deportes/locallib.php");
global $CFG, $DB, $OUTPUT, $PAGE;

// User must be logged in.
require_login();
if (isguestuser()) {
	die();
}
$email = $USER->email;
$context = context_system::instance();

	$url = new moodle_url("/local/deportes/reserve.php");
	$PAGE->navbar->add(get_string("nav_title", "local_deportes"));
	$PAGE->navbar->add(get_string("reserve", "local_deportes"), $url);
	$PAGE->set_context($context);
	$PAGE->set_url($url);
	$PAGE->set_pagelayout("standard");
	$PAGE->set_title(get_string("page_title", "local_deportes"));
	$PAGE->set_heading(get_string("page_heading", "local_deportes"));
	
	$email = $USER->email;
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		print_error(get_string("notvalidemail", "local_deportes"));
	}
	
	$curl = curl_init();
	$url = $CFG->deportes_urldeportesalumno;
	$token = $CFG->deportes_token;
	$fields = array(
			"token" => $token,
			"email" => $email
	);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl, CURLOPT_POST, TRUE);
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
	$result = json_decode(curl_exec($curl));
	curl_close($curl);
	
	$table = new html_table("p");
	
	$table->head = array(
			get_string("sport", "local_deportes"),
			get_string("teacher", "local_deportes"),
			get_string("t_start", "local_deportes"),
			get_string("t_end", "local_deportes"),
			get_string("reserved", "local_deportes"),
			get_string("quota", "local_deportes"),
			""
	);
	
	$table->size = array(
			"15%",
			"20%",
			"15%",
			"15%",
			"15%",
			"10%",
			"10%"
	);
	
	foreach($result as $sport){
		$sportinfo = array(
				$sport->name,
				$sport->led_by,
				$sport->whenHHMM,
				$sport->endHHMM,
				$sport->reservados,
				$sport->capacity,
				$OUTPUT->single_button('https://intranet.uai.cl/WebPages/Deporte/Reservas.aspx',get_string('reserve','local_deportes'))
		);
		
		$table->data[] = $sportinfo	;
	}
	
	echo $OUTPUT->header();
	echo $OUTPUT->heading("DeportesUAI");
	echo $OUTPUT->tabtree(deportes_tabs(), "reserve");
	if(empty($result)){
		echo html_writer::div(get_string("nosportsleft","local_deportes"),"alert alert-info", array("role"=>"alert"));
	}else{
		echo html_writer::table($table);
	}
	echo $OUTPUT->footer();
