<?php

	session_start();
	
	// prevent resubmission
	header("Cache-Control: no cache");
	session_cache_limiter("private_no_expire");
	
	
	// check going directly to this page
	if(isset($_POST['doctor_id'])){
		$_SESSION['last_doctor_id'] = $_POST['doctor_id'];
	} else {
		if(!isset($_SESSION['last_doctor_id'])){
			header("Location: index.php");
			exit();
		}
	}
	
	// database connection
	include("database_con.php");
	$doctor_id = $_SESSION['last_doctor_id'];
	
	// getting doctor data
	$result = mysqli_query($con, "select * from doctor where id = $doctor_id");
	if($row = mysqli_fetch_assoc($result)){
		$name 				= 	$row['name'];
		$phone				=	$row['phone'];
		$address			=	$row['address'];
		$email 				= 	$row['email'];
		$image				= 	$row['image'];
		$about 				= 	$row['about'];
		$hospital_id		=	$row['hospital_id'];
		$specialty_id		=	$row['specialty_id'];
		
		// get specialty name
		$result2 = mysqli_query($con, "select name from specialties where id = $specialty_id");
		if($row2 = mysqli_fetch_assoc($result2)){
			$specialty_name = $row2['name'];
		} else {
			$specialty_name = "none";
		}
		
		// get hospital name
		$result2 = mysqli_query($con, "select name from hospital where id = $hospital_id");
		if($row2 = mysqli_fetch_assoc($result2)){
			$hospital_name = $row2['name'];
		} else {
			$hospital_name = "none";
		}
	}

?>


<!DOCTYPE html>
<html>
    <head>
         <title>Doctor</title>
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
                      <img class="img-circle" src="<?php echo $image ?>" width="120" height="120">
                  </div>
                  <div class="info">
                      <p class="lead"><?php echo "Dr/$name" ?></p>
                  </div>
                  <div class="clearfix"></div>
                  <div class="About-me">
                       <h3>About Doctor</h3>
                       <p><?php echo $about ?></p>
                  </div>
              </div>
          </div>
          <div class="col-md-9 col-12-sm">
             <div class="profile">
               <div class="image">
                    <img src="<?php echo $image ?>" class="img-circle" width="120" height="120">
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
                                    <p style="top:-25px"><?php echo $address ?></p>
                                    <label>Phone :</label>
                                    <p style="top:-25px"><?php echo $phone ?> </p>
                                    <label>Email :</label>
                                    <p style="top:-25px"><?php echo $email ?></p>
                                    <label>Hospital :</label>
									<?php
										if($hospital_id == -1)echo "<p style=\"top:-25px\"> None </p>";
										else {
											echo "	 <form action = \"hospitalView.php\" method=\"post\">										";
											echo "		<input type=\"hidden\" name=\"hospital_id\" value=\"$hospital_id\">						";
											echo "		<input style=\"left:20px\" type=\"submit\" value=\"$hospital_name\">										";
											echo "	</form>																						";
										}
									?>
                                     <label>Speciality :</label>
                                    <p style="top:-25px"><?php echo $specialty_name ?></p>
                                </div>
                            </div> 
                          </div>
                      </div>
                  </div>
              </div>
             </div>
        </div>
    </div>
<!--end Header-->
  </body>
</html>
