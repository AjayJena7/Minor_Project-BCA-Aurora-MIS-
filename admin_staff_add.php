<?php
session_start();
require_once "./functions/admin.php";
$title = "Add Staff Member";
require "./template/header.php";
require "./functions/database_functions.php";
$conn = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $staff_id = trim($_POST['staff_id']);
    $staff_id = mysqli_real_escape_string($conn, $staff_id);

    $staff_name = trim($_POST['staff_name']);
    $staff_name = mysqli_real_escape_string($conn, $staff_name);

    $staff_position = trim($_POST['staff_position']);
    $staff_position = mysqli_real_escape_string($conn, $staff_position);

    // add image
    $image = "default_image.jpg"; // Set a default image

    if (isset($_FILES['staff_image']) && $_FILES['staff_image']['name'] != "") {
        $image = $_FILES['staff_image']['name'];
        $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
        $uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . "bootstrap/img/";
        $uploadDirectory .= $image;
        move_uploaded_file($_FILES['staff_image']['tmp_name'], $uploadDirectory);
    }

    $query = "INSERT INTO staff (`staff_id`, `staff_name`, `staff_position`, `staff_image`) VALUES ('$staff_id', '$staff_name', '$staff_position', '$image')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $_SESSION['staff_success'] = "New Staff member has been added successfully";
        header("Location: admin_staff.php");
        exit;
    } else {
        $err = "Can't add new data " . mysqli_error($conn);
    }
}
?>

<div class="container-fluid">
    <h5 class="fw-bolder text-center">Add Staff Member</h5>
    <hr class="bg-warning" style="width:5em;height:3px;opacity:1">

    <form method="post" action="admin_staff_add.php" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="control-label">Staff ID</label>
            <input class="form-control rounded-0" type="text" name="staff_id" required>
        </div>
        <div class="mb-3">
            <label class="control-label">Staff Name</label>
            <input class="form-control rounded-0" type="text" name="staff_name" required>
        </div>
        <div class="mb-3">
            <label class="control-label">Staff Position</label>
            <input class="form-control rounded-0" type="text" name="staff_position" required>
        </div>
        <div class="mb-3">
            <label class="control-label">Staff Image</label>
            <input class="form-control rounded-0" type="file" name="staff_image">
        </div>
        <div class="text-center">
            <button type="submit" name="add" class="btn btn-primary btn-sm rounded-0">Add Staff</button>
            <a href="admin_staff.php" class="btn btn-default btn-sm rounded-0 border">Cancel</a>
        </div>
    </form>
</div>

<?php
if (isset($conn)) {
    mysqli_close($conn);
}
require_once "./template/footer.php";
?>
