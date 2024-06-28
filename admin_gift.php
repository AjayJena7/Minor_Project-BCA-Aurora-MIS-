<?php
session_start();
require_once "./functions/admin.php";
$title = "Admin Gifts";
require "./template/header.php";
require "./functions/database_functions.php";
$conn = db_connect();

// Delete Gift
if (isset($_GET['delete']) && $_GET['delete'] == 'success') {
    $_SESSION['gift_success'] = "Gift has been deleted successfully";
    header("Location: admin_gift.php");
    exit;
}

// Update Gift
if (isset($_GET['update']) && $_GET['update'] == 'success') {
    $_SESSION['gift_success'] = "Gift has been updated successfully";
    header("Location: admin_gift.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $gift_name = trim($_POST['gift_name']);
    $gift_name = mysqli_real_escape_string($conn, $gift_name);

    $gift_description = trim($_POST['gift_description']);
    $gift_description = mysqli_real_escape_string($conn, $gift_description);

    $gift_price = floatval(trim($_POST['gift_price']));
    $gift_price = mysqli_real_escape_string($conn, $gift_price);

    $gift_category = trim($_POST['gift_category']);
    $gift_category = mysqli_real_escape_string($conn, $gift_category);

    // add image
    if (isset($_FILES['gift_image']) && $_FILES['gift_image']['name'] != "") {
        $image = $_FILES['gift_image']['name'];
        $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
        $uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . "bootstrap/img/";
        $uploadDirectory .= $image;
        move_uploaded_file($_FILES['gift_image']['tmp_name'], $uploadDirectory);
    }

    $query = "INSERT INTO gifts (`gift_name`, `gift_description`, `gift_price`, `gift_category`, `gift_image`) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssdss", $gift_name, $gift_description, $gift_price, $gift_category, $image);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $_SESSION['gift_success'] = "New Gift has been added successfully";
        header("Location: admin_gift.php");
        exit;
    } else {
        $err =  "Can't add new data " . mysqli_error($conn);
    }
}
?>

<!-- The rest of your HTML code remains unchanged -->
