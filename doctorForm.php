<?php
	
	session_start();
	
	// prevent resubmission
	header("Cache-Control: no cache");
	session_cache_limiter("private_no_expire");

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

	$wrong_email_or_pass = FALSE;

	if(isset($_POST['email']) && isset($_POST['password'])){

		$email = $_POST['email'];
		$pass = $_POST['password'];

		include("database_con.php");

		$result = mysqli_query($con, "select * from doctor where email = '$email' and password = '$pass'");
		if($row = mysqli_fetch_assoc($result)){
			$_SESSION['user_type'] = 'doctor';
			$_SESSION['id'] = $row['id'];
			header("Location: doctorProfile.php");
			exit();
		} else {
			$wrong_email_or_pass = TRUE;
		}

	}


?>




<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Medical Network</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/hover-min.css">
    <link rel="shortcut icon" href="images/logo.png">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
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
            <li class="active"><a href="doctorForm.php">Login As A doctor</a></li>
            <li><a href="patientForm.php">Login As A Patient</a></li>
             <li><a href="hospitalForm.php">Login To Hospital</a></li>
          </ul>

        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
<!--End Navbar -->
<!--Start Patient Form-->
      <div class="patient-form">
             <div class="form">
                 <div class="form-header">
                     <h3>Log In</h3>
                 </div>
                 <div class="logo">
                     <img src="images/logo.png">
                 </div>
                 <div class="clearfix"></div>
                 <form action="<?php $_PHP_SELF?>" method="post">
                     <input required type="email" name="email" placeholder="Enter Your Email">
                     <input required type="Password" name="password" placeholder="Enter Your Password">
					 <span style="color:red;" align="center" > <?php if($wrong_email_or_pass) echo "Email and password don't match!" ?> </span>
                     <input type="submit" value="Log In">
                     <span>Still New ?</span>
                     <a href="doctorAccount.php">Create An Account</a>
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
