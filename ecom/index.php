<?php
// show server errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// start session
session_start();

// set session variables if you are not logged in.
if (!$_SESSION['Email']) {
    $_SESSION["Email"] = null;
    $_SESSION["First_Name"] = 'Guest';
    $_SESSION["Last_Name"] = null;
    $_SESSION["Rank"] = 5;
}

// connect to database
$db = new sqlite3('database/database.db');

// search results
$searchresult = null;
if (isset($_POST['searchbutton'])) {
    $search = $_POST['searchinput'];
    $searchquery = $db->prepare("SELECT * FROM items WHERE title LIKE ?");
    $searchquery->bindValue(1, "%$search%", SQLITE3_TEXT);
    $searchresult = $searchquery->execute();
}

    // get items from database
    if (isset($_GET['category'])) {
        $category = $_GET['category'];
        $result = $db->query("SELECT * FROM items WHERE catagory = '$category'");
    } else {
        $result = $db->query("SELECT * FROM items");
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/nav.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">Ecom</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <?php
            if ($_SESSION['Rank'] == '1') {
                echo '<li class="nav-item">
                <a class="nav-link" href="index/additem.php">Add item</a>
            </li>';
            }

            ?>
            <li class="nav-item">
                <a href="index/winkelmand.php" class="nav-link">Cart</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0 col-md-4" name="form" method="post">
            <div style="position: relative;">
                <input class="custom_search form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="searchinput">
                <span class="search_span"></span>
            </div>
            <input class="ml-lg-3 btn btn-success my-2 my-sm-0 search_button tx--white" type="submit" ripple="ripple" name="searchbutton">Search</input>
        </form>
    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        echo '<a href="index/logout.php">
    <button type="button" class="btn search_button tx--white" data-toggle="modal" data-target="#exampleModal">
        Logout
    </button>
    </a>';
    } else {
        echo '<a href="index/login.php">
    <button type="button" class="btn search_button tx--white" data-toggle="modal" data-target="#exampleModal">
        Login
    </button>
    </a>';
    }
    ?>
    </div>
</nav>
<!--search results-->
<div class="overlay" id="searchOverlay">
    <div class="container3">
        <?php
        // Display search results if available
        if ($searchresult && $searchresult->numColumns() > 0) {
            while ($row = $searchresult->fetchArray(SQLITE3_ASSOC)) {
                echo '<div class="item-info">';
                echo '<a href="index/item.php?id=' . $row['ID'] . '">';
                echo '<img src="img/' . $row['img1'] . '" alt="' . $row['title'] . '" class="item-image">';
                echo  $row['title'];
                echo '</a>';
                echo '</div>';
            }
        }
        ?>
    </div>
</div>
<div class="container">
    <header class="header">
        <h1 id="title" class="text-center">Ecom site!</h1>
        <p id="description" class="text-center">
            Welcome to my demo of an ecom site with a payment system.
            <br>
            <small>©Justin Nootenboom</small>
        </p>
    </header>

    <div class="dropdown2">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            Select category
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="index.php">All</a>
            <a class="dropdown-item" href="index.php?category=Hoodie">Hoodies</a>
            <a class="dropdown-item" href="index.php?category=T-shirt">T-shirt</a>
            <a class="dropdown-item" href="index.php?category=Hat">Hat</a>
            <a class="dropdown-item" href="index.php?category=Pants">Pants</a>
            <a class="dropdown-item" href="index.php?category=Other">Other</a>
        </div>
    </div>


        <div class="container2">
            <!--show items in a grid formation-->
            <?php
            while ($row = $result->fetchArray()) {
                echo '<div class="card" style="width: 16rem;">
    <img src="img/' . $row['img1'] . '" class="card-img-top" alt="...">
    <div class="card-body">
        <h5 class="card-title">' . $row['title'] . '</h5>
        <p class="card-text">' . $row['description'] . '</p>
        <p class="card-text">€' . $row['price'] . '</p>
        <a href="index/item.php?id=' . $row['ID'] . '" class="btn search_button tx--white">View item</a>
    </div>
</div>';
            }
            ?>
        </div>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="JS/script.js"></script>
</body>
</html>
