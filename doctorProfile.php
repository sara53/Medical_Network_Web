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
	
		// check not doctor
		if($_SESSION['user_type'] != 'doctor'){
			header("Location: index.php");
			exit();
		}	
	
		// database connection
		include("database_con.php");
	
		// doctor id
		$id =  $_SESSION['id'];
		
		// uploading image
		include("uploadImage.php");
		$image_error = "";
		if(upload_image("doctor", $id) == TRUE){
			header("Location: "  . $_SERVER['PHP_SELF']);
			exit();
		}
		
		
		//####################### getting doctor data #####################################
		$result = mysqli_query($con, "select * from doctor where id = $id");
		if($row = mysqli_fetch_assoc($result)){
		
			// doctor data
			$name = $row['name'];
			$address = $row['address'];
			$phone = $row['phone'];
			$email = $row['email'];
			$hospital_id = $row['hospital_id'];
			$specialty_id = $row['specialty_id'];
			$about = $row['about'];
			$imageName = $row['image'];
			
			// getting doctor's hospital name 
			$result = mysqli_query($con, "select name from hospital where id = $hospital_id");
			if($row = mysqli_fetch_assoc($result)){
				$hospital_name = $row['name'];
			} else {
				$hospital_name = "none";
			}
			
			// getting doctor's specialty name
			$result = mysqli_query($con, "select name from specialties where id = $specialty_id");
			if($row = mysqli_fetch_assoc($result)){
				$specialty_name = $row['name'];
			} else {
				$specialty_name = "none";
			}
		}// ################################### END GETTING DOCTOR's DATA #######################################

		// default value for adding_patient_error message
		$adding_patient_error = "";
		
		// Add patient
		if(isset($_POST['patient-id'])){
			$pid = $_POST['patient-id'];
			
			$result = mysqli_query($con, "select * from patient where id = $pid;");
			if($row = mysqli_fetch_assoc($result)){
				$result = mysqli_query($con, "select * from patient_doctor where patient_id = $pid and doctor_id = $id;");
				if($row = mysqli_fetch_assoc($result)){
					$adding_patient_error = "Patient already added";
				} else {
					mysqli_query($con, "insert into patient_doctor values($pid, $id);");
				}
			} else {
				$adding_patient_error = "Invalid input/non existing id";
			}
		}
		
		// Deleting Patient
		if(isset($_POST['deleting_patient'])){
			$pid = $_POST['deleted_patient_id'];
			mysqli_query($con, "delete from patient_doctor where patient_id = $pid and doctor_id = $id");
		}

	
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
                      <img src="<?php echo $imageName ?>" width="120" height="120" class="img-circle" alt="doctor Pic">
                  </div>
                  <div class="info">
                      <h5>Welcome </h5>
                      <p class="lead"><?php echo 'Dr/' . $name ?></p>
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
                    <img src="<?php echo $imageName ?>" width="120" height="120" class="img-circle">
                </div>
                 
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post">
                    <input type="file" accept=".jpg,.jpeg,.png" name="file" >
					<span class="image-upload"> <?php echo $image_error ?> </span>
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
                                     <label>Specialty :</label>
                                    <p style="top:-25px"><?php echo $specialty_name ?></p>
                                    <form action="updateDoctorAccount.php" method="post">
                                        <input type="submit" value="Update info" class="update">
                                    </form>
                                </div>
                            </div> 
                          </div>
                      </div>
                  </div>
              </div>
              <div class="patient col-md-12">
                  <h4>Your Patient</h4>
                  <form action = "<?php echo $_SERVER['PHP_SELF'] . "#patient_table"; ?>" method="post">
                      <input type="text" required name="patient-id" placeholder="New Patient Id">
                      <input type="submit" value="Add" class="one">
                  </form>
				  <span class="patient-added"> <?php echo $adding_patient_error;  ?> </span>
                  <table id = "patient_table">
                      <?php
						  $result = mysqli_query($con, "select patient.id, patient.name, patient.image from patient where patient.id in (select patient_id from patient_doctor where doctor_id = $id);");
						  while($row = mysqli_fetch_assoc($result)){
						  
						  $pid = $row['id'];
						  $pname = $row['name'];
						  $pimage = $row['image'];
						  
						  $action_delete = $_SERVER['PHP_SELF'] . "#patient_table";
						  $action_select = "medicalRecordDoctorView.php";
						
						  
						  echo "<tr>																								";
						  echo "    <td class=\"t-image\">																			";
						  echo "        <img src=\"$pimage\" class=\"img-responsive\">												";
						  echo "    </td>																							";
						  echo "    <td>$pid</td>																					";
						  echo "    <td>																							";
						  echo "        <form action = \"$action_select\" method = \"post\">										";
						  echo "			<input type = \"hidden\" name = \"selected_patient_id\" value = \"$pid\" />				";	
						  echo "            <input type=\"submit\" value=\"$pname\" class=\"name\">									";
						  echo "        </form>																						";
						  echo "    </td>																							";
						  echo "    <td>																							";
						  echo "        <form action=\"$action_delete\" method=\"post\" >											";
						  echo "			<input type = \"hidden\" name = \"deleted_patient_id\" value = \"$pid\" />				";	
						  echo "            <input type=\"submit\" name=\"deleting_patient\" value=\"delete\">						";
						  echo "        </form>																						";
						  echo "    </td>																							";
						  echo "</tr>																								";
						  
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
