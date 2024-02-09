<?php
session_start();

if (isset($_POST['cartKey']) && isset($_POST['numbofitem'])) {
    $cartKey = $_POST['cartKey'];
    $newNumbofitem = $_POST['numbofitem'];

    // Update the session with the new numbofitem
    $_SESSION['cart'][$cartKey]['numbofitem'] = $newNumbofitem;

    echo 'Session updated successfully';
} else {
    echo 'Invalid request';
}
?>