<?php
//Timetable applicatin for Freightos

class Timetable{
	
    public function getClassRomms($id){
        $resultTimetableClassData = $db->update('class_timetable',$updateCancelClassData);
       
        $resultTimetableData = array();
        
        header('Content-Type: application/json');
        echo json_encode(array("status" => 1, "data" => $resultTimetableData));
        exit;
    }
	public function bookClass($classroomId, $time, $day,  $subject = 'Math'){		
		
        $timeFrom = "";
        $timeTo = "";
        $day = "";
        
        echo "ddfd";exit;
            
        $insertBookClassData = array("class_id" => $classroomId, "day" => $day,
                        "date" => date("Y-m-d"), "time_from" => $timeFrom, "time_to" => $timeTo, "created_datetime" => date("Y-m-d H:i:s"));
        return $resultInsertBookClassData = $db->insert('class_timetable',$insertCollectPayData);
       
        
    }
	
	public function cancelClass($timetableClassId){
		
        $updateCancelClassData = array("id" => $timetableClassId, "cancelled_status" => $timetableClassId);
        return $resultUpdateCancelClassData = $db->update('class_timetable',$updateCancelClassData);       
        
	}
	
	public function getTimetableClasses($datefrom, $dateto){
		
	}
	
}
?>