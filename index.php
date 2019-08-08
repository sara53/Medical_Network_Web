<?php

	session_start();

    include("database_con.php");
	
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
            <li class="active"><a href="index.php">Home <span class="sr-only">(current)</span></a></li>
            <li><a href="doctorForm.php">Login As A doctor</a></li>
            <li><a href="patientForm.php">Login As A Patient</a></li>
            <li><a href="hospitalForm.php">Login To Hospital</a></li>
             
          </ul>

        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
<!--End Navbar -->
<!--Start Slider-->
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
          <!-- Indicators -->
          <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
          </ol>

          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox">
            <div class="item active">
              <img src="images/slide1.jpg" alt="Slide1">
              <div class="carousel-caption">
               <h1>We Are Experienced Doctors</h1>
              <p class="lead">You will get well experienced quality doctors here</p>
               <button class="hvr-rectangle-out">See Details</button>
              </div>
            </div>
            <div class="item">
              <img src="images/slide2.jpg" alt="Slide1">
              <div class="carousel-caption">
                  <h2>We Are 24 Hourse Available</h2>
                  <p class="lead">When you will get into a problem, we are always here</p>
                <button class="hvr-rectangle-out">See Details</button>
              </div>
            </div>
            <div class="item">
              <img src="images/slide3.jpg" alt="Slide1">
              <div class="carousel-caption">
                <h3>We Provide Best &amp; Quality Servies</h3>
                <p class="lead">We do not compromise anything to keep quality service</p>
                <button class="hvr-rectangle-out">See Details</button>
              </div>
            </div>
          </div>
    </div>
<!--End Slider-->
<!--Start Choose-->
      <div class="choose-us text-center">
          <div class="container">
              <h2 class="h1">Why Choose Us</h2>
              <img src="images/title-bg.png">
              <p>You Can Choose Our High Quality Service without Hesitation</p>
              <div class="row">
                  <div class="col-md-3 col-sm-6 col-xs-12">
                     <div class="icone">
                      <p><i class="fa fa-bolt" aria-hidden="true"></i></p>
                      <p>Advanced Technology</p>
                     </div>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                      <div class="icone">
                          <p><i class="fa fa-clock-o" aria-hidden="true"></i></p>
                          <p>24 Hourse Service</p>
                      </div>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                      <div class="icone">
                          <p><i class="fa fa-users" aria-hidden="true"></i></p>
                          <p>Expert Staff</p>
                      </div>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                      <div class="icone">
                          <p><i class="fa fa-bookmark" aria-hidden="true"></i></p>
                          <p>Extensive History</p>
                      </div>
                  </div>

              </div>
          </div>
      </div>
<!--End choose-->
<!--Start our servives-->
<div class="our-sevices text-center">
    <div class="container">
        <h2 class="h1"> Our Services</h2>
        <p>Our expert staffs always provide premium quality service</p>
        <img src="images/title-bg.png">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="services">
                    <div class="icon">
                        <i class="fa fa-heart" aria-hidden="true"></i>
                    </div>
                    <div class="info">
                        <h4>Mother Care</h4>
                        <p>Quis non odit sordidos, vanos, leves, futtiles? Primum in nostrane potestate est, quid meminerimus.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="services">
                    <div class="icon">
                        <i class="fa fa-user-md"></i>
                    </div>
                    <div class="info">
                        <h4>Mother Care</h4>
                        <p>Quis non odit sordidos, vanos, leves, futtiles? Primum in nostrane potestate est, quid meminerimus.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="services">
                    <div class="icon">
                       <i class="fa fa-ambulance"></i>
                    </div>
                    <div class="info">
                        <h4>Mother Care</h4>
                        <p>Quis non odit sordidos, vanos, leves, futtiles? Primum in nostrane potestate est, quid meminerimus.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="services">
                    <div class="icon">
                        <i class="fa fa-stethoscope"></i>
                    </div>
                    <div class="info">
                        <h4>Mother Care</h4>
                        <p>Quis non odit sordidos, vanos, leves, futtiles? Primum in nostrane potestate est, quid meminerimus.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="services">
                    <div class="icon">
                        <i class="fa fa-medkit"></i>
                    </div>
                    <div class="info">
                        <h4>Mother Care</h4>
                        <p>Quis non odit sordidos, vanos, leves, futtiles? Primum in nostrane potestate est, quid meminerimus.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="services">
                    <div class="icon">
                        <i class="fa fa-flask"></i>
                    </div>
                    <div class="info">
                        <h4>Mother Care</h4>
                        <p>Quis non odit sordidos, vanos, leves, futtiles? Primum in nostrane potestate est, quid meminerimus.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<!--End our servives-->
<!--Start Account-->
<div class="account">
   <div class="account-overlay">
    <div class="container">
        <div class="row">
           <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="count-down">
                    <i class="fa fa-ambulance"></i>
                    <p><span class="timer" data-from="0" data-to="<?php $counter=0; $result = mysqli_query($con, "select * from hospital"); while($row = mysqli_fetch_assoc($result)){$counter++;} echo $counter; ?>"  data-speed="5000">44</span></p>
                    <p>Total Hospitals</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="count-down">
                    <i class="fa fa-heart"></i>
                    <p><span class="timer" data-from="0" data-to="<?php $counter=0; $result = mysqli_query($con, "select * from doctor"); while($row = mysqli_fetch_assoc($result)){$counter++;} echo $counter; ?>"  data-speed="5000">130</span></p>
                    <p>Expert Doctors</p>
                </div>
            </div>
              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="count-down">
                    <i class="fa fa-stethoscope"></i>
                    <p><span class="timer" data-from="0" data-to="<?php $counter=0; $result = mysqli_query($con, "select * from patient"); while($row = mysqli_fetch_assoc($result)){$counter++;} echo $counter; ?>"  data-speed="5000">1200</span></p>
                    <p>Happy Patients</p>
                </div>
            </div>
           
        </div>
    </div>
</div>
</div>
<!--End Account-->
         <div class="copy text-center">
             All Right Reserved To &copy; Our Team
        </div>
    </div>
<!--End Footer-->
    <script src="js/jquery-1.12.4.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/jquery.countTo.js"></script>
  </body>
</html>
