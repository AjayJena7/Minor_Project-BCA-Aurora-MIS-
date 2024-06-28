<?php
session_start();
$count = 0;

$title = "Home";
require_once "./template/header.php";
require_once "./functions/database_functions.php";
$conn = db_connect();

// Fetching latest books
$row = select4LatestBook($conn);

// Fetching gifts
$gifts = getGifts($conn);
?>

<!-- Example row of columns for Latest Books -->
<div class="lead text-center text-dark fw-bolder h4">Latest Books</div>
<center>
    <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
</center>
<div class="row">
    <?php foreach ($row as $book) { ?>
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 py-2 mb-2">
            <a href="book.php?bookisbn=<?php echo $book['book_isbn']; ?>" class="card rounded-0 shadow book-item text-reset text-decoration-none">
                <div class="img-holder overflow-hidden">
                    <img class="img-top" src="./bootstrap/img/<?php echo $book['book_image']; ?>">
                </div>
                <div class="card-body">
                    <div class="card-title fw-bolder h5 text-center"><?= $book['book_title'] ?></div>
                </div>
            </a>
        </div>
    <?php } ?>
</div>

<!-- Example row of columns for Gifts -->
<div class="lead text-center text-dark fw-bolder h4 mt-5">Featured Gifts</div>
<center>
    <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
</center>
<div class="row">
    <?php foreach ($gifts as $gift) { ?>
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 py-2 mb-2">
            <div class="card rounded-0 shadow book-item">
                <div class="img-holder overflow-hidden position-relative">
                    <!-- Add a link to the detail page with the gift ID -->
                    <a href="gift_details.php?id=<?= $gift['gift_id']; ?>">
                        <img class="img-top img-fluid" src="./bootstrap/img/<?php echo $gift['gift_image']; ?>" style="transition: transform 0.2s;">
                    </a>
                </div>
                <div class="card-body">
                    <div class="card-title fw-bolder h5 text-center"><?= $gift['gift_name'] ?></div>
                    <!-- Remove the lines below to hide description and price -->
                    <!-- <p class="card-text"><?= $gift['gift_description'] ?></p> -->
                    <!-- <p class="card-text">Price: $<?= $gift['gift_price'] ?></p> -->
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<style>
    .img-holder:hover img {
        transform: scale(1.1);
    }
</style>

<?php
if (isset($conn)) {
    mysqli_close($conn);
}
require_once "./template/footer.php";
?>

<?php
function getGifts($conn)
{
    $gifts = array();

    $query = "SELECT * FROM gifts";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $gifts[] = $row;
        }
    }

    return $gifts;
}
?>
