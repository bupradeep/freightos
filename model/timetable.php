<?php
//Timetable applicatin for Freightos

include("../app/class/Logger.php");
include("../app/class/DBManager.php");

class Timetable extends DB{
	
    private $transLog;
    private $db;
    use Logger;
    
    function __construct(){
        $this->transLog = new Logger("log_".date("YmdHis"), true);
        $this->db = new DB();
    }    
    
    //Book the class
	public function bookClass($subject = 'Math'){
		                    
        try{
            $classroom = $_REQUEST['classroom_id'];
            $time = $_REQUEST['time'];
            $date = $_REQUEST['date'];
            $day = date('l', strtotime($date));
            
            
            $classConfig = $this->getClassConfig($classroomId);            
            $timeTo = $time + $classConfig['bookable_hours_per_day'];
            
            //Validate day should be in prefered days
            if (!in_array($day, explode(',',$classConfig['prefered_day']))){                
                return array("status" => 0, "mesage" => "Given day is not in prefered days list");
            }
            
            //Validate duplicate class bookings
            if ($this->checkDuplicateBookings($date, $time,  $day, $subject, $classroomId)){                
                return array("status" => 0, "mesage" => "Duplicate class booking. Class has already booked");
            }
            
            //Validate total people count per class
            $datefrom = (date('D') != 'Mon') ? date('Y-m-d', strtotime('last Monday')) : date('Y-m-d');
            $dateto = (date('D') != 'Sat') ? date('Y-m-d', strtotime('next Saturday')) : date('Y-m-d');
            $totalClasscount = $this->getTotalClassCountByWeek($datefrom, $dateto, $classroomId); 
            if ($totalClasscount >= $classConfig['people_capacity']){                
                return array("status" => 0, "mesage" => "Reache max people for the class");
            }
            
            $insertBookClassData = array("class_id" => $classroomId, "day" => $day, "subject_name" => $subject,
                        "date" => $date, "time_from" => $timeFrom, "time_to" => $timeTo, "created_datetime" => date("Y-m-d H:i:s"));
            $resultInsertBookClassData = $this->db->insert('class_timetable',$insertCollectPayData);
            $result = array("status" => 1, "mesage" => "Susscessfully booked");
        } catch (Exception $ex) {
            $this->transLog->log("Exception occured while booke the class = ".$ex->getMessage(), 2);
            $result = array("status" => 0, "mesage" => "Class has not booked successfully");            
        }
        return $result; 
    }
	
    //Cancel the class
	public function cancelClass(){
		             
        try{
            
            $classId = $_REQUEST['class_id'];
            
            //Validate requested cancel class should not be less than 24 hours
            $classtimtableData = $this->getClasstimeTableById($classId);
            $classtime = strtotime($classtimtableData['date']. " ".$classtimtableData['timefrom'].":00:00");
            if ($classtime < (time()+86400)){                
                return array("status" => 0, "mesage" => "Class cannot be cancelled within 24 hours");
            }
            
            $updateStatus = array("cancelled_status" => "1");
            $updateFor = array("id" => $classId);
            $resultUpdateCancelClassData = $this->db->update('class_timetable', $updateStatus, $updateFor);
            $result = array("status" => 1, "mesage" => "Susscessfully cancelled");
            return $result;
        } catch (Exception $ex) {
            $this->transLog->log("Exception occured while cancel the class = ".$ex->getMessage(), 2);
            $result = array("status" => 0, "mesage" => "Class has not cancelled properlly");
            return $result;
        }        
	}
	
