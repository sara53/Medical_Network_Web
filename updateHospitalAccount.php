<?php

	session_start();
	
	// prevent resubmission
	header("Cache-Control: no cache");
	session_cache_limiter("private_no_expire");
	
	if( isset( $_SESSION['user_type'] )){
		if($_SESSION['user_type'] == 'hospital'){
			include("database_con.php");
			$id = $_SESSION['id'];
			
			#################### hopital Data ##########################
			// default value for error messages
			$data_error = "";
			
			// in general use the data from the database 
			$result = mysqli_query($con, "select * from hospital where id = $id;");
			if($row = mysqli_fetch_assoc($result)){
				$name			= 		$row['name'];
				$city			= 		$row['city'];
				$detailed_address	= 		$row['detailed_address'];
				$email			= 		$row['email'];
				$password		=		$row['password'];
				$about 			= 		$row['about'];
			}
			
			
			###################### On clicking Update #############################
			// if the user clicked update, use the data from $_POST
			if(isset($_POST['update'])){
				$name = $_POST['name'];
				$city = $_POST['city'];
				$detailed_address = $_POST['detailed_address'];
				$email = $_POST['email'];
				$about = $_POST['about'];
				$existingEmailResult = mysqli_query($con, "select * from hospital where email='$email' and id <> $id;");
					
				// update data
				if($row = mysqli_fetch_assoc($existingEmailResult)){
					$data_error = "New email already existing";
				} elseif($data_error==""){
					mysqli_query($con, "update hospital set name = '$name', city = '$city', detailed_address='$detailed_address', 
						email='$email',  about = '$about' where id = $id");
				}
				
				// if the user wants to change password
				$old_pass = $_POST['old_pass'];
				$new_pass = $_POST['new_pass'];
				$confirm_pass = $_POST['confirm_pass'];
				if($old_pass != "" && $data_error == ""){
					// update password
					if($new_pass == '' || $confirm_pass == ''){
						$data_error = "Please fill all password fields!";
					} elseif($old_pass != $password){
						$data_error = "Wrong old password!";
					} elseif($new_pass != $confirm_pass){
						$data_error = "New pass and confirmation don't match!";
					} elseif($data_error=="") {
						mysqli_query($con, "update hospital set password = '$new_pass' where id = $id");
					}
				}
				
				// get back to profile
				if($data_error == ""){
					header("Location: hospitalProfile.php");
					exit();
				}
			}
		}
		// not hospital
		else {
			header("Location: index.php");
			exit();
		}
	}
	// not logged in
	else {
		header("Location: index.php");
		exit();
	}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update</title>
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
            
              <li><a href="index.php">Back To Profile</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
<!--End Navbar -->
<!--Start Hospital Update Form-->
      <div class="update-form height">
             <div class="form">
                 <div class="form-header">
                     <h3>Update Account</h3>
                 </div>
                 <div class="logo">
                     <img src="images/logo.png">
                 </div>
                 <div class="clearfix"></div>
                 <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
					<span style="color:red" ><?php echo $data_error ?></span>
					<p></p>
                     <input required type="text" name="name" placeholder="Hospital Name" value="<?php echo $name ?>">
                     <input required type="text" name="city" placeholder="City" value="<?php echo $city ?>">
                     <input required type="text" name="detailed_address" placeholder="Detailed Address" value="<?php echo $detailed_address ?>">
                     <input required type="email" name="email" placeholder="Enter Your Email" value="<?php echo $email ?>">
                      <textarea required id="about" name = "about" placeholder="About Hospital"><?php echo $about ?></textarea>
                    
                     <span style="color:#0BA1B6;">Change password? (If not, leave these empty)</span>
					 <p></p>
					 <input type="Password" name="old_pass" placeholder="Enter Old Password">
					 <input  type="Password" name="new_pass" placeholder="Enter New Password">
                     <input  type="Password" name="confirm_pass" placeholder="Confirm Password">
                     <input type="submit" name="update" value="Update Account">
                 </form>
             </div>
      </div>
<!--Start Hospital Update  Form-->
   
    <script src="js/jquery-1.12.4.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/jquery.countTo.js"></script>
  </body>
</html>
