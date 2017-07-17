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

$context = context_system::instance();

$url = new moodle_url("/local/deportes/reserve.php");
$PAGE->navbar->add(get_string("nav_title", "local_deportes"));
$PAGE->navbar->add(get_string("reserve", "local_deportes"), $url);
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout("standard");
$PAGE->set_title(get_string("page_title", "local_deportes"));
$PAGE->set_heading(get_string("page_heading", "local_deportes"));

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

// Ejemplo
$table->data[] = array(
		"RPM",
		"Javiera Constanza Ruiz Ganga",
		"15-03-2017 13:10",
		"15-03-2017 14:10",
		"31",
		"45",
		get_string("reserve", "local_deportes")
);

echo $OUTPUT->header();
echo $OUTPUT->heading("DeportesUAI");
echo $OUTPUT->tabtree(deportes_tabs(), "reserve");
echo html_writer::table($table);
echo $OUTPUT->footer();