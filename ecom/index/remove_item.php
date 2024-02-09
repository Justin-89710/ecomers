<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $cartKeyToRemove = $_POST['cart_key'];

    // Find and remove the item from the cart based on the array key
    if (isset($_SESSION['cart'][$cartKeyToRemove])) {
        unset($_SESSION['cart'][$cartKeyToRemove]);
    }
}

// Redirect back to the page displaying the cart
header("Location: winkelmand.php");
exit();
?>