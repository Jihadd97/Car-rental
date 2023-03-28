<?php
include_once("conn.php");
if($_SERVER["REQUEST_METHOD"] === "POST"){
try{
	$sql="DELETE FROM `phone_list` WHERE `id`=?;";
	$id = $_GET["id"];
   // $image = $image_name;
	$stmt = $conn-> prepare($sql);
	$stmt -> execute([$id]);
	echo "Deleted Successfully";
	header("Location: ../cars.php");
        exit();

}catch(PDOException $e){
		echo "Connection failed: " . $e->getMessage();
  }
}

?>