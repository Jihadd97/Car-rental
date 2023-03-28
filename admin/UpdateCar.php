<?php
session_start();
if(!isset($_SESSION["Log"])or $_SESSION["Log"]!=true){
  header("Location:login.php");
  exit;
}

include_once("conn.php");
if($_SERVER["REQUEST_METHOD"] === "POST"){

 // Get reference to uploaded image
 $image_file = $_FILES["image"]; //image is the form input file element name

 // Exit if no file uploaded
 if (!isset($image_file)) {
	 die('No file uploaded.');
 }

 // Add old image details if no file uploaded or replace the new image
 if ($image_file["tmp_name"] != "") {
 // Exit if image file is zero bytes
 	if (filesize($image_file["tmp_name"]) <= 0) {
		 die('Uploaded file has no contents.'); }
		

 // Exit if is not a valid image file
 $image_type = exif_imagetype($image_file["tmp_name"]);
 if (!$image_type) {
	 die('Uploaded file is not an image.');
 }

 // Get file extension based on file type, to prepend a dot we pass true as the second parameter
 $image_extension = image_type_to_extension($image_type, true);

 // Create a unique image name
 $image_name = bin2hex(random_bytes(16)) . $image_extension;

 // Move the temp image file to the images directory
 move_uploaded_file(
	 // Temp image location
	 $image_file["tmp_name"],

	 // New image location
	 __DIR__ . "/../img/" . $image_name );
	 $image=$image_name;

 } else {
	$image=$_POST["oldImage"];
	
	}
	
try{
		$sql="UPDATE `insert_car` SET `car_title`=?,`car_content`=?,`car_price`=?,`car_model`=?,`transmission_type`=?,`fuel_consumption`=?,`car_properties`=?,`car_image`=? where id=?;";
		$id = $_POST["id"];
		$car_title =$_POST["title"];
		$car_content = $_POST["content"];
		$car_price = $_POST["price"];
		$car_model = $_POST["model"];
		$transmission_type = $_POST["type"];
        $fuel_consumption = $_POST["fuel"];
        $car_properties = $_POST["properties"];
		$stmt = $conn-> prepare($sql);
        $stmt -> execute([$car_title,$car_content,$car_price,$car_model,$transmission_type,$fuel_consumption,$car_properties,$image,$id]);
		echo "Updated Successfully";  
		header("Location: cars.php");
      	exit();
  
	}catch(PDOException $e){
			echo "Connection failed: " . $e->getMessage();
	}
}else{
	$id= $_GET["id"];
}
try{
	$stmt1 = $conn->prepare("SELECT * FROM `insert_car` where id = ?;");
	$stmt1->execute([$id]);
	$result = $stmt1->fetch();
	$category=$result['transmission_type'];
	//get all categories and upload to the drop down list
	$stmt2 = $conn->prepare("SELECT DISTINCT transmission_type FROM insert_car;");
	$stmt2->execute();

}catch(PDOException $e) {
	echo "Error: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Edit / Update Car</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
	</head>

	<body>
		<div class="container">
			<form method= "POST" action="<?php echo $_SERVER['PHP_SELF']?>" class="m-auto" style="max-width:600px" enctype="multipart/form-data">
				<h3 class="my-4">Edit / Update Car</h3>
				<hr class="my-4" />
				<div class="form-group mb-3 row"><label for="title2" class="col-md-5 col-form-label">Car Title</label>
					<div class="col-md-7"><input type="text" value ="<?php echo $result["car_title"];?>" class="form-control form-control-lg" id="title2" name="title" required></div>
				</div>
				<hr class="bg-transparent border-0 py-1" />
				<div class="form-group mb-3 row"><label for="content4" class="col-md-5 col-form-label">Content</label>
					<div class="col-md-7"><textarea class="form-control form-control-lg" id="content4" name="content" required><?php echo $result["car_content"];?></textarea></div>
				</div>
				<hr class="bg-transparent border-0 py-1" />
				<div class="form-group mb-3 row"><label for="price6" class="col-md-5 col-form-label">Price</label>
					<div class="col-md-7"><input type="text" class="form-control form-control-lg" value="<?php echo $result["car_price"];?> "id="price6" name="price"></div>
				</div>
				<hr class="bg-transparent border-0 py-1" />
				<div class="form-group mb-3 row"><label for="model6" class="col-md-5 col-form-label">Model</label>
					<div class="col-md-7"><input type="text" class="form-control form-control-lg"value="<?php echo $result["car_model"];?>" id="model6" name="model" ></div>
				</div>
				<hr class="bg-transparent border-0 py-1" />
				<div class="form-group mb-3 row"><label for="select-option1" class="col-md-5 col-form-label">Auto / Manual</label>
					<div class="col-md-7"><select class="form-select custom-select custom-select-lg" id="select-option1" name="type">
					<?php	
						foreach($stmt2->fetchAll() as $k){
							$currentCategory=$k["transmission_type"];
							if($category == $currentCategory){
								echo "<option selected>$currentCategory</option>";
							}else{
								echo "<option selected>$currentCategory</option>";
							}
							}
					?>		
						</select></div>
				</div>
                <hr class="bg-transparent border-0 py-1" />
				<div class="form-group mb-3 row"><label for="model6" class="col-md-5 col-form-label">Fuel Consumption</label>
					<div class="col-md-7"><input type="text" value="<?php echo $result["fuel_consumption"];?>" class="form-control form-control-lg" id="model6" name="fuel"></div>
				</div>
                
				<hr class="bg-transparent border-0 py-1" />
				<div class="form-group mb-3 row"><label for="properties6" class="col-md-5 col-form-label">Properties</label>
					<div class="col-md-7"><input type="text" value="<?php echo $result["car_properties"];?>" class="form-control form-control-lg" id="properties6" name="properties"></div>
				</div>
				<hr class="my-4" />
				<div>
					<img src="../img/<?php echo $result['car_image']; ?>" alt="<?php echo $result['car_title'];?>" style="width: 100px; height:60px;">
					<br>
					<label for="image" class="col-md-5 col-form-label">Select Image</label>
					<input type="file"value="<?php echo $result['car_image']; ?>" id="image" name="image" accept="image/*">
					<input type = "hidden" name ="id" value="<?php echo $id?>">
					<input type = "hidden" name ="oldImage" value ="<?php echo $result['car_image'] ;?>">
					
				</div>
				<hr class="my-4" />
				<div class="form-group mb-3 row"><label for="insert10" class="col-md-5 col-form-label"></label>
					<div class="col-md-7"><button class="btn btn-primary btn-lg" type="submit">Update</button></div>
				</div>
			</form>
		</div>
	</body>

</html>