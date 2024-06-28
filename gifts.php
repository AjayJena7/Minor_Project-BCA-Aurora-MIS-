<?php
session_start();
$count = 0;
require_once "./functions/database_functions.php";
$conn = db_connect();

$query = "SELECT gift_id, gift_name, gift_image FROM gifts";
$result = mysqli_query($conn, $query);

$title = "Gifts";
require_once "./template/header.php";
?>

<?php if (isset($title) && $title == "Gifts"): ?>
    <div class="lead text-center text-dark fw-bolder h4 mt-5">All Kinds Of Gifts</div>
    <center>
        <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
    </center>
    <div class="container">
        <div class="row">
            <?php while ($gift = mysqli_fetch_assoc($result)) : ?>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 py-2 mb-2">
                    <div class="card rounded-0 shadow book-item text-reset text-decoration-none">
                        <a href="gift_details.php?id=<?= $gift['gift_id']; ?>" class="text-reset text-decoration-none">
                            <div class="img-holder overflow-hidden">
                                <img class="img-top" src="./bootstrap/img/<?php echo $gift['gift_image']; ?>">
                            </div>
                            <div class="card-body">
                                <div class="card-title fw-bolder h5 text-center"><?= $gift['gift_name'] ?></div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
<?php endif; ?>

<?php
if (isset($conn)) {
    mysqli_close($conn);
}
require_once "./template/footer.php";
?>
