<?php
//Timetable applicatin for Freightos


include("model/timetable.php");
include("lib/otherfunctions.php");


/*
 * Actions : bookClass, cancelClass, getTimetable
*/

$api = $_REQUEST['action'];

$timetable = new Timetable();
$result = $timetable->$api();

//Send the outputs in json format. 
sendJsonOutput($result);

?>