    //Get timetable for the given two dates
	public function getTimetable($datefrom, $dateto){
		try{
            if(isset($_REQUEST['datefrom']) && isset($_REQUEST['dateto'])){
                $datefrom = $_REQUEST['datefrom'];
                $dateto = $_REQUEST['dateto'];
            }else{
                $datefrom = (date('D') != 'Mon') ? date('Y-m-d', strtotime('last Monday')) : date('Y-m-d');
                $dateto = (date('D') != 'Sat') ? date('Y-m-d', strtotime('next Saturday')) : date('Y-m-d');
            } 

            $selectQueryUser = "SELECT * FROM class_timetable "
                . "WHERE date >= :datefrom AND date <= :dateto AND active_status = 1"
                . "AND cancelled_status = 1"
                . "ORDER BY created_datetime ASC";
            $bindParamClass[":datefrom"] = $datefrom;
            $bindParamClass[":dateto"] = $dateto;
            $timeTable = $this->db->selectQuery($selectQueryClass, $bindParamClass); 
            if(is_array($timeTable) && count($timeTable) > 0){
                for($i=0; $i < count($timeTable); $i++){
                    $classroom = $this->getClassConfig($timeTable[$i]['classroom_id'])['name'];    
                    $timetableData[$classroom][] = array("subject" => $timeTable[$i]['subject_name'], "total_people" => $timeTable[$i]['total_people'],
                        "date" => $timeTable[$i]['date'], "time" => $timeTable[$i]['time_from']."-".$timeTable[$i]['time_to']);
                }
                $result = array("status" => 1, "data" => $timetableData);
                return $result;
            }
            
        } catch (Exception $ex) {
            $this->transLog->log("Exception occured while get the timetable = ".$ex->getMessage(), 2);
            $result = array("status" => 0, "mesage" => "Something went wrong");
            return $result;
        }  
	}	
    
    //Get class details of particular given one
    public function getClassConfig($classroomId){
		try{
            $conditions = array(
                'where' => array('id' => $classroomId)
            );
            return $this->db->getSingle('classrooms',$conditions);
        } catch (Exception $ex) {
            $this->transLog->log("Exception occured while get classroom details = ".$ex->getMessage(), 2);
            return false;
        }  
	}
    
    //Get the classtimetable details by given classid
    public function getClasstimeTableById($classId){
		try{
            $conditions = array(
                'where' => array('id' => $classId)
            );
            return $this->db->getSingle('class_timetable',$conditions);
        } catch (Exception $ex) {
            $this->transLog->log("Exception occured while get classroom details = ".$ex->getMessage(), 2);
            return false;
        }  
	}
    
    //Get the total class count for the given date range and classroom
    public function getTotalClassCountByWeek($dateFrom, $dateTo, $classroomId){
		try{
            $selectQueryUser = "SELECT count(*) as count FROM class_timetable "
                . "WHERE clasroom_id = :classroom_id  AND date >= :datefrom AND date <= :dateto AND active_status = 1"
                . "AND cancelled_status = 0"
                . "ORDER BY created_datetime ASC";
            $bindParamClass[":datefrom"] = $datefrom;
            $bindParamClass[":dateto"] = $dateto;
            $bindParamClass[":classroom_id"] = $classroomId;
            $timeTable = $this->db->selectQuery($selectQueryClass, $bindParamClass); 
            if(is_array($timeTable) && count($timeTable) > 0){
                return $timeTable[0]['count'];
            }
        } catch (Exception $ex) {
            $this->transLog->log("Exception occured while getting total class count details = ".$ex->getMessage(), 2);
            return false;
        }  
	}
    
    //Check duplidate booking forthe given details
    public function checkDuplicateBookings($date, $time,  $day, $subject, $classroomId){
		try{
            $selectQueryUser = "SELECT count(*) as count FROM class_timetable "
                . "WHERE date = :date AND clasroom_id = :classroom_id AND day = :day AND subject_name = :subject "
                . "AND time_from >= :timefrom AND time_to <= :timeto AND active_status = 1"
                . "AND cancelled_status = 0"
                . "ORDER BY created_datetime ASC";
            $bindParamClass[":date"] = $date;
            $bindParamClass[":timefrom"] = $time;
            $bindParamClass[":timeto"] = $time;
            $bindParamClass[":subject"] = $subject;
            $bindParamClass[":day"] = $day;
            $bindParamClass[":classroom_id"] = $classroomId;
            $timeTable = $this->db->selectQuery($selectQueryClass, $bindParamClass); 
            return (is_array($timeTable) && count($timeTable) > 0) ? true : false;
        } catch (Exception $ex) {
            $this->transLog->log("Exception occured while getting duplicate records found = ".$ex->getMessage(), 2);
            return false;
        }
	}
}
?>