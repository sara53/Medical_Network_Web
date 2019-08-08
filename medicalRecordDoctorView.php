<?php

	session_start();
	
	// prevent resubmission
	header("Cache-Control: no cache");
	session_cache_limiter("private_no_expire");
	
	if(isset($_POST['selected_patient_id'])){
		$_SESSION['last_patient_id'] = $_POST['selected_patient_id'];
	} else {
		if(!isset($_SESSION['last_patient_id'])){
			header("Location: index.php");
			exit();
		}
	}

	// database connection
	include("database_con.php");

	// doctor id
	$id =  $_SESSION['id'];
	// patient id
	$pid = $_SESSION['last_patient_id'];
	
	//####################### Adding new status ######################################
	if(isset($_POST['overall_condition'])){
		
	
		$overall_condition = $_POST['overall_condition'];
		$details = $_POST['details'];
		$date_and_time = $_POST['date_and_time'];
		
		mysqli_query($con, "insert into medical_records(patient_id, date_and_time, overall_condition, details, doctor_id) values($pid, '$date_and_time', '$overall_condition', '$details', $id);") or die("Errur");
	
	} 
	
	
	//####################### getting patient data #####################################
	$result = mysqli_query($con, "select * from patient where id = $pid");
	if($row = mysqli_fetch_assoc($result)){
		// patient data
		$name = $row['name'];
		$address = $row['address'];
		$phone = $row['phone'];
		$email = $row['email'];
		$imageName = $row['image'];
		
	}


	
	
?>



<!DOCTYPE html>
<html>
    <head>
         <title>Medical Record</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/doctor.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="shortcut icon" href="images/logo.png">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<script src="js/addStatus.js"></script>
        <!--[if lt IE 9]>
          <script src="js/html5shiv.min.js"></script>
          <script src="js/respond.min.js"></script>
        <![endif]-->
  </head>
  <body>
  
<!--start Header-->
    <div class="patient-profile">
         <div class="row">
          <div class="col-md-12">
             <div class="profile">
                  <a class="log-out log2" href="index.php">Back To Profile</a>
                   <div class="image">
                    <img src="<?php echo $imageName ?>" width="120" height="120" class="img-circle">
                   <h3><?php echo $name ?></h3>
				   <p style="color:white;">Id: <?php echo $pid ?></p>
				   <p style="color:white;">Phone: <?php echo $phone ?></p>
				   <p style="color:white;">Address: <?php echo $address ?></p>
				   <p style="color:white;">Email: <?php echo $email ?></p>
                 </div>
             </div>
             </div>
        </div>
    </div>
    <div class="status">
        <div class="container">
            <div class="row">
               <div class="col-md-12 text-center">
                 <div class="new-status first">
                     <h4>New Status</h4>
						<form id="form_add" action = "<?php echo $_SERVER['PHP_SELF'] ."#posts" ?>" method="post">
							<label>Overall Condition: </label>
							<input id="overall" name="overall_condition" type="text" size="30"><br>
							<label class="del"> Details: </label>
							<textarea id="details" name="details" ></textarea><br>
                    </form>
                        <p style="color:red;" id="error_txt"></p>
                        <button onclick="addStatus()"> Add </button>
                 </div>
                </div>
            </div>
        </div>
    </div>
    <div class="posts" id="posts">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
						<?php
						
							$result = mysqli_query($con, "select * from medical_records where patient_id = $pid order by id DESC");
							while($row = mysqli_fetch_assoc($result)){
							
								$date_and_time = $row['date_and_time'];
								$overall_condition = $row['overall_condition'];
								$details = $row['details'];
								$doctor_id = $row['doctor_id'];
								
								$result2 = mysqli_query($con, "select name from doctor where id = $doctor_id");
								if($row2 =  mysqli_fetch_assoc($result2)){
									$doctor_name = "Dr/" . $row2['name'];
								}
								if($doctor_id == $id){
									$doctor_name = "You";
									$action = "doctorProfile.php";
								} else {
									$action = "DoctorView.php";
								}	
								
								echo " <div class=\"firstPost\">																								";
									echo " <h4> $date_and_time </h4>																							";
									echo " <label>Overall Condition</label>																						";
									echo " <p>$overall_condition</p>																							";
									echo " <label>details :</label>																								";
									echo " <p>$details</p>																										";
									echo " <p><strong>Submitted By </strong></p>																				";
									echo " <form action=\"$action\" method =\"post\">																			";
									echo "	<input type=\"hidden\" name=\"doctor_id\" value=\"$doctor_id\">														";
									echo " 	<input type=\"submit\" value=\"$doctor_name\">																		";
									echo " </form>																												";
								echo " </div>																													";
							}
						?>
                </div>
            </div>
        </div>
    </div>
  </body>
</html>

