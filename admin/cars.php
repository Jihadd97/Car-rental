<?php
session_start();
if(!isset($_SESSION["Log"]) or $_SESSION["Log"]!=true){
  header("Location:login.php");
  exit;
}

include_once("conn.php");
try
{
    $stmt = $conn->prepare("SELECT  `id`,`car_title`,`car_price`,`car_model`,`car_image` FROM `insert_car` ;");
	  $stmt -> execute();
    
    if(isset($_REQUEST['id'])) {
    $sql="DELETE FROM `insert_car` WHERE `id`=?;";
    $id = $_GET["id"];
    $stmt = $conn-> prepare($sql);
    $stmt -> execute([$id]);
    echo "Deleted Successfully";
    header("Location:cars.php");
    exit();
  } 
}catch(PDOException $e){
      echo "Connection failed: " . $e->getMessage();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Table V01</title>
    <link rel="stylesheet" href="cars.css">
</head>
<body>
    <body>
        <div id="wrapper">
         <h1>Cars List</h1>
         
         <table id="keywords" cellspacing="0" cellpadding="0">
           <thead>
        
             <tr>
               <th><span>Car Title</span></th>
               <th><span>Price</span></th>
               <th><span>Model</span></th>
               <th><span>Image</span></th>
               <th><span>Delete</span></th>
               <th><span>Update</span></th>
               
             </tr>
           </thead>
           <tbody>


        <?php
        foreach($stmt->fetchAll() as $k){
          $id    = $k["id"];
          $title = $k["car_title"];
          $price = $k["car_price"];
          $model = $k["car_model"];
          $image = $k["car_image"];
		    ?>
             <tr>
              
               <td class="lalign"><?php echo $title;?></td>
               <td><?php echo $price;?></td>
               <td><?php echo $model;?></td>
               
               <td><img src="../img/<?php echo $k['car_image']; ?>"style="width:90px; height:55px;"></td>
               <input type = "hidden" name ="id" value="<?php echo $id?>">
               <td><a onClick="javascript: return confirm('Please confirm deletion');"href="cars.php?id=<?php echo $id?>"><img src='../img/delete.jpg'style="width:40px;height:40px;"></i></a></td>
               <td><a href="UpdateCar.php?id=<?php echo $id;?>"><img src='../img/update.jpg'style="width:40px;height:40px;"></a></td>
             </tr>
        <?php
             }
        ?>
             
           </tbody>
         </table>
        </div> 
       </body>
</body>
</html>
