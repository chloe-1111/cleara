<html>
<head>
	<meta charset="UTF 8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
	
	<!--title-->
	<title> Cleara </title>

	<!--style sheets-->
	<link rel="shortcut icon" href="images/logo.png" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" href="http://cdn.jsdelivr.net/cal-heatmap/3.3.10/cal-heatmap.css" />    
	<link rel="stylesheet" href="custom/custom.css" />

	<!--java script-->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script> 
	<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
    <script type="text/javascript" src="http://d3js.org/d3.v3.min.js"></script>    
    <script type="text/javascript" src="http://cdn.jsdelivr.net/cal-heatmap/3.3.10/cal-heatmap.min.js"></script>  
</head>


<header>
	<!--header image-->
	<div id="header-img" style="background-image: url(images/header.jpeg);"></div>
	
	<!--nav bar-->
	<nav class="navbar navbar-expand-lg navbar-light bg-white d-none d-md-block">
		<div class="container d-flex justify-content-center">

			<a href="#food-container" class="navbar-brand d-flex align-items-center">
				<img src="images/food.png" width="40" height="40" class="mr-3 d-none d-md-block" alt="list icon">
				<span class="navbar-text d-none d-lg-block"> Recommendations </span>
			</a>

			</a>
			<a href="#calendar-container" class="navbar-brand d-flex align-items-center ml-5">
				<img src="images/calendar.png" width="40" height="40" class="mr-3 d-none d-md-block" alt="medicine icon">
				<span class="navbar-text d-none d-lg-block"> Calendar </span>
			</a>
			<a href="#progress-container" class="navbar-brand d-flex align-items-center ml-5">
				<img src="images/progress.png" width="40" height="40" class="mr-3 d-none d-md-block" alt="calendar icon">
				<span class="navbar-text d-none d-lg-block"> Tracking </span>
			</a>
			<a href="#profile-container" class="navbar-brand d-flex align-items-center ml-5">
				<img src="images/profile.png" width="40" height="40" class="mr-3 d-none d-md-block" alt="calendar icon">
				<span class="navbar-text d-none d-lg-block"> Profile </span>
			</a>
			
		</div>
	</nav>
</header>
<br>

<!--Inlcude database, patientid and json-->
<?php
	ob_start();
	include ("data.php")
?>
 
<body>

	<!--container 4: medicine-->
	<?php
		include( "tracking.php" );
	?>
<?php include 'tips.php'; ?>

	<!--container 4: medicine-->
	<?php
		include( "profile.php" );
	?>


	<!-- footer-->
	<?php
		include( "footer.php" );
	?>


