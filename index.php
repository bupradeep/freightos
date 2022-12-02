<?php
//Timetable applicatin for Freightos


include("app/class/PDOEx.php");
include("app/core/db-connect-main.php");
include("model/timetable.php");
include("app/class/Logger.php");
include("app/class/DBManager.php");

$api = $_REQUEST['action'];

$timetable = new Timetable();
if($api == "book"){
    $classroom = $_REQUEST['classroom_id'];
    $time = $_REQUEST['time'];
    $day = $_REQUEST['day'];
    echo "dfdf";exit;
    $timetable->bookClass($classroom, $time,$day);
    header('Content-Type: application/json');
    echo json_encode(array("status" => 1, "mesage" => "Susscessfully booked"));
    exit;
}elseif($api == "cancel"){
    $timetable->cancelClass($classId);
    header('Content-Type: application/json');    
    echo json_encode(array("status" => 1, "mesage" => "Susscessfully cancelled"));
    exit;
}elseif($api == "getTimeTable"){
    $timetable->getTimetableClasses($datefrom, $dateto);
    header('Content-Type: application/json');    
    echo json_encode(array("status" => 1, "mesage" => "Susscessfully cancelled"));
    exit;
}

?>