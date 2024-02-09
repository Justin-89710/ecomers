<?php
// show server errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// start session
session_start();

// connect to database
$db = new sqlite3('../database/database.db');


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

if (empty($_SESSION['cart'])) {
    $cartinfo = "Your cart is empty";
} else {
    $cartItems = $_SESSION['cart'];

    foreach ($cartItems as $cartKey => $item) {

        $numbofitem = $item['numbofitem'];
        $itemid = $item['itemid'];

        // Fetch item details from the SQLite3 database
        $query = "SELECT title, price * :numbofitem as total_price, img1 FROM items WHERE ID = :itemid";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':itemid', $itemid, SQLITE3_INTEGER);
        $stmt->bindValue(':numbofitem', $numbofitem, SQLITE3_INTEGER);
        $result = $stmt->execute();

        if ($result) {
            $row = $result->fetchArray(SQLITE3_ASSOC);

            if ($row) {
                $title = $row['title'];
                $totalPrice = $row['total_price'];
                $img1 = $row['img1'];
            }
        } else {
            echo "Error executing the query<br>";
        }
    }
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/nav.css">
    <link rel="stylesheet" href="../CSS/winkelmand.css">
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
                // Display search results if available
                if ($searchresult && $searchresult->numColumns() > 0) {
                    echo '        <div class="overlay fixed-bottom" id="searchOverlay">
            <div class="container3 mt-3 mt-md-0">';
                    $counter = 0; // Counter to limit the number of items displayed
                    while ($row = $searchresult->fetchArray(SQLITE3_ASSOC)) {
                        if ($counter < 3) {
                            echo '<div class="item-info">';
                            echo '<a href="item.php?id=' . $row['ID'] . '">';
                            echo '<img src="../img/' . $row['img1'] . '" alt="' . $row['title'] . '" class="item-image">';
                            echo $row['title'];
                            echo '</a>';
                            echo '</div>';
                            $counter++;
                        } else {
                            break; // Break the loop once three items are displayed
                        }
                    }

                    // Display link to a page with all items
                    echo '<a href="all_items.php">View All Items</a>';
                    echo '<button id="closeSearchOverlayBtn" class="btn btn-danger">Close Search</button>';
                    echo '            </div>
        </div>';
                }
                ?>
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
<div class="col-sm-12 col-md-10 col-md-offset-1">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>Product</th>
            <th>Authorized</th>
            <th>Quantity</th>
            <th class="text-center">Price</th>
            <th class="text-center">Total</th>
            <th> </th>
        </tr>
        </thead>
        <tbody>

        <?php
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $cartKey => $item) {
                $numbofitem = $item['numbofitem'];
                $itemid = $item['itemid'];

        // Fetch item details from the SQLite3 database
        $query = "SELECT title, price * :numbofitem as total_price, img1, price FROM items WHERE ID = :itemid";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':itemid', $itemid, SQLITE3_INTEGER);
        $stmt->bindValue(':numbofitem', $numbofitem, SQLITE3_INTEGER);
        $result = $stmt->execute();

        if ($result) {
        $row = $result->fetchArray(SQLITE3_ASSOC);

        if ($row) {
            $title = $row['title'];
            $totalPrice = $row['total_price'];
            $itemprice = $row['price'];
            $img1 = $row['img1'];
        }

                // For demonstration purposes, I'll use placeholder values
                $itemTitle = $title;
                $itemPrice = $itemprice; // Placeholder value
                $itemTotal = $totalPrice;
                $itemImgSrc = "../img/$img1";

                // Output HTML for each item
                ?>
            <form method="post" class="cart-item-form">
                <input type="hidden" name="cart_key" value="<?= $cartKey ?>">
                <tr>
                <td class="col-sm-8 col-md-6">
                    <div class="media">
                        <a class="thumbnail pull-left" href="#"> <img class="media-object" src="<?= $itemImgSrc ?>" style="width: 72px; height: 72px;"> </a>
                        <div class="media-body">
                            <h4 class="media-heading"><a style="color: #1a1a1a;" href="item.php?id=<?php echo $itemid;?>"><?= $itemTitle ?></a></h4>
                        </div>
                    </div>
                </td>
                <td class="col-md-1 text-left"><strong class="label label-danger">None</strong></td>
                <td class="col-sm-1 col-md-1" style="text-align: center">
                    <input type="number" class="form-control quantity-input" name="quantity" value="<?= $numbofitem ?>" data-cart-key="<?= $cartKey ?>">
                </td>
                <td class="col-sm-1 col-md-1 text-center"><strong>$<?= $itemPrice ?></strong></td>
                <td class="col-sm-1 col-md-1 text-center"><strong class="item-total">$<?= $itemTotal ?></strong></td>
            </form>
                    <td class="col-sm-1 col-md-1">
                        <form action="remove_item.php" method="post">
                            <input type="hidden" name="cart_key" value="<?= $cartKey ?>">
                            <button type="submit" class="btn btn-danger remove-item-btn" name="remove_item">
                                <span class="fa fa-remove"></span> Remove
                            </button>
                        </form>
                    </td>
                </tr>

            <?php
        }
        else {
            // Cart is empty
            echo '<tr><td colspan="6">Your cart is empty</td></tr>';
        }
        }
        }
        ?>
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var quantityInputs = document.querySelectorAll('.quantity-input');

        quantityInputs.forEach(function (input) {
            input.addEventListener('input', function () {
                updateItemTotal(this);
            });

            input.addEventListener('keyup', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    updateItemTotal(this);
                    submitForm(this);
                }
            });
        });

        function updateItemTotal(input) {
            var quantity = parseInt(input.value);
            var cartKey = input.getAttribute('data-cart-key');
            console.log('cartKey:', cartKey);

            var row = input.closest('tr');
            var targetElement = row.querySelector('.item-total');
            console.log('targetElement:', targetElement);

            if (targetElement !== null) {
                var itemPrice = parseFloat(input.parentNode.nextElementSibling.textContent.replace('$', ''));
                var newTotal = (quantity * itemPrice).toFixed(2);
                targetElement.textContent = '$' + newTotal;

                // Pass the quantity to the updateSession function
                updateSession(cartKey, quantity);
            } else {
                console.error('Target element not found.');
            }
        }

        function updateSession(cartKey, newQuantity) {
            $.ajax({
                type: 'POST',
                url: 'update_session.php', // Replace with your server-side script
                data: { cartKey: cartKey, numbofitem: newQuantity }, // Change to 'numbofitem'
                success: function(response) {
                    console.log('Session updated successfully:', response);
                },
                error: function(error) {
                    console.error('Error updating session:', error);
                }
            });
        }

        function submitForm(element) {
            var cartKey = element.getAttribute('data-cart-key');
            document.querySelector('.cart-item-form input[name="cart_key"]').value = cartKey;
            document.querySelector('.cart-item-form').submit();
        }
    });
</script>
<!--    <script src="../JS/nav.js"></script>-->
</body>
</html>