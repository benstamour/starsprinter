<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Log In | The Starsprinter Scavengers</title>
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

        <!-- Validation Function -->
        <script>
            function check()
            {
                var email = document.getElementById("email").value;
                var pwd = document.getElementById("pwd").value;
                if(email != "" && pwd != "")
                {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function()
                    {
                        if(this.readyState == 4 && this.status == 200)
                        {
                            var msg = this.responseText;
                            console.log(msg);
                        }
                        if(msg)
                        {
                            document.getElementById("login-hint").innerHTML = msg;
                            document.getElementById("signal").value = "red";
                        }
                        if(msg == "Email and password match!")
                        {
                            document.getElementById("signal").value = "green";
                        }
                    };
                    xhr.open("GET", "validation.php?email=" + email + "&pwd=" + pwd);
                    xhr.send();
                }
            }
        </script>
        <script>
            function validate()
            {
                if (document.getElementById("signal").value == "green")
                {
                    window.location.href = "index.php";
                    return true;
                }
                else
                {
                    event.preventDefault();
                    return false;
                }
            }
        </script>
	</head>

	<body>
	    <!-- Header -->
		<header>
			<h1 class="title">THE STARSPRINTER SCAVENGERS</h1>
		</header>

		<!-- Navigation Bar -->
		<nav class="navbar navbar-expand-lg navbar-dark">
		  <a class="navbar-brand" href="index.php"><img class="navbarlogo" src="pictures/sslogo.jpg" /></a>
		  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		  </button>

		  <div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
			  <li class="nav-item">
				<a class="nav-link" href="index.php">Home</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" href="crew.php">The Crew</a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" href="catalogue.php">Search Catalogue</a>
			  </li>
              <?php
                if($_SESSION['loginsuccess'])
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
                if($_SESSION['loginsuccess'])
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
                        <li class="nav-item active rightalign"><a class="nav-link nav-active" href="login.php">Log In<span class="sr-only">(current)</span></a></li>
                    </ul>';
                    echo '<ul class="navbar-nav d-lg-none">
                        <li class="nav-item active"><a class="nav-link nav-active" href="login.php">Log In<span class="sr-only">(current)</span></a></li>
                    </ul>';
                }
            ?>
		  </div>
		</nav>
		
		<!-- Body Text -->
        <h2 class="home-heading mt-3">Log In</h2>
        <!-- Login Form that asks for email and password -->
        <form class="login-form mt-3" action="index.php" method="POST" onsubmit="validate();">
            <div class="form-group row container-fluid">
                <label for="email" class="col-sm-2 col-form-label login-label">Email</label>
                <div class="col-lg-6 col-md-6 col-sm-10">
                <input type="text" class="form-control" id="email" name="email" placeholder="email@example.gx" onkeyup="check();">
                </div>
            </div>
            <div class="form-group row container-fluid">
                <label for="pwd" class="col-sm-2 col-form-label login-label">Password</label>
                <div class="col-lg-6 col-md-6 col-sm-10">
                <input type="password" class="form-control" id="pwd" name="pwd" placeholder="password" onkeyup="check();">
                <p class="my-3" id="login-hint"></p>
                </div>
            </div>
            <button class="btn btn-info ml-3 mb-3" type="submit">Log In</button>
            <input type="hidden" name="signal" id="signal">
        </form>
        <!-- If the user does not have an account, they can sign up by going to the signup page -->
        <button class="btn btn-info ml-3 mb-3" type="button" onclick="window.location.href='signup.php';">Sign Up</button>

        <!-- Footer -->
		<footer>
			<h5 class="mr-3 mt-3">Made by the crew of the <i>Starsprinter</i>, 3023</h5>
		</footer>

        <!-- Bootstrap JavaScript CDN -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>