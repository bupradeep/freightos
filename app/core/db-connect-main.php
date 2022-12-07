<?php

$dbconnection_active = "dev";
$debug_mode = "ON";
  
    if($dbconnection_active == "live")
    {
   
    
   
    $dbhost_site = "localhost";
    $dbusername_site = "root";
    $dbpassword_site = "root";
    $dbname_site = "freightos";
    
   


    }
    elseif($dbconnection_active == "dev")
    {
       
    $dbhost_site = "localhost";
    $dbusername_site = "freightos1";
    $dbpassword_site = "freightos1";
    $dbname_site = "freightos";
        
    }
    //http://forums.devshed.com/php-faqs-stickies-167/properly-access-mysql-database-php-954131.html        
    if(($dbconnection_active != "") && (($dbconnection_active == "dev") || ($dbconnection_active == "live")))
    {
       try {
           
				/* Uncomment, after adding Database Details in the above DB Configuration */
					if (($dbhost_site != "") && ($dbusername_site != "") && ($dbpassword_site != "") && ($dbname_site != "")) {
					  //$dbcon = new PDO("mysql:host=$dbhost_site;dbname=$dbname_site", $dbusername_site, $dbpassword_site);
					  //$dbcon = new PDOEx("mysql:host=$dbhost_pg;dbname=$dbname_pg", $dbusername_pg, $dbpassword_pg);
					  $dbcon = new PDOEx("mysql:host=$dbhost_site;dbname=$dbname_site;port=3306;charset=utf8mb4", $dbusername_site, $dbpassword_site);
					  // throw exceptions in case of errors (default: stay silent)
					  $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					  $dbcon->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
					  // fetch associative arrays (default: mixed arrays)
					  $dbcon->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
					}
				
			}
			catch (PDOException $e) {
			
			  if($debug_mode == "ON")
				{
					print "Error!: " . $e->getMessage() . "<br>";
				  //var_dump($e);
				  die();
				}
				elseif($debug_mode == "OFF")
				{
					echo "db related error!!!";
				  die();
				}
			}
    } //dbconnection active close tag
    
?>
