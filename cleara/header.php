<html>
<head>
	<meta charset="UTF 8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
	
	<!--title-->
	<title> PCOS companion</title>

	<!--style sheets-->
	<link rel="shortcut icon" href="images/logo.png" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" href="http://cdn.jsdelivr.net/cal-heatmap/3.3.10/cal-heatmap.css" />    
	<link rel="stylesheet" href="custom/custom.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
	<!--java script-->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script> 
	<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
    <script type="text/javascript" src="http://d3js.org/d3.v3.min.js"></script>    
    <script type="text/javascript" src="http://cdn.jsdelivr.net/cal-heatmap/3.3.10/cal-heatmap.min.js"></script>  
</head>

<?php
session_start();
include("templates/conn.php");

?>

<header>
	<!--header image-->
	<div id="header-img" style="background-image: url(images/headerlg.png);"></div>
	
	<!--nav bar-->
	<nav class="navbar navbar-expand-lg navbar-light bg-white">
		<div class="container d-flex justify-content-center">

			<a href="daily.php" class="navbar-brand d-flex align-items-center">
				<img src="images/list.png" width="40" height="40" class="mr-3" alt="list icon">
				<span class="navbar-text d-lg-block" style="color: #005453;"> Daily Log </span>
			</a>
			<a href="food.php" class="navbar-brand d-flex align-items-center ml-5">
				<img src="images/food.png" width="40" height="40" class="mr-3" alt="property icon">
				<span class="navbar-text d-lg-block" style="color: #005453;"> Food</span>
			</a>
			<a href="progress.php" class="navbar-brand d-flex align-items-center ml-5">
				<img src="images/progress.png" width="40" height="40" class="mr-3" alt="medicine icon">
				<span class="navbar-text d-lg-block" style="color: #005453;"> Progress </span>
			</a>
			<a href="calendar.php" class="navbar-brand d-flex align-items-center ml-5">
				<img src="images/calendar.png" width="40" height="40" class="mr-3" alt="calendar icon">
				<span class="navbar-text d-lg-block" style="color: #005453;"> Calender </span>
			</a>
	
		</div>
	</nav>
</header>