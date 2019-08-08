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
	
		// check not hospital
		if($_SESSION['user_type'] != 'hospital'){
			header("Location: index.php");
			exit();
		}	
	
		// database connection
		include("database_con.php");
	
		// hospital id
		$id =  $_SESSION['id'];
		
		// uploading image
		include("uploadImage.php");
		$image_error = "";
		if(upload_image("hospital", $id) == TRUE){
			header("Location: "  . $_SERVER['PHP_SELF']);
			exit();
		}
		
		//####################### getting hospital data #####################################
		$result = mysqli_query($con, "select * from hospital where id = $id");
		if($row = mysqli_fetch_assoc($result)){	
			// hospital data
			$name = $row['name'];
			$city = $row['city'];
			$detailed_address = $row['detailed_address'];
			$email = $row['email'];
			$about = $row['about'];
			$imageName = $row['image'];
			
		}// ################################### END GETTING Patient's DATA #######################################
		
		
		
		// add specialty
		$add_spec_error = "";
		if(isset($_POST['add_spec'])){
			$spec_name = $_POST['spec_name'];
			$result = mysqli_query($con, "select name from specialties where hospital_id = $id and name like '$spec_name'");
			if($row = mysqli_fetch_assoc($result)){
				$add_spec_error = "Specialty already existing";
			} else {
				mysqli_query($con, "insert into specialties(name, hospital_id) values ('$spec_name', $id)");
			}
		}
		
		// delete specialty
		if(isset($_POST['delete_spec'])){
			$spec_id = $_POST['spec_id'];
			mysqli_query($con, "update doctor set specialty_id = -1 where specialty_id = $spec_id");
			mysqli_query($con, "delete from specialties where id = $spec_id");
		}
		
		
		// delete doc
		if(isset($_POST['delete_doc'])){
			$doc_id = $_POST['doctor_id'];
			mysqli_query($con, "update doctor set specialty_id = -1, hospital_id = -1 where id = $doc_id");
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
                      <img src="<?php echo $imageName ?>" class="img-circle" alt="doctor Pic" width="120" height="120">
                  </div>
                  <div class="info">
                      <h5>Welcome</h5>
                      <p class="lead"><?php echo $name ?></p>
                  </div>
                  <div class="clearfix"></div>
                  <div class="About-me">
                       <h3>About Hospital</h3>
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
                    <img src="<?php echo $imageName ?>" class="img-circle" width="120" height="120">
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
                                    <p style="top:-25px"><?php echo $detailed_address . ' - ' . $city ?></p>
                                    <label>Email :</label>
                                    <p style="top:-25px"><?php echo $email ?></p>
									
									<p><form action="updateHospitalAccount.php" method="post">
										<input type="submit" value="Update data" class="add-update">
									</form></p>
									
									
									
                                    <form id = "specs" action="<?php echo $_SERVER['PHP_SELF'] . "#specs" ?>" method="post">
                                      <input required type="text" name="spec_name" placeholder="New Specialty">
                                      <input type="submit" name="add_spec" value="Add" class="add-update">
									</form>
									
									<span style="color:red;"><?php echo $add_spec_error ?></span>
									
									<p></p>
									<p></p>
							  
									
									
									<ul>
										<li style="margin-top:45px">
											<p class="o">Specialties: </p>
											<ul>
											
												<?php 
													
													$result = mysqli_query($con, "select * from specialties where hospital_id = $id");
													$counter=0;
													while($row = mysqli_fetch_assoc($result)){
														
														$spec_id = $row['id'];
														$spec_name = $row['name'];
														$action = $_SERVER['PHP_SELF'] . "#specs";
													
														echo " <li>																							";
														echo "	<p>$spec_name																				";
														echo "	<form action = \"$action\" method=\"post\">													";
														echo "		<input type=\"hidden\" name=\"spec_id\" value=\"$spec_id\"> 							";
														echo "		<input type=\"submit\" name=\"delete_spec\" value=\"Delete\"> 							";
														echo "	</form>																						";
														echo "	</p>  																						";
														echo " </li>																						";
														$counter++;
													
													}
													if($counter == 0)echo "<p> None </p>";
												?>
												
												
											</ul>
										</li>
									</ul>
                                  
                                </div>
                            </div> 
                          </div>
                      </div>
                  </div>
              </div>
              <div class="patient col-md-12">
                  <h4>Doctors</h4>
                  <table id="docs" style="width:700px">
				  
					<?php
						$result = mysqli_query($con, "select * from doctor where hospital_id = $id");
						$counter=0;
						while($row = mysqli_fetch_assoc($result)){
						
								$id = $row['id'];
								$name = $row['name'];
								$image = $row['image'];
								$action = $_SERVER['PHP_SELF'] . "#docs";
				  
								echo "<tr>																		";
								echo "<td class=\"t-image\">													";
								echo "	  <img src=\"$image\" class=\"img-responsive\">							";
								echo "  </td>																	";
								echo "  <td>																	";
								echo "	  <form action=\"DoctorView.php\" method=\"post\">						";
								echo "		  <input type=\"hidden\" name=\"doctor_id\" value=\"$id\">			";
								echo "		  <input style=\"width:133px\" type=\"submit\" value=\"$name\" class=\"name\">			";
								echo "	  </form>																";
								echo "  </td>																	";
								echo "  <td>																	";
								echo "	  <form action=\"$action\" method=\"post\">								";
								echo "		  <input type=\"hidden\" name=\"doctor_id\" value=\"$id\">			";
								echo "		  <input name=\"delete_doc\" type=\"submit\" value=\"delete\">		";
								echo "	  </form>																";
								echo "  </td>																	";
								echo " </tr>																	";
							  
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
