<?php
session_start();
$count = 0;
// connect to the database
require_once "./functions/database_functions.php";
$conn = db_connect();

// Fetch gifts data from the database
$query = "SELECT gift_id, gift_name, gift_description, gift_price, gift_image FROM gifts";
$result = mysqli_query($conn, $query);

$title = "List of Gifts";
require_once "./template/header.php";
?>

<p class="lead text-center text-muted">List of All Gifts</p>

<div class="container">
    <?php while ($gift = mysqli_fetch_assoc($result)) : ?>
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 py-2 mb-2">
                <div class="card rounded-0 shadow gift-item text-reset text-decoration-none" data-id="<?= $gift['gift_id']; ?>">
                    <div class="img-holder overflow-hidden">
                        <img class="img-top" src="./bootstrap/img/<?php echo $gift['gift_image']; ?>">
                    </div>
                    <div class="card-body">
                        <div class="card-title fw-bolder h5 text-center"><?= $gift['gift_name'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<!-- JavaScript to display gift details on click -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        $(".gift-item").click(function () {
            var giftId = $(this).data("id");

            // Redirect to the gift_details.php page with the gift ID
            window.location.href = "gift_details.php?id=" + giftId;
        });
    });
</script>

<?php
if (isset($conn)) {
    mysqli_close($conn);
}
require_once "./template/footer.php";
?>
