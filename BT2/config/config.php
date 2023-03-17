<?php
$db_host= 'localhost';
$db_name= 'btth3_bai2';
$username='root';
$pass='';
try{
    $db = new PDO("mysql:host=$db_host;dbname=$db_name",$username,$pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo "Connection failed:".$e->getMessage();
}