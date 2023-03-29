<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Your Mission Crew | The Starsprinter Scavengers</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Site icon -->
        <link rel="icon" type="image/x-icon" href="pictures/favicon.ico">

		<!-- Bootstrap CSS CDN -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

		<!-- For carousel -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

		<!-- For icons -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

		<!-- Main CSS -->
		<link rel="stylesheet" type="text/css" href="css/main.css">
	</head>

	<body>
	    <!-- Header -->
		<header>
			<h1 class="title">THE STARSPRINTER SCAVENGERS</h1>
		</header>

		<!-- Navigation Bar -->
		<nav class="navbar navbar-expand-lg navbar-dark">
		  <a class="navbar-brand" href="index.php"><img class="navbarlogo" src="pictures/sslogo.jpg" /></a>
		  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		  </button>

		  <div class="collapse navbar-collapse" id="navbarContent">
			<ul class="navbar-nav mr-auto">
			  <li class="nav-item">
				<a class="nav-link" href="index.php">Home</a>
			  </li>
			  <li class="nav-item active">
				<a class="nav-link nav-active" href="crew.php">The Crew<span class="sr-only">(current)</span></a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" href="catalogue.php">Search Catalogue</a>
			  </li>
              <?php
                if($_SESSION["loginsuccess"])
                {
                    if($_SESSION['usercode'] === "0")
                    {
                        echo '<li class="nav-item"><a class="nav-link" href="additem.php">Add Item</a></li>';
                    }
                    echo '<li class="nav-item"><a class="nav-link" href="logout.php">Log Out</a></li>';
                }
            ?>
            </ul>

            <?php
                if($_SESSION["loginsuccess"])
                {
                    echo '<ul class="navbar-nav d-none d-lg-block">
                        <li class="nav-item nav-user rightalign"><a class="nav-link" href="profile.php?id='.$_SESSION["userid"].'">Hi, '.$_SESSION["name"].'!</a></li>
                    </ul>';
                    echo '<ul class="navbar-nav d-lg-none">
                        <li class="nav-item"><a class="nav-link" href="profile.php?id='.$_SESSION["userid"].'">Profile/Cart</a></li>
                    </ul>';
                }
                else
                {
                    echo '<ul class="navbar-nav d-none d-lg-block">
                        <li class="nav-item rightalign"><a class="nav-link" href="login.php">Log In</a></li>
                    </ul>';
                    echo '<ul class="navbar-nav d-lg-none">
                        <li class="nav-item"><a class="nav-link" href="login.php">Log In</a></li>
                    </ul>';
                }
            ?>
		  </div>
		</nav>
		
		<!-- Body Text -->
        <h2 class="crew-heading mt-3">Your Mission Crew</h2>
        <!-- Images and descriptions of crew members -->
        <div class="container-fluid row mb-3">
            <div class="col-lg-2 col-md-2 col-sm-4">
                <img class="crew-pic" src="pictures/seraz.jpg">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-8">
                <h3 class="crew-name">Seraz Battoriyo</h3>
                <h5 class="crew-text">Quiet but calculating, you won't see him coming until it's too late. Not in the knife-in-your-back way, but more like the hack-into-your-bank-account way.</h5>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-4">
                <img class="crew-pic" src="pictures/xalerie.jpg">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-8">
                <h3 class="crew-name">Xalerie Hkaliger</h3>
                <h5 class="crew-text">Her idea of a perfect vacation involves camping in the wilderness, exploring some caves, and slaying some zombies. You know, the usual.</h5>
            </div>
        </div>
        <div class="container-fluid row mb-3">
            <div class="col-lg-2 col-md-2 col-sm-4">
                <img class="crew-pic" src="pictures/gavaan.jpg">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-8">
                <h3 class="crew-name">Gavaan Ursuzar</h3>
                <h5 class="crew-text">Yeah, the blue hair makes it difficult for him to be stealthy, but he insists that if he's going to die, he at least wants to go down looking cool.</h5>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-4">
                <img class="crew-pic" src="pictures/aesta.jpg">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-8">
                <h3 class="crew-name">Aësta Izary</h3>
                <h5 class="crew-text">She can always stay cool, calm, and collected in a crisis. In fact, she works so well under pressure that she doesn't start projects until the night before they're due.</h5>
            </div>
        </div>
		
		<!-- Footer -->
		<footer>
			<h5 class="mr-3 mt-3">Made by the crew of the <i>Starsprinter</i>, 3023</h5>
		</footer>

        <!-- Bootstrap JavaScript CDN -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>