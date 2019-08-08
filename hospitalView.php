<?php

	session_start();
	
	// prevent resubmission
	header("Cache-Control: no cache");
	session_cache_limiter("private_no_expire");
	
	
	if(isset($_POST['hospital_id'])){
		$_SESSION['last_hospital_id'] = $_POST['hospital_id'];
	} else {
		if(!isset($_SESSION['last_hospital_id'])){
			header("Location: index.php");
			exit();
		}
	}
	
	
	
	include("database_con.php");
	$hospital_id = $_SESSION['last_hospital_id'];
	
	// getting hospital data
	$result = mysqli_query($con, "select * from hospital where id = $hospital_id");
	if($row = mysqli_fetch_assoc($result)){
		$name 				= 	$row['name'];
		$city 				= 	$row['city'];
		$detailed_address 	= 	$row['detailed_address'];
		$email 				= 	$row['email'];
		$image				= 	$row['image'];
		$about 				= 	$row['about'];
	}
	
		

?>



<!DOCTYPE html>
<html>
    <head>
         <title>Hospital</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/doctor.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="shortcut icon" href="images/logo.png">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <!--[if lt IE 9]>
          <script src="js/html5shiv.min.js"></script>
          <script src="js/respond.min.js"></script>
        <![endif]-->
  </head>
  <body>
<!--start Header-->
    <div class="doctor-profile">
         <div class="row">
          <div class="col-md-3 hidden-xs hidden-sm">
              <div class="doctor">
                  <div class="image">
                      <img src="<?php echo $image; ?>" class="img-circle" alt="doctor Pic" width="120" height="120">
                  </div>
                  <div class="info">
                      <p class="lead"><?php echo $name; ?></p>
                  </div>
                  <div class="clearfix"></div>
                  <div class="About-me">
                       <h3>About Hospital</h3>
                       <p><?php echo $about; ?> </p>
                  </div>
              </div>
          </div>
          <div class="col-md-9 col-12-sm">
             <div class="profile">
               <div class="image">
                    <img src="<?php echo $image; ?>" class="img-circle" width="120" height="120">
                     <a class="log-out" href="index.php">Back To Profile</a>
                   
                </div>
             </div>
              <div class="doctor-info">
                  <div class="row">
                      <div class="col-md-12">
                          <div class="details">
                            <div class="col-md-12">
                              <div class="information">
                                   <label>Address :</label>
                                    <p style="top:-25px"><?php echo $detailed_address . ' - ' . $city; ?></p>
                                    <label>Email :</label>
                                  <p style="top:-25px"><?php echo $email; ?></p>
                                </div>
                            </div> 
                          </div>
                      </div>
                  </div>
              </div>
              <div class="patient col-md-12">
                  <h4>Doctors</h4>
                  <table>
						<?php
							
							$result = mysqli_query($con, "select * from doctor where hospital_id = $hospital_id");
							while($row = mysqli_fetch_assoc($result)){
								$doc_id = $row['id'];
								$doc_name = $row['name'];
								$doc_image = $row['image'];
							
								echo"  <tr>																												";
								echo"	  <td class=\"t-image\">																						";
								echo"		  <img src=\"$doc_image\" class=\"img-responsive\">															";
								echo"	  </td>																											";
								echo"	  <td>																											";
								echo"		  <form action=\"DoctorView.php\" method=\"post\">															";
								echo"			  <input type=\"hidden\" name=\"doctor_id\" value=\"$doc_id\">											";
								echo"			  <input type=\"submit\" value=\"$doc_name\" class=\"name\">											";
								echo"		  </form>																									";
								echo"	  </td>																											";
								echo"  </tr>																											";
								
							}
						?>
                  </table>
              </div>
             </div>
        </div>
    </div>
<!--end Header-->
  </body>
</html>
