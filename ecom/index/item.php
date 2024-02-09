<?php
// report server errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//start session
session_start();

//connect to db
$db = new sqlite3('../database/database.db');

//check if there is a session
if (!$_SESSION['Email']){
    $_SESSION["Email"] = null;
    $_SESSION["First_Name"] = 'Guest';
    $_SESSION["Last_Name"] = null;
    $_SESSION["Rank"] = 5;
}

//search result
$searchresult = null;
if (isset($_POST['searchbutton'])) {
    $search = $_POST['searchinput'];
    $searchquery = $db->prepare("SELECT * FROM items WHERE title LIKE ?");
    $searchquery->bindValue(1, "%$search%", SQLITE3_TEXT);
    $searchresult = $searchquery->execute();
}

//get item from db
$item_ID = $_GET['id'];

$result = $db->query("SELECT * FROM items WHERE ID = '$item_ID'");
$row2 = $result->fetchArray();
$id = $row2['ID'];
$img1 = $row2['img1'];
$img2 = $row2['img2'];
$img3 = $row2['img3'];
$img4 = $row2['img4'];
$title = $row2['title'];
$price = $row2['price'];
$description = $row2['description'];
$left = $row2['left'];
$catagory = $row2['catagory'];

// Start the counter if it doesn't exist in the session
if (!isset($_SESSION['cart_counter'])) {
    $_SESSION['cart_counter'] = 1;
}

if (isset($_POST['add'])) {
    $numbofitem = $_POST['cart_quantity'];
    $itemid = $item_ID;

    // Use the current counter value as the key and increment it for the next item
    $cartKey = $_SESSION['cart_counter'];
    $_SESSION['cart_counter']++;

    // Create an associative array to hold numbofitem and itemid
    $_SESSION['cart'][$cartKey] = array(
        'numbofitem' => $numbofitem,
        'itemid' => $itemid
    );
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/nav.css">
    <link rel="stylesheet" href="../CSS/item.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="../index.php">Ecom</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="../index.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <?php
            if ($_SESSION['Rank'] == '1') {
                echo '<li class="nav-item">
                <a class="nav-link" href="additem.php">Add item</a>
            </li>';
            }

            ?>
            <li class="nav-item">
                <a href="winkelmand.php" class="nav-link">Cart</a>
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
            echo '<a href="logout.php">
    <button type="button" class="btn search_button tx--white" data-toggle="modal" data-target="#exampleModal">
        Logout
    </button>
    </a>';
        } else {
            echo '<a href="login.php">
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
                echo '<a href="item.php?id=' . $row['ID'] . '">';
                echo '<img src="../img/' . $row['img1'] . '" alt="' . $row['title'] . '" class="item-image">';
                echo  $row['title'];
                echo '</a>';
                echo '</div>';
            }
        }
        ?>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-md-5">
            <div class="main-img">
                <img id="photo" class="img-fluid" src="../img/<?php echo $img1; ?>" alt="ProductS">
                <div class="row my-3 previews">
                    <?php
                    if ($row2['img2']){
                        echo  '<div class="col-md-3">';
                        echo '<button onclick="view2()" style="border: none; background: transparent;">';
                        echo  '<img id="field2" src="../img/' . $row2['img2'] . '" alt="Sale">';
                        echo '</button>';
                        echo  '</div>';
                    }
                    if ($row2['img3'] ){
                        echo  '<div class="col-md-3">';
                        echo '<button onclick="view3()" style="border: none; background: transparent;">';
                        echo  '<img id="field3" src="../img/' . $row2['img3'] . '" alt="Sale">';
                        echo '</button>';
                        echo  '</div>';
                    }
                    if ($row2['img4']){
                        echo  '<div class="col-md-3" style="border: none; background: transparent;">';
                        echo '<button onclick="view4()">';
                        echo  '<img id="field4" src="../img/' . $row2['img4'] . '" alt="Sale">';
                        echo '</button>';
                        echo  '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="main-description px-2">
                <div class="category text-bold">
                    Category: <?php echo $catagory; ?>
                </div>
                <div class="product-title text-bold my-3">
                    <?php
                    echo $title;
                    ?>
                </div>


                <div class="price-area my-4">
                    <p class="new-price text-bold mb-1">$<?php echo $price; ?></p>
                    <p class="text-secondary mb-1">There are <?php echo $left; ?> of these products left!</p>
                    <p class="text-secondary mb-1">(Additional tax may apply on checkout)</p>
                </div>


                <div class="buttons d-flex my-5">
                    <form method="post">
                        <input type="hidden" value="<?php echo $item_ID?>" name="item_ID">
                        <div class="block quantity">
                            <input type="number"  class="form-control" id="cart_quantity" value="1" min="0" max="5" placeholder="Enter email" name="cart_quantity">
                        </div>
                        <div class="spacer"></div>
                        <div class="block">
                            <button class="shadow btn custom-btn" name="add">Add to cart</button>
                        </div>
                    </form>
                </div>




            </div>

            <div class="product-details my-4">
                <p class="details-title text-color mb-1">Product Details</p>
                <p class="description"><?php echo $description?></p>
            </div>

            <div class="row questions bg-light p-3">
                <div class="col-md-1 icon">
                    <i class="fa-brands fa-rocketchat questions-icon"></i>
                </div>
                <div class="col-md-11 text">
                    Have a question about our products at E-Store? Feel free to contact our representatives via email.
                </div>
            </div>

            <div class="delivery my-4">
                <p class="font-weight-bold mb-0"><span><i class="fa-solid fa-truck"></i></span> <b>Product will not actually be delivered this is only a demo site!</b> </p>
                <p class="text-secondary">Order will not be sent.</p>
            </div>


        </div>
    </div>
</div>



<!--<div class="container similar-products my-4">-->
<!--    <hr>-->
<!--    <p class="display-5">Similar Products</p>-->
<!---->
<!--    <div class="row">-->
<!--        <div class="col-md-3">-->
<!--            <div class="similar-product">-->
<!--                <img class="w-100" src="https://source.unsplash.com/gsKdPcIyeGg" alt="Preview">-->
<!--                <p class="title">Lovely black dress</p>-->
<!--                <p class="price">$100</p>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="col-md-3">-->
<!--            <div class="similar-product">-->
<!--                <img class="w-100" src="https://source.unsplash.com/sg_gRhbYXhc" alt="Preview">-->
<!--                <p class="title">Lovely Dress with patterns</p>-->
<!--                <p class="price">$85</p>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="col-md-3">-->
<!--            <div class="similar-product">-->
<!--                <img class="w-100" src="https://source.unsplash.com/gJZQcirK8aw" alt="Preview">-->
<!--                <p class="title">Lovely fashion dress</p>-->
<!--                <p class="price">$200</p>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="col-md-3">-->
<!--            <div class="similar-product">-->
<!--                <img class="w-100" src="https://source.unsplash.com/qbB_Z2pXLEU" alt="Preview">-->
<!--                <p class="title">Lovely red dress</p>-->
<!--                <p class="price">$120</p>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
</div>

<script>
    const img = document.getElementById('photo');
    const field2 = document.getElementById('field2');
    const field3 = document.getElementById('field3');
    const field4 = document.getElementById('field4');

    function view2() {
        let tempSrc = img.src;
        img.src = field2.src;
        field2.src = tempSrc;
    }

    function view3() {
        let tempSrc = img.src;
        img.src = field3.src;
        field3.src = tempSrc;
    }

    function view4() {
        let tempSrc = img.src;
        img.src = field4.src;
        field4.src = tempSrc;
    }
</script>



<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
