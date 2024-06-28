<?php
session_start();
require_once "./functions/admin.php";
$title = "Delete Gift";
require "./template/header.php";
require "./functions/database_functions.php";
$conn = db_connect();

if (isset($_GET['id'])) {
    $gift_id = $_GET['id'];

    // Fetch gift details from the database based on the ID
    $query = "SELECT * FROM gifts WHERE gift_id = '$gift_id'";
    $result = mysqli_query($conn, $query);
    $gift = mysqli_fetch_assoc($result);

    if (!$gift) {
        echo "Gift not found!";
        exit;
    }
} else {
    echo "Invalid request!";
    exit;
}

if (isset($_POST['delete'])) {
    // Code to delete associated reviews (Option 1)
    $delete_reviews_query = "DELETE FROM reviews WHERE gift_id = '$gift_id'";
    $delete_reviews_result = mysqli_query($conn, $delete_reviews_query);

    if (!$delete_reviews_result) {
        echo "Error deleting reviews: " . mysqli_error($conn);
    }

    // Code to delete the gift
    $delete_query = "DELETE FROM gifts WHERE gift_id = '$gift_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    if (!$delete_result) {
        echo "Error deleting gift: " . mysqli_error($conn);
    } else {
        // Redirect to the admin_gift.php page after deleting
        header("Location: admin_gift.php");
        exit;
    }
}
?>

<div class="container">
    <!-- Your HTML code remains unchanged -->
</div>

<?php
if (isset($conn)) {
    mysqli_close($conn);
}
require_once "./template/footer.php";
?>
