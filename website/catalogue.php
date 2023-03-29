<?php
    session_start();
    include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Catalogue | The Starsprinter Scavengers</title>
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
			  <li class="nav-item active">
				<a class="nav-link nav-active" href="catalogue.php">Search Catalogue<span class="sr-only">(current)</span></a>
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
        <h2 class="catalogue-heading mt-3">Our Catalogue</h2>
        <h5 class="catalogue-text mx-3 my-3">We're always adding new things to our catalogue, so keep checking back for new stuff!</h5>

        <!-- Catalogue Search -->
        <div class="container-fluid row">
            <div class="col-lg-2 col-md-0 col-sm-0"></div>
            <div class="col-lg-8 col-md-12 col-sm-12">
                <form action="catalogue.php" method="GET" onsubmit="">
                    <div class="input-group mx-3 my-3">
                        <?php
                            // text input for search terms
                            if($_GET["text-input"] != "")
                            {
                                echo '<input type="text" class="form-control w-25" name="text-input" aria-label="search terms" value="'.$_GET["text-input"].'">';
                            }
                            else
                            {
                                echo '<input type="text" class="form-control w-25" name="text-input" aria-label="search terms">';
                            }
                            // dropdown that determines how results are sorted
                            if($_GET["sort-by"] != "")
                            {
                                if($_GET["sort-by"] == "name-asc")
                                {
                                    echo '<select class="form-select" name="sort-by" aria-label="select sorting">
                                        <option selected value="name-asc">Name Ascending</option>
                                        <option value="name-desc">Name Descending</option>
                                        <option value="price-asc">Price Ascending</option>
                                        <option value="price-desc">Price Descending</option>
                                        </select>';
                                }
                                else if($_GET["sort-by"] == "name-desc")
                                {
                                    echo '<select class="form-select" name="sort-by" aria-label="select sorting">
                                        <option value="name-asc">Name Ascending</option>
                                        <option selected value="name-desc">Name Descending</option>
                                        <option value="price-asc">Price Ascending</option>
                                        <option value="price-desc">Price Descending</option>
                                        </select>';
                                }
                                else if($_GET["sort-by"] == "price-asc")
                                {
                                    echo '<select class="form-select" name="sort-by" aria-label="select sorting">
                                        <option value="name-asc">Name Ascending</option>
                                        <option value="name-desc">Name Descending</option>
                                        <option selected value="price-asc">Price Ascending</option>
                                        <option value="price-desc">Price Descending</option>
                                        </select>';
                                }
                                else if($_GET["sort-by"] == "price-desc")
                                {
                                    echo '<select class="form-select" name="sort-by" aria-label="select sorting">
                                        <option value="name-asc">Name Ascending</option>
                                        <option value="name-desc">Name Descending</option>
                                        <option value="price-asc">Price Ascending</option>
                                        <option selected value="price-desc">Price Descending</option>
                                        </select>';
                                }
                            }
                            else
                            {
                                echo '<select class="form-select" name="sort-by" aria-label="select sorting">
                                    <option selected disabled hidden>Sort By</option>
                                    <option value="name-asc">Name Ascending</option>
                                    <option value="name-desc">Name Descending</option>
                                    <option value="price-asc">Price Ascending</option>
                                    <option value="price-desc">Price Descending</option>
                                </select>';
                            }
                            // checkbox that decides whether only items that have an available quantity of at least 1 should be displayed
                            if($_GET["in-stock-only"])
                            {
                                echo '<div class="input-group-append">
                                    <span class="input-group-text">Only in-stock items?&nbsp;&nbsp;&nbsp;  
                                    <input type="checkbox" name="in-stock-only" aria-label="Checkbox for following text input" checked>
                                    </span>
                                </div>';
                            }
                            else
                            {
                                echo '<div class="input-group-append">
                                    <span class="input-group-text">Only in-stock items?&nbsp;&nbsp;&nbsp;  
                                    <input type="checkbox" name="in-stock-only" aria-label="Checkbox for following text input">
                                    </span>
                                </div>';
                            }
                        ?>
                        <button class="btn btn-info" type="submit">Search</button>
                    </div>                
                </form>
            </div>
        </div>


		<?php
            // get starting point of query
            $page = $_GET['page'];
            if(!$page)
            {
                $page = "1";
            }
            $page = intval($page);
            $pagination_val = 5; // number of results per page;
            $start = $page*$pagination_val - $pagination_val;

            // create SQL query from search terms
            $query_select = 'SELECT * FROM `items` WHERE ';
            $query_count = 'SELECT COUNT(*) FROM `items` WHERE ';
            $query_in_stock = '`quantity` > 0 && (';
            $query_match_text = 'UPPER(`name`) LIKE CONCAT("%", UPPER("'.$_GET["text-input"].'"), "%") || UPPER(`tags`) LIKE CONCAT("%", UPPER("'.$_GET["text-input"].'"), "%")';
            $query_in_stock_endbracket = ')';
            $query_order_name = ' ORDER BY `name`';
            $query_order_price = ' ORDER BY `price`';
            $query_order_descend = ' DESC';
            $query_limit = ' LIMIT '.$pagination_val;
            $query_inflimit = ' LIMIT 1000';
            $query_offset = ' OFFSET '.$start;

            $sql = ""; //$query_select;
            if($_GET["in-stock-only"])
            {
                $sql.=$query_in_stock;
                $sql.=$query_match_text;
                $sql.=$query_in_stock_endbracket;
            }
            else
            {
                $sql.=$query_match_text;
            }
            if($_GET["sort-by"] == "" || $_GET["sort-by"] == "name-asc" || $_GET["sort-by"] == "name-desc")
            {
                $sql.=$query_order_name;
            }
            else
            {
                $sql.=$query_order_price;
            }
            if($_GET["sort-by"] == "name-desc" || $_GET["sort-by"] == "price-desc")
            {
                $sql.=$query_order_descend;
            }
            $sql_search = $sql.$query_limit;
            $sql = $sql.$query_inflimit;
            if($start > 0)
            {
                $sql_search.=$query_offset;
            }
            $sql_search = $query_select.$sql_search;
            $sql = $query_count.$sql;

            $result = mysqli_query($link, $sql);
            $row = mysqli_fetch_row($result);
            $num_rows = $row[0];
            $num_pages = intval(ceil($num_rows/$pagination_val));

            mysqli_free_result($result);
            $result = mysqli_query($link, $sql_search);

            echo '<hr style="clear: both; margin: 10px 0 10px 0; border: 1px solid white">';
            
            // displaying search results
            while ($row = mysqli_fetch_row($result))
            {
                $id = $row[0];
                $name = $row[1];
                $quantity = $row[2];
                $price = $row[3];
                $type = $row[4];
                $tags = $row[5];
                $descr = $row[6];
                $notes = $row[7];
                $image = $row[8];
                if(file_get_contents($image))
                {
                    echo '<div class="itemresult" style="border: bottom;"><img class="itempic" alt="'.$name.'" src="data:image/jpg;base64,'.base64_encode(file_get_contents($image)).'" />';
                }
                else
                {
                    echo '<div class="itemresult" style="border: bottom;"><img class="itempic" alt="'.$name.'" src="data:image/jpg;base64,'.base64_encode($image).'" />';
                }
                echo '<div id="iteminfo">';
                echo '<h2 class="itemname"><a class="itemlink" href="https://bensta.epizy.com/starsprinter/item.php?id='.$id.'">'.$name.'</a></h2>';
                if($quantity > 0)
                {
                    echo '<h5 class="itemtrait">'.$quantity.' available • §'.$price.'</h5><br/>';
                }
                else
                {
                    echo '<h5 class="itemtrait"><span class="unavailable"><i>Currently out of stock</i></span> • §'.$price.'</h5><br/>';
                }
                if($descr)
                {
                    echo '<h5 class="itemtrait d-none d-md-block">'.$descr.'</h5>';
                }
                if($notes)
                {
                    echo '<h5 class="itemtrait d-none d-md-block"><b>NOTES:</b> '.$notes.'</h5>';
                }
                echo '</div></div><hr style="clear: both; margin: 10px 0 10px 0; border: 1px solid white">';
            }

            // pagination
            $preprev_page = $page - 2;
            $prev_page = $page - 1;
            $next_page = $page + 1;
            $nexnext_page = $page + 2;

            echo '<nav aria-label="...">
                <ul class="pagination ml-3 justify-content-center">';
            
            // adding search terms to URL
            $pageurl = 'catalogue.php';
            if($_GET["text-input"])
            {
                $pageurl.='?text-input='.$_GET["text-input"];
            }
            if($_GET["sort-by"] && $_GET["text-input"])
            {
                $pageurl.='&sort-by='.$_GET["sort-by"];
            }
            else if($_GET["sort-by"])
            {
                $pageurl.='?sort-by='.$_GET["sort-by"];
            }
            if($_GET["in-stock-only"] && ($_GET["text-input"] || $_GET["sort-by"]))
            {
                $pageurl.='&in-stock-only='.$_GET["in-stock-only"];
            }
            else if($_GET["in-stock-only"])
            {
                $pageurl.='?in-stock-only='.$_GET["in-stock-only"];
            }
            if($_GET["text-input"] || $_GET["sort-by"] || $_GET["in-stock-only"])
            {
                $pageurl.='&page=';
            }
            else
            {
                $pageurl.='?page=';
            }
            
            // previous page
            if($page > 1)
            {
                echo '<li class="page-item">
                    <a class="page-link" href="'.$pageurl.$prev_page.'">Previous</a>
                </li>';
            }
            else
            {
                echo '<li class="page-item disabled">
                    <a class="page-link disabled-page" href="#" tabindex="-1">Previous</a>
                </li>';
            }
            // adjacent page
            if($page > 2)
            {
                echo '<li class="page-item"><a class="page-link" href="'.$pageurl.$preprev_page.'">'.$preprev_page.'</a></li>';
            }
            if($page > 1)
            {
                echo '<li class="page-item"><a class="page-link" href="'.$pageurl.$prev_page.'">'.$prev_page.'</a></li>';
            }
            echo '<li class="page-item active">
                <a class="page-link active-page" href="#">'.$page.'<span class="sr-only">(current)</span></a>
            </li>';
            if($page < $num_pages)
            {
                echo '<li class="page-item"><a class="page-link" href="'.$pageurl.$next_page.'">'.$next_page.'</a></li>';
            }
            if($page < $num_pages - 1)
            {
                echo '<li class="page-item"><a class="page-link" href="'.$pageurl.$nexnext_page.'">'.$nexnext_page.'</a></li>';
            }
            // next page
            if($page < $num_pages)
            {
                echo '<li class="page-item">
                    <a class="page-link" href="'.$pageurl.$next_page.'">Next</a>
                </li>';
            }
            else
            {
                echo '<li class="page-item disabled">
                    <a class="page-link disabled-page" href="#" tabindex="-1">Next</a>
                </li>';
            }
            // end pagination navigation
            echo '</ul>
            </nav>';
        ?>
		
		<!-- Footer -->
		<footer>
			<h5 class="mr-3 mt-3">Made by the crew of the <i>Starsprinter</i>, 3023</h5>
		</footer>

        <!-- Bootstrap JavaScript CDN -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>