<?php
    session_start();
    include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Home Page | The Starsprinter Scavengers</title>
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

        <!-- Signup code -->
        <?php
            // for signing up
            if($_GET['signup'] == "true")
            {
                $fname = addslashes($_REQUEST['fname']);
                $lname = addslashes($_REQUEST['lname']);
                $email = addslashes($_REQUEST['email']);
                $dob = addslashes($_REQUEST['dob']);
                $bankid = addslashes($_REQUEST['bankid']);
                $pwd = addslashes($_REQUEST['pwd1']);
                if(isset($_POST['add-submit']))
                { 
                    $sql = 'SELECT MAX(`id`) as id FROM `users`';
                    $result = mysqli_query($link, $sql);

                    if (!$result) {
                        echo "DB Error, could not query the database\n";
                        echo 'MySQL Error: '.mysql_error();
                        exit;
                    }

                    $row = mysqli_fetch_assoc($result);
                    $id = $row['id'] + 1;
                    mysqli_free_result($result);
                    
                    $sql = "INSERT INTO `users`(`id`, `usercode`, `fname`, `lname`, `birthdate`, `email`, `password`, `bankid`) VALUES (".$id.",1,'".$fname."','".$lname."','".$dob."','".$email."','".$pwd."','".$bankid."')";

                    $result = mysqli_query($link, $sql);
                    if($result)
                    {
                        session_regenerate_id();
                        echo '<div class="alert alert-info alert-dismissible fade show mx-3" role="alert">
                            You have successfully signed up!
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
                        $_SESSION["loginsuccess"] = true;

                        $_SESSION['email'] = $email;
                        $_SESSION['usercode'] = 1;
                        $_SESSION['userid'] = $id;
                        $_SESSION['name'] = $fname;
                    }
                    else
                    {
                        echo '<div class="alert alert-info alert-dismissible fade show mx-3" role="alert">
                            There was an error signing up.
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
                    }
                }
            }

            // for logging in
            else
            {
                $email = $_REQUEST['email'];
                if ($email != "") {
                    $sql = 'SELECT `usercode`, `id`, `fname` FROM `users` WHERE `email` = "'.$email.'"';
                    $result = mysqli_query($link, $sql);

                    if(mysqli_num_rows($result) == 1)
                    {
                        session_regenerate_id();
                        $row = mysqli_fetch_row($result);
                        $_SESSION['email'] = $email;
                        $_SESSION['usercode'] = $row[0];
                        $_SESSION['userid'] = $row[1];
                        $_SESSION['name'] = $row[2];
                        echo '<div class="alert alert-info alert-dismissible fade show mx-3" role="alert">
                            You have successfully logged in!
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
                        $_SESSION["loginsuccess"] = true;
                        
                    }
                }
            }
        ?>

		<!-- Navigation Bar -->
		<nav class="navbar navbar-expand-lg navbar-dark">
		  <a class="navbar-brand" href="index.php"><img class="navbarlogo" src="pictures/sslogo.jpg" /></a>
		  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		  </button>

		  <div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
			  <li class="nav-item active">
				<a class="nav-link nav-active" href="index.php">Home<span class="sr-only">(current)</span></a>
			  </li>
			  <li class="nav-item">
				<a class="nav-link" href="crew.php">The Crew</a>
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
		<h2 class="home-heading mt-3">Welcome to Our Spaceship!</h2>
        <h5 class="home-text mx-3 my-3">You've just discovered the best treasure hunting gang in this part of the galaxy! Since 3022, our mission has been to give you access to some of the most amazing, powerful, and/or dangerous goods that the universe has to offer. Here, you can buy things that you can't just get anywhere - not without risking your life, at least.
        <br/><br/>
        Enjoy your stay here, and check out all the cool stuff we have to offer! We hope you like it, because for each item we have, we probably almost died getting it.
        <br/><br/>
        We're always getting new stuff in - check out some of the newest additions to our collection below!</h5>
        <hr style="border: 1px solid white">

        <?php
            $sql = 'SELECT `name`, `quantity`, `price`, `image`, `id` FROM `items` ORDER BY `id` DESC LIMIT 3';
            $result = mysqli_query($link, $sql);
            
            echo '<div class="row container-fluid newest-additions">';
            while ($row = mysqli_fetch_row($result))
            {
                echo '<div class="new-item-col col-lg-4 col-md-4 col-sm-12">';
                $name = $row[0];
                $quantity = $row[1];
                $price = $row[2];
                $image = $row[3];
                $id = $row[4];
                if(file_get_contents($image))
                {
                    echo '<div class="new-item"><img class="new-itempic" alt="'.$name.'" src="data:image/jpg;base64,'.base64_encode(file_get_contents($image)).'" />';
                }
                else
                {
                    echo '<div class="new-item"><img class="new-itempic" alt="'.$name.'" src="data:image/jpg;base64,'.base64_encode($image).'" />';
                }
                echo '<div id="iteminfo">';
                echo '<h2 class="new-itemname"><a class="itemlink" href="https://bensta.epizy.com/starsprinter/item.php?id='.$id.'">'.$name.'</a></h2>';
                if($quantity > 0)
                {
                    echo '<h5 class="new-itemtrait">'.$quantity.' available • §'.$price.'</h5><br/>';
                }
                else
                {
                    echo '<h5 class="new-itemtrait"><span class="unavailable"><i>Currently out of stock</i></span> • §'.$price.'</h5><br/>';
                }
                echo '</div></div></div>';
            }
            echo '</div>';
        ?>

        <p class="home-disclaimer mx-3"><i>DISCLAIMER: Everything on this site is completely fictional and a result of my imagination (the writing) and <a href="https://dream.ai/create">Dream AI</a> (the images). Please do not enter any personal information on this site.</i></p>
		
		<!-- Footer -->
		<footer>
			<h5 class="mr-3 mt-3">Made by the crew of the <i>Starsprinter</i>, 3023</h5>
		</footer>

        <!-- Bootstrap JavaScript CDN -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>