<?php
/*
$userName = "id20357809_jihad97";
$password = "+eq(&9R{b93}s\vV";
dbname=id20357809_car_rental
$serverName = "localhost";
*/
$serverName = "localhost";
$userName = "root";
$password = "";

try{
    $conn = new PDO("mysql:host=$serverName;dbname=car_rental", $userName, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   // echo "Connected successfully";
}catch(PDOException $e){
      echo "Connection failed: " . $e->getMessage();
}
?>