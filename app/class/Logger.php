<?php
if (defined('STDIN') ) {
  //echo("Running from CLI");
} else {
  //echo("Not Running from CLI");
  defined('START') or die;
}//close of else of if (defined('STDIN') ) {


/*
 * DB Class
 * This class is used for database related (connect, insert, update, and delete) operations
 */
 
trait Logger{

    public $logfile; 
    public $dbUsername;
    public $dbPassword;
    public $dbName;

    public function __construct($filename, $dailylog = false){	
        global $current_epoch, $siteLogPath;
        if($dailylog){        
            $logDir = $siteLogPath.df_convert_unix_timestamp_to_date_custom_timezone($current_epoch, "Asia/Kolkata", "d-m-Y");
            if(!is_dir($logDir)) mkdir($logDir,0755);
            $logFile = $logDir."/".$filename.".txt";	
        }else{         
            $logFile = $logDir."/".$filename.".txt";	
        }        
        $logFile = $logDir."/".$filename.".txt";	
        $this->logfile = $logFile;
    }
           
    /*
     * Delete data from the database
     * @param string name of the table
     * @param array where condition on deleting data
     */
    public function log($msg, $severity = 0){ 
        global $current_epoch;
        $datetime = df_convert_unix_timestamp_to_datetime_custom_timezone($current_epoch);		        
        $logMsg = "\n".$severity. ":: ".$datetime ." :: " . $msg;		
        file_put_contents($this->logfile , $logMsg, FILE_APPEND);
    }
    
    public function logNewSeperator(){ 
        global $current_epoch;
        $datetime = df_convert_unix_timestamp_to_datetime_custom_timezone($current_epoch);		        
        $logMsg = "\n0:: ".$datetime ." :: ------------------------- ";		
        file_put_contents($this->logfile , $logMsg, FILE_APPEND);
    }
}