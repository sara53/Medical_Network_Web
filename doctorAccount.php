<?php
	
	session_start();
	
	// prevent resubmission
	header("Cache-Control: no cache");
	session_cache_limiter("private_no_expire");

	########### If logged in, go to profile ################
	if(isset($_SESSION['user_type'])){
	
		if($_SESSION['user_type'] == 'doctor'){
			header("location: doctorProfile.php");
			exit();
		}
		
		if($_SESSION['user_type'] == 'patient'){
			header("location: patientProfile.php");
			exit();
		}
		
		if($_SESSION['user_type'] == 'hospital'){
			header("location: hospitalProfile.php");
			exit();
		}
		
	}
	
	################## If creating account ####################
	// database connection
	include("database_con.php");
	
	// default value for error messages
	$data_error = "";
	
	// default values for data
	$name			= 		'';
	$address		= 		'';
	$phone			= 		'';
	$email			= 		'';
	$new_pass		=		'';
	$confirm_pass	=		'';
	$hospital_id	= 		-1;
	$specialty_id	= 		-1;
	$about 			= 		'';
	
	###################### On clicking Create #############################
	// if the user clicked create, use the data from $_POST
	if(isset($_POST['create'])){
		$name = $_POST['name'];
		$address = $_POST['address'];
		$phone = $_POST['phone'];
		$hospital_id = $_POST['hospital'];
		$specialty_id = $_POST['specialty'];
		$email = $_POST['email'];
		$about = $_POST['about'];
		$new_pass = $_POST['new_pass'];
		$confirm_pass = $_POST['confirm_pass'];
		$existingEmailResult = mysqli_query($con, "select * from doctor where email='$email';");
			
		// create account
		if($hospital_id == -1 || $specialty_id == -1){
			$data_error = "Please select hospital and specialty";
		} elseif($row = mysqli_fetch_assoc($existingEmailResult)){
			$data_error = "Email already existing";
		} elseif($new_pass != $confirm_pass){
			$data_error = "Password and confirmation don't match!";
		} elseif($data_error==""){
			mysqli_query($con, "insert into doctor(name, phone, address, hospital_id, specialty_id, email, password, about) 
				values('$name', '$phone', '$address', $hospital_id, $specialty_id, '$email', '$new_pass', '$about');");
		}
		
		// go to profile
		if($data_error == ""){
			$result = mysqli_query($con, "select id from doctor where email = '$email' and password='$new_pass'");
			if($row = mysqli_fetch_assoc($result)){
				$_SESSION['user_type'] = 'doctor';
				$_SESSION['id'] = $row['id'];
				header("Location: doctorProfile.php");
				exit();
			}
		}
	}
	
	###################### Populating drop down lists ############################
	// hospital arrays of id, name (to fill the drop down list)
	$result = mysqli_query($con, "select * from hospital");
	$hospital_id_arr = array(-1);
	$hospital_name_arr = array(htmlspecialchars("<Select hospital>"));
	while($row = mysqli_fetch_assoc($result)){
		$hospital_id_arr[ ] = $row['id'];
		$hospital_name_arr[ ] = $row['name'];
	}
	
	// if the user has just selected an item from the hospital list
	if(isset($_POST['hospital_select'])){
		$hospital_id = $_POST['hospital_select'];
		$specialty_id = -1;
		$name = $_POST['name'];
		$address = $_POST['address'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$about = $_POST['about'];
	}
	
	// specialty arrays of id, name (to fill the drop down list)
	$result = mysqli_query($con, "select * from specialties where hospital_id = $hospital_id;");
	$specialty_id_arr = array(-1);
	$specialty_name_arr = array(htmlspecialchars("<Select specialty>"));
	if($hospital_id == -1){
		$specialty_name_arr = array(htmlspecialchars("<Select hospital first>"));
	}
	while($row = mysqli_fetch_assoc($result)){
		$specialty_id_arr[ ] = $row['id'];
		$specialty_name_arr[ ] = $row['name'];
	}
	
	
	
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/hover-min.css">
    <link rel="shortcut icon" href="images/logo.png">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	<script src="js/selectHospital.js"></script>
    <!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
<!--Start Social Links-->
      <div class="social-links">
          <div class="container">
              <div class="row">
                  <div class="col-sm-6 col-xs-12">
                      <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                      <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                      <a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                      <a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
                      <a href="#"><i class="fa fa-youtube" aria-hidden="true"></i></a>
                  </div>
                  <div class="col-sm-6 col-xs-12">
                     <p>
                        <i class="fa fa-phone-square" aria-hidden="true"></i>
                        <span>Call Us :111 222 3333</span>
                      </p>
                  </div>
              </div>
          </div>
      </div>
<!--End Social Links -->
<!--Start Navbar -->
    <nav class="navbar navbar-default">
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">
             <span>Hospital</span> 
            <img class="img-responsive" src="images/logo.png">
          </a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Home <span class="sr-only">(current)</span></a></li>
            <li><a href="doctorForm.php">Login As A doctor</a></li>
            <li><a href="patientForm.php">Login As A Patient</a></li>
             <li><a href="hospitalForm.php">Login To Hospital</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
<!--End Navbar -->
<!--Start Patient Form-->
      <div class="Doctor-form">
             <div class="form">
                 <div class="form-header">
                     <h3>Sign Up</h3>
                 </div>
                 <div class="logo">
                     <img src="images/logo.png">
                 </div>
                 <div class="clearfix"></div>
                 <form id="form_data" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
				 
					<span style="color:red" ><?php echo $data_error ?></span>
					<p></p>
				 
                     <input id="name" type="text" name="name" required value="<?php echo $name ?>" placeholder="Enter Your Name">
                     <input id="phone" type="tel" name="phone" required value="<?php echo $phone ?>" placeholder="Enter Your Phone">
                     <input id="address" id = "address" type="text" name="address" required value="<?php echo $address ?>" placeholder="Enter Your Address">
                     <input id="email" type="email" name="email" required value="<?php echo $email ?>" placeholder="Enter Your Email">
					 
					 <select name="hospital" id="hospital" onchange="onSelectHospital()">
						 <?php
							for($i = 0; $i < count($hospital_id_arr); $i++){
								$hos_id = $hospital_id_arr[$i];
								$hos_name = $hospital_name_arr[$i];
								echo "	<option value=\"$hos_id\" ";
								if($hos_id == $hospital_id) echo " selected ";
								echo " >$hos_name</option>		";
							}
						 ?>
                     </select>
					 
					 <select name="specialty" id="special">
						 <?php
							for($i = 0; $i < count($specialty_id_arr); $i++){
								$spec_id = $specialty_id_arr[$i];
								$spec_name = $specialty_name_arr[$i];
								echo "	<option value=\"$spec_id\" ";
								if($spec_id == $specialty_id) echo " selected ";
								echo " >$spec_name</option>		";
							}
						 ?>
                     </select>
                      
                     
                     
                     <input type="Password" name="new_pass" required placeholder="Enter Password">
                     
                     <input type="Password" name="confirm_pass" required placeholder="Confirm Password">
                     <textarea required id="about" name = "about" placeholder="About yourself"><?php echo $about; ?></textarea>
                     <input id="btn" name = "create" type="submit" value="Create Account">
					 
                 </form>
             </div>
      </div>
<!--Start Patient Form-->
   
    <script src="js/jquery-1.12.4.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/jquery.countTo.js"></script>
  </body>
</html>
