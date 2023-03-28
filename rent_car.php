<?php
    include_once("admin/conn.php");
    try{ 
        $stmt = $conn->prepare("SELECT * FROM `insert_car`");
        $stmt->execute();   
    }
    catch(PDOException $e){
      echo "Error: " . $e->getMessage();
    }
?>

<!-- Rent A Car Start -->
<div class="container-fluid py-5">
        <div class="container pt-5 pb-3">
            <h1 class="display-1 text-primary text-center">03</h1>
            <h1 class="display-4 text-uppercase text-center mb-5">Find Your Car</h1>
            <div class="row">

        <?php
            foreach($stmt->fetchAll() as $k){
               $id = $k["id"]; 
               $title= $k["car_title"];
               $content= $k["car_content"];
               $price = $k["car_price"];
               $model = $k["car_model"];
               $type = $k["transmission_type"];
               $fuel = $k["fuel_consumption"];
               $properties = $k["car_properties"];
               $image = "/img/".$k["car_image"];
        ?>
                <div class="col-lg-4 col-md-6 mb-2">
                    <div class="rent-item mb-4">
                        <img class="img-fluid mb-4" src="img/<?php echo $image;?>" alt="<?php echo $title ;?>">
                        <a href="booking.php?id=<?php echo $id;?>"><h4 class="text-uppercase mb-4"><?php echo $title;?></h4></a>
                        <div class="d-flex justify-content-center mb-4">
                            <div class="px-2">
                                <i class="fa fa-car text-primary mr-1"></i>
                                <span><?php echo $model;?></span>
                            </div>
                            <div class="px-2 border-left border-right">
                                <i class="fa fa-cogs text-primary mr-1"></i>
                                <span><?php echo $type;?></span>
                            </div>
                            <div class="px-2">
                                <i class="fa fa-road text-primary mr-1"></i>
                                <span><?php echo $fuel;?>K</span>
                            </div>
                        </div>
                        <a class="btn btn-primary px-3" href="booking.php?id=<?php echo $id ?>">$<?php echo $price;?>/Day</a>
                    </div>
                </div>
            <?php
            }
            ?> 
            </div>
        </div>
    </div>
    <!-- Rent A Car End -->