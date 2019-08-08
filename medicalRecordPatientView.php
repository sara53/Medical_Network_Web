<?php

	session_start();
	
	// prevent resubmission
	header("Cache-Control: no cache");
	session_cache_limiter("private_no_expire");
	
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
		
		// patient data
		$result = mysqli_query($con, "select * from patient where id = $id");
		if($row = mysqli_fetch_assoc($result)){
			$name = $row['name'];
			$imageName = $row['image'];
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
         <title>Medical Record</title>
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
    <div class="patient-profile">
         <div class="row">
          <div class="col-md-12">
             <div class="profile">
                   <a class="log-out log2" href="index.php">Back To Profile</a>
                   <div class="image">
                    <img src="<?php echo $imageName ?>" width="120" height="120" class="img-circle">
                   <h3><?php echo $name ?></h3>
                 </div>
             </div>
             </div>
        </div>
    </div>
    <div class="posts">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    
						<?php
							
							
							$result = mysqli_query($con, "select * from medical_records where patient_id=$id order by id DESC");
							while($row = mysqli_fetch_assoc($result)){
							
								$date_and_time = $row['date_and_time'];
								$overall = $row['overall_condition'];
								$details = $row['details'];
								$doc_id = $row['doctor_id'];
								$result2 = mysqli_query($con, "select name from doctor where id = $doc_id");
								if($row2 = mysqli_fetch_assoc($result2)){
									$doc_name = $row2['name'];
								}
								
								echo "<div class=\"firstPost\">																								";
								echo "<h4>$date_and_time</h4>																								";
								echo "<label>Overall Condition</label>																						";
								echo "<p>$overall</p>																										";
								echo "<label>details :</label>																								";
								echo "<p>$details</p>																										";
								echo "<p><strong>Submitted By </strong></p>																					";
								echo "<form action=\"DoctorView.php\" method =\"post\">																		";
								echo "	<input type=\"hidden\" name=\"doctor_id\" value=\"$doc_id\">														";
								echo "	<input type=\"submit\" value=\"$doc_name\">																			";
								echo "</form>																												";
								echo "</div>																												";
							}
						
						?>
                    
                </div>
            </div>
        </div>
    </div>
  </body>
</html>
