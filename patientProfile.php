<?php

	session_start();
	
	// prevent resubmission
	header("Cache-Control: no cache");
	session_cache_limiter("private_no_expire");

    if(isset($_POST['logOut'])){
        session_destroy();
        header("Location: index.php");
        exit();
    }
	
	if(isset($_SESSION['user_type'])){
	
		// check not patient
		if($_SESSION['user_type'] != 'patient'){
			header("Location: index.php");
			exit();
		}	
	
		// database connection
		include("database_con.php");
	
		// patient id
		$id =  $_SESSION['id'];
		
		// uploading image
		include("uploadImage.php");
		$image_error = "";
		if(upload_image("patient", $id) == TRUE){
			header("Location: "  . $_SERVER['PHP_SELF']);
			exit();
		}
			
		
		//####################### getting patient data #####################################
		$result = mysqli_query($con, "select * from patient where id = $id");
		if($row = mysqli_fetch_assoc($result)){
			// patient data
			$name = $row['name'];
			$address = $row['address'];
			$phone = $row['phone'];
			$email = $row['email'];
			$about = $row['about'];
			$imageName = $row['image'];
			
		}// ################################### END GETTING Patient's DATA #######################################
		
		
		// default city filter
		$city_filter = '';
		
		
		// after selecting city filter
		if(isset($_POST['city_filter']))
			$city_filter = $_POST['city_filter'];
	} 
	
	// Not logged in
	else {
	
		header("Location: index.php");
		exit();
	
	}
?>



<!DOCTYPE html>
<html>
    <head>
         <title>Patient</title>
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
                      <img src="<?php echo $imageName ?>" width="120"  height="120"class="img-circle" alt="doctor Pic">
                  </div>
                  <div class="info">
                      <h5>Welcome</h5>
                      <p class="lead"><?php echo $name ?></p>
                  </div>
                  <div class="clearfix"></div>
                  <div class="About-me">
                       <h3>About Me</h3>
                       <p><?php echo $about ?></p>
                  </div>
              </div>
          </div>
          <div class="col-md-9 col-12-sm">
             <div class="profile">
              <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                  <input id="log-out" type="submit" value="Log Out" name="logOut">
              </form>
               <div class="image">
                    <img src="<?php echo $imageName ?>" width="120"  height="120"class="img-circle" alt="doctor Pic">
                </div>
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post">
                    <input type="file" accept=".jpg,.jpeg,.png" name="file" >
					<span class="image-upload" align="center_horizontal"> <?php echo $image_error ?> </span>
                    <input type="submit" name="uploadImage" value="upload">
					</form>
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
                                    <p style="top:-25px"><?php echo $phone ?></p>
                                    <label>Email :</label>
                                    <p style="top:-25px"><?php echo $email ?></p>
                                     <form action="updatePatientAccount.php" method="post">
                                        <input type="submit" value="Update" class="update">
                                    </form>
									
									<ul class="list-unstyled">
										<h2>Medical Record:</h2>
										<form action="medicalRecordPatientView.php" method="post">
											<input style ="top:-19px" type="submit" value="View" class="Consultants-btn"> 
										</form>
									</ul>
                                    <ul class="list-unstyled">
                                        <h2>Consultants:</h2>
										<?php
											$result = mysqli_query($con, "select id, name from doctor where id in (select doctor_id from patient_doctor where patient_id = $id);");
											$counter = 0;
											while($row = mysqli_fetch_assoc($result)){
											
												$doc_id = $row['id'];
												$doc_name = $row['name'];
												
												echo " <li>																							";
												echo " 		<form action =\"DoctorView.php\" method=\"post\"> 										";
												echo "			<input type=\"hidden\" name=\"doctor_id\" value=\"$doc_id\"> 						";  
												echo "			<input type=\"submit\" value=\"Dr/$doc_name\" class=\"Consultants-btn\"> 			";
												echo "		</form> 																				";
												echo " </li> 																						";
												
												$counter++;
											}
											if($counter == 0){
												echo "<li> None </li>";
											}
                                        ?>
                                    </ul>
                                </div>
                            </div> 
                          </div>
                      </div>
                  </div>
              </div>
              <div class="patient col-md-12">
                  <h4 class="p-head">Reach Out For A hospital</h4>
                  <form action = "<?php echo $_SERVER['PHP_SELF'] ."#special" ?>" method="post">
                     <select name="city_filter" id="special">
						<?php
						
							echo "<option value=\"\" ";
							if($city_filter == '') echo " selected ";
							echo ">".htmlspecialchars("<No filter>")."</option> ";
						
							$result = mysqli_query($con, "SELECT city FROM hospital GROUP by city ORDER by city ASC;");
							while($row = mysqli_fetch_assoc($result)){
								$city = $row['city'];
								
								echo "<option value=\"$city\" ";
								if($city_filter == $city) echo " selected ";
								echo ">$city</option> ";
							}
						 
						 ?>
                      </select>
                      <input type="submit" value="Filter" class="one">
                  </form>
                  <table>
				  
						<?php
						
							$result = mysqli_query($con, "select id, name, city, image from hospital where city like '%$city_filter%' order by city ASC");
							while($row = mysqli_fetch_assoc($result)){
							
								$hos_id = $row['id'];
								$hos_name = $row['name'];
								$hos_city = $row['city'];
								$hos_image = $row['image'];
					
									echo "<tr>																									";
									echo "  <td class=\"t-image\">																				";
									echo "	  <img src=\"$hos_image\" class=\"img-responsive\">													";
									echo "  </td>																								";
									echo "  <td>																								";
									echo "	  <form action =\"hospitalView.php\" method=\"post\">												";
									echo "		  <input type =\"hidden\" name=\"hospital_id\" value=\"$hos_id\">								";
									echo "		  <input type=\"submit\" value=\"$hos_name\" class=\"name\">									";
									echo "	  </form>																							";
									echo "  </td>																								";
									echo "  <td>$hos_city</td>																					";
									echo "</tr>																									";
							  
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

