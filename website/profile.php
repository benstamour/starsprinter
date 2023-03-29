<?php
    session_start();
    include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Profile | The Starsprinter Scavengers</title>
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
                        <li class="nav-item nav-user active rightalign"><a class="nav-link nav-active" href="profile.php?id='.$_SESSION["userid"].'">Hi, '.$_SESSION["name"].'!<span class="sr-only">(current)</span></a></li>
                    </ul>';
                    echo '<ul class="navbar-nav d-lg-none">
                        <li class="nav-item nav-user active"><a class="nav-link nav-active" href="profile.php?id='.$_SESSION["userid"].'">Profile/Cart</a></li>
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
            // Check if user has proper permissions to access this page
            $id = $_GET['id'];
            if($_SESSION["usercode"] != "0" && $id != $_SESSION["userid"])
            {
                echo "<div class='permission-error mx-3 my-3'>You don't have permission to view this page!</div>";
                exit;
            }

            // complete purchase
            if($_POST["totalcost"])
            {
                $purchase_cost = intval(substr($_POST["totalcost"],2));
                $isvalidpurchase = true;
                $sql = 'SELECT `cartid`, `itemid`, `quantity` FROM `cart` WHERE `userid` = '.$id;
                $result = mysqli_query($link, $sql);
                if($result)
                {
                    while($row = mysqli_fetch_row($result))
                    {
                        $itemid = $row[1];
                        $quantity = $row[2];

                        // verify quantities of each item in cart
                        $sql = 'SELECT `quantity` FROM `items` WHERE `id` = '.$itemid;
                        $quantity_result = mysqli_query($link, $sql);
                        $quantityrow = mysqli_fetch_row($quantity_result);
                        $curquantity = $quantityrow[0];
                        if($curquantity < $quantity)
                        {
                            $isvalidpurchase = false;
                            echo '<div class="alert alert-info alert-dismissible fade show mx-3 my-3" role="alert">
                                There was an error making your purchase. There may not be enough stock of one or more of the items in your cart.
                                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
                            break;
                        }
                    }
                    if($isvalidpurchase)
                    {
                        // add items in cart into purchases database and remove them from cart database
                        mysqli_data_seek($result, 0);
                        while($row = mysqli_fetch_row($result))
                        {
                            $sql = 'SELECT MAX(`purchaseid`) as id FROM `purchases`';
                            $result2 = mysqli_query($link, $sql);
                            if (!$result2) {
                                echo "DB Error, could not query the database\n";
                                echo 'MySQL Error: '.mysql_error();
                                exit;
                            }
                            $rowmax = mysqli_fetch_assoc($result2);
                            $nextpurchaseid = $rowmax['id'] + 1;

                            $cartid = $row[0];
                            $itemid = $row[1];
                            $quantity = $row[2];
                            $sql = 'INSERT INTO `purchases`(`purchaseid`, `userid`, `itemid`, `quantity`) VALUES ('.$nextpurchaseid.','.$_SESSION["userid"].','.$itemid.','.$quantity.')';
                            $result2 = mysqli_query($link, $sql);
                            $sql = 'DELETE FROM `cart` WHERE `cartid` = '.$cartid;
                            $result2 = mysqli_query($link, $sql);

                            $sql = 'SELECT `quantity` FROM `items` WHERE `id` = '.$itemid;
                            $quantity_result = mysqli_query($link, $sql);
                            $quantityrow = mysqli_fetch_row($quantity_result);
                            $curquantity = $quantityrow[0];
                            $newquantity = $curquantity - $quantity;
                            $sql = 'UPDATE `items` SET `quantity`='.$newquantity.' WHERE `id` = '.$itemid;
                            $quantity_result = mysqli_query($link, $sql);
                        }
                        echo '<div class="alert alert-info alert-dismissible fade show mx-3 my-3" role="alert">
                            You have successfully completed your purchase; thank you!
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
                    }
                }
            }
            // remove from cart
            if($_POST["remove-item"])
            {
                $cartid = $_POST["remove-item"];
                $sql = 'DELETE FROM `cart` WHERE `cartid` = '.$cartid;
                $result = mysqli_query($link, $sql);
                echo '<div class="alert alert-info alert-dismissible fade show mx-3 my-3" role="alert">
                    Item removed from cart!
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            }

            // profile text
            $sql = 'SELECT `fname`, `lname`, `birthdate`, `email` FROM `users` WHERE `id` = '.$id;
            $result = mysqli_query($link, $sql);
            if($result)
            {
                $row = mysqli_fetch_row($result);
                $fname = $row[0];
                $lname = $row[1];
                $birthdate = $row[2];
                $email = $row[3];
                // display profile information
                echo '<div class="profile-info mx-3 my-3">';
                echo '<h2 class="profile-name home-heading atomicage" style="text-align: left">'.$fname.' '.$lname.'</h2>';
                echo '<h5 class="profile-trait"><b>Birthday:</b> '.$birthdate.'</h5>';
                echo '<h5 class="profile-trait"><b>Email:</b> '.$email.'</h5></div>';

                if($id == intval($_SESSION["userid"]))
                {
                    $sql = 'SELECT `itemid`, `quantity`, `cartid` FROM `cart` WHERE `userid`='.$id;
                    $result = mysqli_query($link, $sql);
                    if(mysqli_num_rows($result) > 0)
                    {
                        $carttotal = 0;
                        echo '<hr style="border: 1px solid white"><h2 class="profile-cart-title home-heading atomicage mx-3" style="text-align: left">Your Cart</h2>';
                        while ($row = mysqli_fetch_row($result))
                        {
                            // display information on each item in cart
                            $curitemid = $row[0];
                            $curquantity = $row[1];
                            $cartid = $row[2];
                            $itemsql = 'SELECT `name`, `price`, `image` FROM `items` WHERE `id`='.$curitemid;
                            $itemresult = mysqli_query($link, $itemsql);
                            $itemrow = mysqli_fetch_row($itemresult);
                            $itemname = $itemrow[0];
                            $itemprice = $itemrow[1];
                            $itempic = $itemrow[2];
                            $totalitemprice = $curquantity * $itemprice;
                            $carttotal += $totalitemprice;
                            if(file_get_contents($itempic))
                            {
                                echo '<div class="itemresult" style="border: bottom;"><img class="cartpic" alt="'.$name.'" src="data:image/jpg;base64,'.base64_encode(file_get_contents($itempic)).'" />';
                            }
                            else
                            {
                                echo '<div class="itemresult" style="border: bottom;"><img class="cartpic" alt="'.$name.'" src="data:image/jpg;base64,'.base64_encode($itempic).'" />';
                            }
                            echo '<div id="cartiteminfo">';
                            echo '<h2 class="itemname"><a class="itemlink" href="https://bensta.epizy.com/starsprinter/item.php?id='.$curitemid.'">'.$itemname.'</a> <form class="deletecartform" style="display: inline-block" action="profile.php?id='.$_SESSION["userid"].'" method="POST" onsubmit=""><input hidden name="remove-item" value="'.$cartid.'"><button type="submit" class="deletecartbutton" style="background: none; border: none; padding: none" href=profile.php?id='.$id.'><i class="fa-solid fa-xmark fa-2xs"></i></button></form></h2>';
                            echo '<h5 class="itemtrait"><b>Quantity:</b> '.$curquantity.'</h5>';
                            echo '<h5 class="itemtrait"><b>Price:</b> §'.$totalitemprice.'</h5></div><hr style="clear: both">';



                            /* edit cart quantity
                             <a class="editcartitem" href=edititem.php?id='.$id.'><i class="fa-sharp fa-solid fa-pen-to-square"></i></a>
                            echo '<form class="editquantityform" action="item.php?id='.$id.'" method="POST" onsubmit="">
                                <div class="modal fade quantitymodal" id="quantityModal" tabindex="-1" role="dialog" aria-labelledby="quantityModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content bg-dark">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="quantityModalLabel">Edit Quantity</h5>
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
                                                <button type="submit" class="btn btn-info">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>';*/
                        }
                        echo '<h5 class="itemtrait"><b>Total Cost:</b> §'.$carttotal.'</h5>';
                        echo '<form action="profile.php?id='.$id.'" method="POST" onsubmit="">';
                        echo '<input hidden type="number" class="form-control-plaintext" id="totalcost" name="totalcost" value="'.$carttotal.'"/>';
                        //echo '<input type="text" readonly class="form-control-plaintext" id="totalcost" value="§'.$carttotal.'">';
                        //echo '<h5 class="itemtrait"><b>Total Cost:</b> §'.$carttotal.'</h5><hr style="clear: both">';
                        /*echo '<h5><div class="form-group row container-fluid">
                            <label for="totalcost" class="col-sm-2 col-form-label totalcost-item-label"><b>Total Cost:</b></label>
                            <div class="col-lg-6 col-md-6 col-sm-10">
                                <input readonly type="type" class="form-control-plaintext" style="color: white" id="totalcost" name="totalcost" value="§'.$carttotal.'"/>
                            </div>
                        </div></h5>';*/
                        /*echo '<div class="form-group row container-fluid" style="vertical-align: middle">';
                        echo '<label for="totalcost" class="col-sm-2 col-form-label total-cost-label"><h5><b>Total Cost:</b></h5></label>';
                        echo '<div class="col-sm-10">
                            <h5><input type="text" readonly class="col-lg-6 col-md-6 col-sm-10 form-control-plaintext" style="color: white" id="totalcost" value="§'.$carttotal.'"></h5>
                            </div>';
                        echo '</div>';*/
                        echo '<button class="btn btn-info mx-3" type="submit">Purchase</button>';
                        echo '</form><hr style="clear: both">';
                    }
                }
            }
        ?>
		
		<!-- Footer -->
		<footer>
			<h5 class="mr-3 mt-3">Made by the crew of the <i>Starsprinter</i>, 3023</h5>
		</footer>

        <!-- Bootstrap JavaScript CDN -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>