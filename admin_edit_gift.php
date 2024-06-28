<?php
session_start();
require_once "./functions/admin.php";
$title = "Edit Gift";
require "./template/header.php";
require "./functions/database_functions.php";
$conn = db_connect();

if (isset($_GET['id'])) {
    $gift_id = $_GET['id'];

    // Fetch gift details from the database based on the ID
    $query = "SELECT * FROM gifts WHERE gift_id = ?"; // Use prepared statement
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $gift_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $gift = mysqli_fetch_assoc($result);

    if (!$gift) {
        echo "Gift not found!";
        exit;
    }
} else {
    echo "Invalid request!";
    exit;
}

if (isset($_POST['update'])) {
    // Assuming you have form fields like gift_name, gift_description, gift_price, etc.
    $gift_name = mysqli_real_escape_string($conn, $_POST['gift_name']);
    $gift_description = mysqli_real_escape_string($conn, $_POST['gift_description']);
    $gift_price = mysqli_real_escape_string($conn, $_POST['gift_price']);
    // Add more fields as needed

    // Assuming you have a SQL query to update the gift in the database
    $update_query = "UPDATE gifts SET gift_name = ?, gift_description = ?, gift_price = ? WHERE gift_id = ?";
    
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssii", $gift_name, $gift_description, $gift_price, $gift_id);
    
    $update_result = mysqli_stmt_execute($stmt);
    
    if (!$update_result) {
        echo "Error updating gift: " . mysqli_error($conn);
    } else {
        // Redirect to the admin_gift.php page after updating
        header("Location: admin_gift.php?update=success");
        exit;
    }
}
?>

<!-- The rest of your HTML code remains unchanged -->
