<?php
// show server errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// start session
session_start();

$db = new sqlite3('../database/database.db');
// Include or initialize your database connection code here

// Fetch all items from the database
$query = "SELECT * FROM items"; // Update with your actual table name
$result = $db->query($query); // Update with your actual database connection variable

// Display all items
if ($result && $result->numColumns() > 0) {
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        echo '<div class="item-info">';
        echo '<a href="item.php?id=' . $row['ID'] . '">';
        echo '<img src="../img/' . $row['img1'] . '" alt="' . $row['title'] . '" class="item-image">';
        echo $row['title'];
        echo '</a>';
        echo '</div>';
    }
} else {
    echo 'No items found.';
}

// Close your database connection if necessary
// $your_database_connection->close(); // Uncomment and update as needed
?>