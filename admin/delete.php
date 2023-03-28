<?php
//in case deleting and going to other page
include_once("conn.php");
try{
	$sql="DELETE FROM `insert_car` WHERE `id`=?;";
	$id = $_GET["id"];
	$stmt = $conn-> prepare($sql);
	$stmt -> execute([$id]);
	echo "Deleted Successfully";
	header("Location: admin/cars.php");
      exit();

}catch(PDOException $e){
		echo "Connection failed: " . $e->getMessage();
  }


?>