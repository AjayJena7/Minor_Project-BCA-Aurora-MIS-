<?php
session_start();
require_once "./functions/admin.php";
require "./functions/database_functions.php";
$conn = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $staff_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Delete the staff member
    $query = "DELETE FROM staff WHERE staff_id = '$staff_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $_SESSION['staff_success'] = "Staff member has been deleted successfully";
        header("Location: admin_staff.php");
        exit;
    } else {
        $err = "Can't delete data " . mysqli_error($conn);
    }
} else {
    header("Location: admin_staff.php");
    exit;
}
?>
