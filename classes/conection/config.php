<?php 
if(!defined('DB_SERVER')){
    require_once('initialize.php');
}
    class config{
       private $host = DB_SERVER;
       private $user = DB_USERNAME;
       private $password = DB_PASSWORD;
       private $database = DB_NAME;

       public $conn;

       public function __construct(){
         if(!isset($this->conn)){
            $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);

            if(!$this->conn){
                echo 'Cannot connect to dabase server';
            }
         }
       }
       public function __destruct(){
            $this->conn->close();
        }
    }
?>