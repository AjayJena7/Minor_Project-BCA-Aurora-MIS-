<?php
session_start();
require_once "./functions/admin.php";
$title = "Add New Gift";
require "./template/header.php";
require "./functions/database_functions.php";
$conn = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    // Assuming you don't manually provide the gift_id

    $gift_name = trim($_POST['gift_name']);
    $gift_name = mysqli_real_escape_string($conn, $gift_name);

    $gift_description = trim($_POST['gift_description']);
    $gift_description = mysqli_real_escape_string($conn, $gift_description);

    $gift_price = floatval(trim($_POST['gift_price']));
    $gift_price = mysqli_real_escape_string($conn, $gift_price);

    $gift_category = trim($_POST['gift_category']);
    $gift_category = mysqli_real_escape_string($conn, $gift_category);

    // Add image
    if (isset($_FILES['gift_image']) && $_FILES['gift_image']['name'] != "") {
        $image = $_FILES['gift_image']['name'];
        $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
        $uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . "bootstrap/img/";
        $uploadDirectory .= $image;
        move_uploaded_file($_FILES['gift_image']['tmp_name'], $uploadDirectory);
    }

    $query = "INSERT INTO gifts (`gift_name`, `gift_description`, `gift_price`, `gift_category`, `gift_image`) VALUES ('$gift_name', '$gift_description', '$gift_price', '$gift_category', '$image')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $_SESSION['gift_success'] = "New Gift has been added successfully";
        header("Location: admin_gift.php");
        exit;
    } else {
        $err =  "Can't add new data " . mysqli_error($conn);
    }
}
?>

<h4 class="fw-bolder text-center">Add New Gift</h4>
<center>
    <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
</center>

<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
        <div class="card rounded-0 shadow">
            <div class="card-body">
                <div class="container-fluid">
                    <?php if (isset($err)) : ?>
                        <div class="alert alert-danger rounded-0">
                            <?= $_SESSION['err_login'] ?>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="admin_add_gift.php" enctype="multipart/form-data">
                        <!-- Do not include an input for the gift_id -->

                        <div class="mb-3">
                            <label class="control-label">Gift Name</label>
                            <input class="form-control rounded-0" type="text" name="gift_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="control-label">Gift Description</label>
                            <textarea class="form-control rounded-0" name="gift_description" cols="40" rows="5"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="control-label">Gift Price</label>
                            <input class="form-control rounded-0" type="text" name="gift_price" required>
                        </div>
                        <div class="mb-3">
                            <label class="control-label">Gift Category</label>
                            <input class="form-control rounded-0" type="text" name="gift_category" required>
                        </div>
                        <div class="mb-3">
                            <label class="control-label">Gift Image</label>
                            <input class="form-control rounded-0" type="file" name="gift_image">
                        </div>
                        <div class="text-center">
                            <button type="submit" name="add" class="btn btn-primary btn-sm rounded-0">Save</button>
                            <a href="admin_gift.php" class="btn btn-default btn-sm rounded-0 border">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($conn)) {
    mysqli_close($conn);
}
require_once "./template/footer.php";
?>
Ensure that the `gift_id` column in your database is set as an auto-increment primary key. If you manually provide a value for an auto-increment column, it may lead to unexpected behavior. If you want to retrieve the auto-incremented `gift_id` after insertion, you can use `mysqli_insert_id($conn)`.
