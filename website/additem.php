<?php
    session_start();
    include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add an Item | The Starsprinter Scavengers</title>
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
                if($_SESSION["loginsuccess"])
                {
                    if($_SESSION['usercode'] === "0")
                    {
                        echo '<li class="nav-item active"><a class="nav-link nav-active" href="additem.php">Add Item<span class="sr-only">(current)</span></a></li>';
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
        <h2 class="home-heading mt-3">Add an Item</h2>

        <!-- Check if user has proper permissions to access this page -->
        <?php
            if($_SESSION["usercode"] != "0")
            {
                echo "<div class='permission-error mx-3 my-3'>You don't have permission to view this page!</div>";
                exit;
            }
        ?>

        <?php
            // if form was submitted, add item to database
            $name = addslashes($_REQUEST['name']);
            $quantity = $_REQUEST['quantity'];
            $price = $_REQUEST['price'];
            $type = addslashes($_REQUEST['type']);
            $tags = addslashes($_REQUEST['tags']);
            $descr = addslashes($_REQUEST['descr']);
            $notes = addslashes($_REQUEST['notes']);
            if(isset($_POST['add-submit']))
            { 
                $filepath = "pictures/".$_FILES["photo"]["name"];
                if(move_uploaded_file($_FILES["photo"]["tmp_name"], $filepath))
                {
                    $source = file_get_contents($filepath);
                    $base64 = base64_encode($source);
                    $blob = 'data:image/jpg;base64,'.$base64;
                    
                    $sql = 'SELECT MAX(`id`) as id FROM `items`';
                    $result = mysqli_query($link, $sql);

                    if (!$result) {
                        echo "DB Error, could not query the database\n";
                        echo 'MySQL Error: '.mysql_error();
                        exit;
                    }

                    $row = mysqli_fetch_assoc($result);
                    $id = $row['id'] + 1;
                    mysqli_free_result($result);
                    
                    $sql = "INSERT INTO `items`(`id`, `name`, `quantity`, `price`, `type`, `tags`, `description`, `notes`, `image`) VALUES (".$id.",'".$name."','".$quantity."','".$price."','".$type."','".$tags."','".$descr."','".$notes."','".$blob."')";

                    $result = mysqli_query($link, $sql);
                    if($result)
                    {
                        echo '<div class="alert alert-info alert-dismissible fade show mx-3 my-3" role="alert">
                            Item has been successfully added to the catalogue!
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
                    }
                    else
                    {
                        echo '<div class="alert alert-info alert-dismissible fade show mx-3 my-3" role="alert">
                            Unable to add item to catalogue.
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
                    }
                } 
                else
                {
                    echo '<div class="alert alert-info alert-dismissible fade show mx-3 my-3" role="alert">
                        There was an error uploading your file.
                        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
                }
            }
        ?>

        <!-- Form to add item to database -->
        <form action="additem.php" class="add-item-form mt-3" method="POST" enctype="multipart/form-data">
            <div class="form-group row container-fluid">
                <label for="name" class="col-sm-2 col-form-label add-item-label">Name</label>
                <div class="col-lg-6 col-md-6 col-sm-10">
                    <input required type="text" class="form-control" id="name" name="name">
                </div>
            </div>
            <div class="form-group row container-fluid">
                <label for="quantity" class="col-sm-2 col-form-label add-item-label">Quantity Available</label>
                <div class="col-lg-6 col-md-6 col-sm-10">
                    <input required type="number" class="form-control" id="quantity" name="quantity">
                </div>
            </div>
            <div class="form-group row container-fluid">
                <label for="price" class="col-sm-2 col-form-label add-item-label">Price</label>
                <div class="col-lg-6 col-md-6 col-sm-10">
                    <input required type="number" class="form-control" id="price" name="price">
                </div>
            </div>
            <div class="form-group row container-fluid">
                <label for="type" class="col-sm-2 col-form-label add-item-label">Type</label>
                <div class="col-lg-6 col-md-6 col-sm-10">
                    <input type="text" class="form-control" id="type" name="type">
                </div>
            </div>
            <div class="form-group row container-fluid">
                <label for="tags" class="col-sm-2 col-form-label add-item-label">Tags</label>
                <div class="col-lg-6 col-md-6 col-sm-10">
                    <input type="text" class="form-control" id="tags" name="tags">
                </div>
            </div>
            <div class="form-group row container-fluid">
                <label for="descr" class="col-sm-2 col-form-label add-item-label">Description</label>
                <div class="col-lg-6 col-md-6 col-sm-10">
                    <textarea rows="5" cols="50" class="form-control" id="descr" name="descr"></textarea>
                </div>
            </div>
            <div class="form-group row container-fluid">
                <label for="notes" class="col-sm-2 col-form-label add-item-label">Notes</label>
                <div class="col-lg-6 col-md-6 col-sm-10">
                    <textarea rows="5" cols="50" class="form-control" id="notes" name="notes"></textarea>
                </div>
            </div>
            <div class="form-group row container-fluid">
                <label for="photo" class="col-sm-2 col-form-label add-item-label">Photo</label>
                <div class="col-lg-6 col-md-6 col-sm-10">
                    <input type="file" class="form-control" id="photo" name="photo">
                </div>
            </div>
            <button class="btn btn-info ml-3 mb-3" name="add-submit" type="submit">Add Item</button>
        </form>
		
		<!-- Footer -->
		<footer>
			<h5 class="mr-3 mt-3">Made by the crew of the <i>Starsprinter</i>, 3023</h5>
		</footer>

        <!-- Bootstrap JavaScript CDN -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>