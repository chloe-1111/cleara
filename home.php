<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />

	<title>Cleara</title>

	<!-- Stylesheets -->
	<link rel="shortcut icon" href="images/logo.png" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="custom/custom.css" />

	<!-- JavaScript -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>
	<script src="http://d3js.org/d3.v3.min.js"></script>

<!--Inlcude data file-->
<?php
	ob_start();
	include ("data.php")
?>

<!-- Navbar -->
<header class="navbar-custom sticky-top">
	<nav class="navbar navbar-expand-lg navbar-dark">
		<div class="container-fluid">
			<a class="navbar-brand" href="#">
				<img src="images/header.jpeg" alt="Cleara Logo" style="height: 40px;">
			</a>

			<!-- Custom Toggler Button -->
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<!-- Collapsible Links -->
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<a class="nav-link" href="#progress-container">Symptom Tracking</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#tips-container">Tips</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#profile-container">My Profile</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</header>


<br>

<!-- include content -->
<body>
	<!--container: tracking-->
	<?php
		include( "tracking.php" );
	?>

	<!--container: recommendations-->
	<?php 
		include( 'tips.php' ); 
	?>

	<!--container: profile-->
	<?php
		include( "profile.php" );
	?>

</body>
<script>
document.querySelectorAll('a.nav-link').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').replace('#', '');
        const targetElement = document.getElementById(targetId);

        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - document.querySelector('.navbar-custom').offsetHeight,
                behavior: 'smooth'
            });
        }
    });
});
</script>
