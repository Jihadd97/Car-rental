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
	$image=$_POST["oldImage"]; // to get the old image
	
	}
	
try{
		$sql="UPDATE `testimonials` SET `client_name`=?,`client_position`=?,`client_perspective`=?,`client_image`=? where id=?;";
		$id = $_POST["id"];
		$client_name =$_POST["Name"];
		$client_position = $_POST["position"];
		$perspective = $_POST["content"];
		$stmt = $conn-> prepare($sql);
        $stmt -> execute([$client_name,$client_position,$perspective,$image,$id]);
		//echo "Updated Successfully";  
		header("Location: testimonials.php");
      	exit();
  
	}catch(PDOException $e){
			echo "Connection failed: " . $e->getMessage();
	}
} else {
	$id = $_GET["id"]; // to display the information from database in case not post
}

try{
	$stmt = $conn->prepare("SELECT * FROM `testimonials` where id = ?;");
	$stmt->execute([$id]);
	$result = $stmt->fetch();
	
}catch(PDOException $e) {
	echo "Error: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Edit / Update Testimonials</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
	</head>

	<body>
		<div class="container">
			<form method ="POST" action = "<?php echo $_SERVER['PHP_SELF']?>" class="m-auto" style="max-width:600px" enctype="multipart/form-data">
				<h3 class="my-4">Edit / Update Testimonials</h3>
				<hr class="my-4" />
				<div class="form-group mb-3 row"><label for="title2" class="col-md-5 col-form-label">Name</label>
					<div class="col-md-7"><input type="text" value ="<?php echo $result["client_name"];?>" class="form-control form-control-lg" id="title2" name="Name" required></div>
				</div>
				<hr class="bg-transparent border-0 py-1" />
				<div class="form-group mb-3 row"><label for="price6" class="col-md-5 col-form-label">Position</label>
					<div class="col-md-7"><input type="text" value ="<?php echo $result["client_position"];?>" class="form-control form-control-lg" id="price6" name="position"></div>
				</div>
				<hr class="bg-transparent border-0 py-1" />
				<div class="form-group mb-3 row"><label for="content4" class="col-md-5 col-form-label">Content</label>
					<div class="col-md-7"><textarea class="form-control form-control-lg" id="content4" name="content" required><?php echo $result["client_perspective"];?></textarea></div>
				</div>
				<hr class="my-4" />
				<div>
                <div>
					<img src="../img/<?php echo $result['client_image']; ?>" alt="<?php echo $result['client_name'];?>" style="width: 100px; height:60px;">
					<br>
					<label for="image" class="col-md-5 col-form-label">Select Image</label>
					<input type="file"value="<?php echo $result['client_image']; ?>" id="image" name="image" accept="image/*">
					<input type = "hidden" name ="id" value="<?php echo $id?>">
					<input type = "hidden" name ="oldImage" value ="<?php echo $result['client_image'] ;?>">
					
				</div>
				<hr class="my-4" />
				<div class="form-group mb-3 row"><label for="insert10" class="col-md-5 col-form-label"></label>
					<div class="col-md-7"><button class="btn btn-primary btn-lg" type="submit">Update</button></div>
				</div>
			</form>
		</div>
	</body>

</html>