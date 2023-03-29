<?php
    session_start();
    include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            $id = $_GET['id'];

            $sql = 'SELECT `name` FROM `items` WHERE id = '.$id;
            $result = mysqli_query($link, $sql);
            if($result)
            {
                $row = mysqli_fetch_row($result);
                $name = $row[0];
            }

            echo '<title>'.$name.' | The Starsprinter Scavengers</title>';
        ?>
        <title>The Starsprinter Scavengers</title>
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
        <?php
            $id = $_GET['id'];

            $sql = 'SELECT * FROM `items` WHERE id = '.$id;
            $result = mysqli_query($link, $sql);
            if($result)
            {
                $row = mysqli_fetch_row($result);
                $name = $row[1];
                $quantity = $row[2];
                $price = $row[3];
                $type = $row[4];
                $tags = $row[5];
                $descr = $row[6];
                $notes = $row[7];
                $image = $row[8];

                if($quantity_added = $_POST["quantity-added"])
                {
                    $sql = 'SELECT `cartid`, `quantity` FROM `cart` WHERE `userid` = '.$_SESSION['userid'].' && `itemid` = '.$id;
                    $result = mysqli_query($link, $sql);
                    if($row = mysqli_fetch_row($result))
                    {
                        $newq = intval($row[1]) + $quantity_added;
                        if($newq > $quantity)
                        {
                            echo '<div class="alert alert-info alert-dismissible fade show mx-3 my-3" role="alert">
                                    Sorry, we don\'t have enough of that item in stock!
                                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>';
                        }
                        else
                        {
                            $sql = 'UPDATE `cart` SET `quantity` = '.$newq.' WHERE `cartid` = '.$row[0];
                            $result = mysqli_query($link, $sql);

                            if (!$result) {
                                echo "DB Error, could not query the database\n";
                                echo 'MySQL Error: '.mysql_error();
                                exit;
                            }
                            else
                            {
                                echo '<div class="alert alert-info alert-dismissible fade show mx-3 my-3" role="alert">
                                    Successfully added to cart!
                                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>';
                            }
                        }
                    }
                    else
                    {
                        $sql = 'SELECT MAX(`cartid`) as id FROM `cart`';
                        $result = mysqli_query($link, $sql);

                        if (!$result) {
                            echo "DB Error, could not query the database\n";
                            echo 'MySQL Error: '.mysql_error();
                            exit;
                        }

                        $row = mysqli_fetch_assoc($result);
                        $nextcartid = $row['id'] + 1;
                        $sql = 'INSERT INTO `cart`(`cartid`, `userid`, `itemid`, `quantity`) VALUES ('.$nextcartid.', '.$_SESSION['userid'].', '.$id.', '.$quantity_added.')';
                        $result = mysqli_query($link, $sql);

                        if (!$result) {
                            echo "DB Error, could not query the database\n";
                            echo 'MySQL Error: '.mysql_error();
                            exit;
                        }
                        else
                        {
                            echo '<div class="alert alert-info alert-dismissible fade show mx-3 my-3" role="alert">
                                Successfully added to cart!
                                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
                        }
                    }
                }

                if(file_get_contents($image))
                {
                    echo '<div class="itemresult itempage-result" style="border: bottom;"><img class="itempic" alt="'.$name.'" src="data:image/jpg;base64,'.base64_encode(file_get_contents($image)).'" />';
                }
                else
                {
                    echo '<div class="itemresult itempage-result" style="border: bottom;"><img class="itempic" alt="'.$name.'" src="data:image/jpg;base64,'.base64_encode($image).'" />';
                }
                echo '<div id="iteminfo">';
                echo '<h2 class="itemname">'.$name;
                // user must be logged in to add to cart
                if($_SESSION["loginsuccess"] && $quantity > 0)
                {
                    echo ' <a href="#" data-bs-toggle="modal" data-bs-target="#cartModal"><i class="editlink fa-solid fa-cart-plus"></i></a>';
                }

                // check user permissions to edit
                if($_SESSION["usercode"] == "0")
                {
                    echo ' <a class="editlink" href=edititem.php?id='.$id.'><i class="fa-sharp fa-solid fa-pen-to-square"></i></a>';
                }
                echo '</h2>';

                echo '<form class="addtocartform" action="item.php?id='.$id.'" method="POST" onsubmit="">
                <div class="modal fade cartmodal" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content bg-dark">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cartModalLabel">Add to Cart</h5>
                                <button type="button" class="close btn-close-white" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" min="1" max="'.$quantity.'" name="quantity-added" aria-label="add to cart popup form">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-info">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
                </form>';

                if($quantity > 0)
                {
                    echo '<h5 class="itemtrait">'.$quantity.' available • §'.$price.'</h5><br/>';
                }
                else
                {
                    echo '<h5 class="itemtrait"><span class="unavailable"><i>Currently unavailable</i></span> • §'.$price.'</h5><br/>';
                }
                if($descr)
                {
                    echo '<h5 class="itemtrait">'.$descr.'</h5>';
                }
                if($notes)
                {
                    echo '<h5 class="itemtrait"><b>NOTES:</b> '.$notes.'</h5>';
                }

                echo '<hr class="transparent-hr" style="clear: both">';
            }
        ?>
		
		<!-- Footer -->
		<footer>
			<h5 class="mr-3 mt-3">Made by the crew of the <i>Starsprinter</i>, 3023</h5>
		</footer>

        <!-- Bootstrap JavaScript CDN -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>