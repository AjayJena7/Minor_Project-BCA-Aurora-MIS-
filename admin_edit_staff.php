<?php
session_start();
require_once "./functions/admin.php";
$title = "Edit Staff Member";
require "./template/header.php";
require "./functions/database_functions.php";
$conn = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $staff_id = mysqli_real_escape_string($conn, $_GET['id']);

    $query = "SELECT * FROM staff WHERE staff_id = '$staff_id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
} else {
    header("Location: admin_staff.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $staff_name = trim($_POST['staff_name']);
    $staff_name = mysqli_real_escape_string($conn, $staff_name);

    $staff_position = trim($_POST['staff_position']);
    $staff_position = mysqli_real_escape_string($conn, $staff_position);

    // add image
    if (isset($_FILES['staff_image']) && $_FILES['staff_image']['name'] != "") {
        $image = $_FILES['staff_image']['name'];
        $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
        $uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . "bootstrap/img/";
        $uploadDirectory .= $image;
        move_uploaded_file($_FILES['staff_image']['tmp_name'], $uploadDirectory);
    } else {
        $image = $row['staff_image']; // Keep the existing image if no new image is selected
    }

    $query = "UPDATE staff SET `staff_name`='$staff_name', `staff_position`='$staff_position', `staff_image`='$image' WHERE `staff_id`='$staff_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $_SESSION['staff_success'] = "Staff member has been updated successfully";
        header("Location: admin_staff.php");
        exit;
    } else {
        $err = "Can't update data " . mysqli_error($conn);
    }
}
?>

<div class="container-fluid">
    <h5 class="fw-bolder text-center">Edit Staff Member</h5>
    <hr class="bg-warning" style="width:5em;height:3px;opacity:1">

    <form method="post" action="admin_edit_staff.php?id=<?= $staff_id; ?>" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="control-label">Staff Name</label>
            <input class="form-control rounded-0" type="text" name="staff_name" value="<?= $row['staff_name']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="control-label">Staff Position</label>
            <input class="form-control rounded-0" type="text" name="staff_position" value="<?= $row['staff_position']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="control-label">Current Staff Image</label>
            <img src="bootstrap/img/<?= $row['staff_image']; ?>" alt="<?= $row['staff_name']; ?>" style="max-width: 100px; max-height: 100px;">
        </div>
        <div class="mb-3">
            <label class="control-label">New Staff Image</label>
            <input class="form-control rounded-0" type="file" name="staff_image">
        </div>
        <div class="text-center">
            <button type="submit" name="update" class="btn btn-primary btn-sm rounded-0">Update</button>
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
