<?php
session_start();
require_once "./functions/admin.php";
$title = "Admin Staff";
require "./template/header.php";
require "./functions/database_functions.php";
$conn = db_connect();

// Delete Staff
if (isset($_GET['delete']) && $_GET['delete'] == 'success') {
    $_SESSION['staff_success'] = "Staff member has been deleted successfully";
    header("Location: admin_staff.php");
    exit;
}

// Update Staff
if (isset($_GET['update']) && $_GET['update'] == 'success') {
    $_SESSION['staff_success'] = "Staff member has been updated successfully";
    header("Location: admin_staff.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $staff_id = trim($_POST['staff_id']);
    $staff_id = mysqli_real_escape_string($conn, $staff_id);

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
        // Set a default image if no new image is selected
        $image = "default_image.jpg";
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
    <?php if (isset($_SESSION['staff_success'])) : ?>
        <div class="alert alert-success">
            <?= $_SESSION['staff_success']; ?>
        </div>
        <?php unset($_SESSION['staff_success']); ?>
    <?php endif; ?>

    <!-- Add New Staff Section -->
    <div class="mb-4">
        <h5 class="fw-bolder">Add New Staff Member</h5>
        <form method="post" action="admin_staff.php" enctype="multipart/form-data">
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
                <button type="submit" name="add" class="btn btn-primary btn-sm rounded-0">Save</button>
                <a href="admin_staff.php" class="btn btn-default btn-sm rounded-0 border">Cancel</a>
            </div>
        </form>
    </div>

    <hr class="bg-warning" style="width:5em;height:3px;opacity:1">

    <h5 class="fw-bolder text-center">Staff List</h5>

    <table class="table table-bordered">
        <!-- Table Header -->
        <thead>
            <tr>
                <th scope="col">Staff ID</th>
                <th scope="col">Staff Name</th>
                <th scope="col">Staff Position</th>
                <th scope="col">Staff Image</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch the staff data from the database
            $query = "SELECT * FROM staff";
            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $row['staff_id']; ?></td>
                    <td><?= $row['staff_name']; ?></td>
                    <td><?= $row['staff_position']; ?></td>
                    <td><img src="bootstrap/img/<?= $row['staff_image']; ?>" alt="<?= $row['staff_name']; ?>" style="max-width: 100px; max-height: 100px;"></td>
                    <td>
                        <a href="admin_edit_staff.php?id=<?= $row['staff_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="admin_delete_staff.php?id=<?= $row['staff_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
if (isset($conn)) {
    mysqli_close($conn);
}
require_once "./template/footer.php";
?>
