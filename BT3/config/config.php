<?php
class config{
    private $db ;
    private $db_host="localhost";
    private $db_name="btth3_bai3";
    private $username="root";
    private $pass="";
    public function __construct(){
        try{
            $this->db = new PDO("mysql:$this->db_host;$this->db_name",$this->username,$this->pass);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }
    public function getDB() {
        return $this->db;
    }
}