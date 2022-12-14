<?php

//http://stackoverflow.com/a/12850992
//http://stackoverflow.com/questions/1290867/count-number-of-mysql-queries-executed-on-page
//http://stackoverflow.com/questions/12850886/count-number-of-queries-each-page-load-with-pdo

//https://github.com/geoloqi/PHP-PDO-Improved/issues/1#issuecomment-18292492
    class PDOEx extends PDO
      {
          private $queryCount = 0;
      
       /*   public function connect($dsn, $user, $pass)
          {
            $options = array(
                PDO::ATTR_STATEMENT_CLASS => array('MyPDOStatement'),
            );
            parent::__construct($dsn, $user, $pass, $options);
          } 
          
          function __construct($connect_str, $username, $password)
           {
             parent::__construct($connect_str, $username, $password);
           }  */

          public function query($query)
          {
          // Increment the counter.
              ++$this->queryCount;
      
          // Run the query.
              return parent::query($query);
          }
      
          public function exec($statement)
          {
          // Increment the counter.
              ++$this->queryCount;
      
          // Execute the statement.
              return parent::exec($statement);
          }
          
          public function prepare($statement, $driver_options = array())
          {
          // Increment the counter.
              ++$this->queryCount;
      
          // Execute the statement.
              return parent::prepare($statement, $driver_options);
          }
      
          public function GetCount()
          {
              return $this->queryCount;
          }
      }
?>