<?php
/**
 * Strings for component "sports", language "es"
 *
 * @package	deportes
 */

defined("MOODLE_INTERNAL") || die();

//$string[""] = "";

$string["pluginname"] = "deportes";
$string["view"] = "Ver";
$string["attendance"] = "Asistencia";
$string["schedule"] = "Horario";
$string["reserve"] = "Reservar";
$string["teacher"] = "Profesor";
$string["reserved"] = "Reservados";
$string["quota"] = "Cupo";
$string["nav_title"] = "Deportes";
$string["page_title"] = "Deportes";
$string["page_heading"] = "Deportes";
$string["token"] = "Token Omega";
$string["tokendesc"] = "Token entregado por Omega para el uso de Webapi";
$string["urlasistenciasalumno"] = "URL Servicio AsistenciaAlumno";
$string["urlasistenciasalumnodesc"] = "URL de Webapi Omega para ver asistencias a deportes de los alumnos";
$string["urldeportesalumno"] = "URL Servicio DeportesAlumno";
$string["urldeportesalumnodesc"] = "URL de Webapi Omega para ver deportes disponibles para el alumno";
$string["startmonth"] = "Mes de inicio";
$string["startmonthdesc"] = "Primer mes del período de deportes";
$string["endmonth"] = "Mes final";
$string["endmonthdesc"] = "Último mes del peíodo de deportes";
$string["startday"] = "Día de inicio";
$string["startdaydesc"] = "Primer día del peíodo de deportes. Los alumnos podrán registrar asistencias desde este día";
$string["endday"] = "Día final";
$string["enddaydesc"] = "Último día del período de deportes. Asistencias registradas después de este día no serán contadas hacia el total del período";
$string["situation"] = "Situación";
$string["pending"] = "Pendiente";
$string["failed"] = "Reprobado";
$string["rules"] = "Reglas";
$string["recommended"] = "Recomendado";
$string["help"] = "Información del gráfico";
$string["emailextension"] = "Extensión de email";
$string["emailextensiondesc"] = "Extensión de email utilizado en la plataforma de Omega";
$string["courseid"] = "ID de curso general";
$string["courseiddesc"] = "ID del curso general de informaciones usado para vincularlo en el bloque UAI. El vínculo no se mostrará si esto vale 0";

$string["Jan"] = "Enero";
$string["Feb"] = "Febrero";
$string["Mar"] = "Marzo";
$string["Apr"] = "Abril";
$string["May"] = "Mayo";
$string["Jun"] = "Junio";
$string["Jul"] = "Julio";
$string["Aug"] = "Agosto";
$string["Sep"] = "Septiembre";
$string["Oct"] = "Octubre";
$string["Nov"] = "Noviembre";
$string["Dec"] = "Diciembre";


$string["month"] = "Mes";
$string["week"] = "Semana";
$string["date"] = "Fecha";
$string["sport"] = "Deporte";
$string["t_start"] = "Hora inicio";
$string["t_end"] = "Hora término";
$string["fitness"] = "Fitness";
$string["outdoor"] = "Outdoor";

$string["calendarchartweek"] = "DLMMJVS";
$string["number"] = "#";
$string["totalattendance"] = "Asistencias totales";
$string["minimumattendance"] = "Minimo requerido";
$string["monthattendance"] = "Este mes";
$string["sportschart_title"] = "Deportes realizados";
$string["sportschart_subtitle"] = "Asistencias totales";

//module_form langs
$string["module_form"] = "Formulario Modulos";
$string["module_name"] = "Nombre del modulo";
$string["module_name_help"] = "Ingrese un texto referente al nombre de módulo.";
$string["module_initialhour"] = "Hora de inicio del módulo";
$string["module_initialhour_help"] = "Ingrese la hora de inicio del módulo en formato: 16:00, 08:00, 23:30, 10:15.";
$string["module_endhour"] = "Hora de termino del módulo";
$string["module_endhour_help"] = "Ingrese la hora de termino del módulo en formato: 16:00, 08:00, 23:30, 10:15.";
$string["module_type"] = "tipo de modulo";
$string["module_type_help"] = "Seleccione si el modulo corresponde a Fitness o Outdoor.";

$string["close"] = "Cerrar";
$string["rules_title"] = "Reglamento de deportes";
$string["graphinfo"] = "Información del gráfico";
$string["rules_content"] = "Estimado Alumno/a <br>
	Para aprobar tu crédito de deportes debes realizar lo siguiente:<br>
	* N° de asistencias: <b>26</b><br>
	* Inicio semestre: <b>01 de Agosto</b><br>
	* Termino semestre: <b>25 de Noviembre</b><br>
	Puedes realizar la cantidad de asistencias por día, mes y semestre que gustes, pero solo:<br>
	* <b>Será válido 1 asistencia por día</b><br>
	* Máximo <b>8</b> asistencias validas por <b>mes</b><br>
	* 26 asistencias validas por semestre<br>
	* Es de exclusiva responsabilidad de cada alumno el número de asistencias que realizara
	por mes. Ten presente que la sumatoria de asistencias semestral debe ser 26 a la fecha del
	25 de noviembre con un tope máximo de 8 asistencias <b>VÁLIDAS</b> por mes<br>
	* Recuerda que reservar y no asistir a la clase, te restara 1 asistencia<br>
	* Recuerda que al cancelar una reserva 90 minutos antes del inicio de la clase, se restara 0,5
	asistencia<br>
	* Puedes realizar tus asistencias cuando gustes, sin embargo te sugerimos realizar 2 por
	semana para que obtengas los beneficios fisiológicos que da la continuidad de la actividad
	física.";
$string["graphinfo_content"] = '<img src="img/row.png"><br>
	Cada fila del gráfico representa un día de la semana.
	Como muestra la imagen, la fila resaltada corresponde a todos los miércoles del año.<br>
	<img src="img/colpng"><br>
	Cada columna es una semana, partiendo con domingo desde lo más alto y terminando con sábado al último.
	Cada columna tiene 7 espacios hacia abajo, por los 7 días de la semana.<br>
	<img src="img/punishment.png"><br>
	Una celda corresponde a un día. Si no tiene color (fondo gris) es porque no hay asistencias regitradas para ese día.
	Una asistencia válida (+1) en el día se muestra con una celda de color azul, mientras que un castigo (-1) con una de color naranjo.
	Media asistencia (+0.5) tiene un color celeste, y medio castigo (-0.5) tiene un color naranjo claro.<br>
	<img src="img/blank.png"><br>
	Una asistencia nula (0) tiene el color blanco, como muestra la imagen. Se diferencian levemente de las grises.<br>
	<b>Nótese</b> que el gráfico muestras las asistencias <b>válidas</b>, es decir, si usted realizó una asistencia (+1) pero cuenta con medio castigo (-0.5)
	ese mismo día, el gráfico mostrará el correspondiente a media asistencia (+0.5).<br>
	Pasar el cursor sobre una celda mostrará el detalle de la fecha y valor de asistencia en ese día.';