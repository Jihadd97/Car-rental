<?php
session_start();
if(!isset($_SESSION["Log"])or $_SESSION["Log"]!=true){
  header("Location:login.php");
  exit;
}

include_once("conn.php");
try
{
  
  $stmt = $conn->prepare("SELECT `id`, `client_name`, `client_position` FROM `testimonials`;");
	$stmt -> execute();
    
    if(isset($_REQUEST['id'])) {
        $sql="DELETE FROM `testimonials` WHERE `id`=?;";
        $id = $_GET["id"];
        $stmt = $conn-> prepare($sql);
        $stmt -> execute([$id]);
        echo "Deleted Successfully";
        header("Location:testimonials.php");
        exit();
  } 
}catch(PDOException $e){
      echo "Connection failed: " . $e->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Testimonials</title>
    <link rel="stylesheet" href="cars.css">
</head>
<body>
    <body>
        <div id="wrapper">
         <h1>Testimonials List</h1>
         
         <table id="keywords" cellspacing="0" cellpadding="0">
           <thead>
             <tr>
               <th><span>Name</span></th>
               <th><span>Position</span></th>
               <th><span>Delete</span></th>
               <th><span>Update</span></th>
             </tr>
           </thead>
           <tbody>
        <?php
            foreach($stmt->fetchAll() as $k){ 
                $id= $k["id"];
                $name= $k["client_name"];
                $position = $k["client_position"];
        ?>
             <tr>
               <td class="lalign"><?php echo $name;?></td>
               <td><?php echo $position;?></td>
               <input type = "hidden" name ="id" value="<?php echo $id?>">
               <td><a onClick="javascript: return confirm('Please confirm deletion');"href="testimonials.php?id=<?php echo $id?>"><img src="../img/delete.jpg" style="width:40px;height:40px;"></i></a></td>
               <td><a href="UpdateTestimonials.php?id=<?php echo $id;?>"><img src="../img/update.jpg"style="width:40px;height:40px;"></i></a></td>
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
