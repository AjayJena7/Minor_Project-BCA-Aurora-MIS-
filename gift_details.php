<?php
session_start();
require_once "./functions/database_functions.php";
$conn = db_connect();

$title = "Gift Details";

if (isset($_GET['id'])) {
    $gift_id = $_GET['id'];

    $query = "SELECT * FROM gifts WHERE gift_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $gift_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $gift = mysqli_fetch_assoc($result);

    if (!$gift) {
        echo "Gift not found!";
        exit;
    }

    require_once "./template/header.php";
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <!-- Display the gift image with zoom-in effect -->
            <div class="img-holder overflow-hidden position-relative">
                <img class="img-fluid rounded img-top" src="./bootstrap/img/<?php echo $gift['gift_image']; ?>" alt="<?= $gift['gift_name'] ?>" style="transition: transform 0.2s;">
            </div>
        </div>
        <div class="col-md-6">
            <h2 class="mb-4"><?= $gift['gift_name'] ?></h2>
            <p class="lead"><?= $gift['gift_description'] ?></p>
            <hr class="my-4">
            <p class="text-muted"><strong>Category:</strong> <?= $gift['gift_category'] ?></p>
            <p class="mb-4"><strong>Price:</strong> ₹<?= $gift['gift_price'] ?></p>

            <!-- Customer Review Section -->
            <div class="mb-4">
                <h4>Customer Reviews</h4>

                <!-- Display star ratings dynamically based on average rating -->
                <div class="rating" id="starsContainer">
                    <?php
                    $averageRating = calculateAverageRating($conn, $gift_id);
                    $roundedAverageRating = round($averageRating);
                    for ($i = 1; $i <= 5; $i++) {
                        echo '<span class="star ' . ($i <= $roundedAverageRating ? 'filled' : '') . '">&#9733;</span>';
                    }
                    ?>
                </div>
                <p class="text-muted">Average Rating: <?= number_format($averageRating, 1) ?></p>

                <!-- Button to show the review form -->
                <button class="btn btn-primary btn-sm" onclick="showReviewForm()">Write a Review</button>

                <!-- Form to submit reviews (hidden by default) -->
                <div id="reviewForm" style="display: none;">
                    <hr>
                    <h5>Write Your Review</h5>
                    <form method="post" action="gift_details.php?id=<?= $gift_id ?>">
                        <div class="mb-3">
                            <label for="reviewerName" class="form-label">Your Name:</label>
                            <input type="text" class="form-control" name="reviewer_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="reviewRating" class="form-label">Rating:</label>
                            <select class="form-select" name="rating" required>
                                <option value="5">5 stars</option>
                                <option value="4">4 stars</option>
                                <option value="3">3 stars</option>
                                <option value="2">2 stars</option>
                                <option value="1">1 star</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="reviewComment" class="form-label">Comment:</label>
                            <textarea class="form-control" name="comment" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm" name="submit_review">Submit Review</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="cancelReview()">Cancel</button>
                    </form>
                </div>

                <!-- Button to show the customer reviews -->
                <button class="btn btn-primary btn-sm" onclick="showCustomerReviews()">Show Customer Reviews</button>

                <!-- Customer reviews section (hidden by default) -->
                <div id="customerReviewsSection" style="display: none;">
                    <hr>
                    <h5>Customer Reviews</h5>
                    <?php
                    $reviews_query = "SELECT * FROM reviews WHERE gift_id = ?";
                    $stmt = mysqli_prepare($conn, $reviews_query);
                    mysqli_stmt_bind_param($stmt, "i", $gift_id);
                    mysqli_stmt_execute($stmt);
                    $reviews_result = mysqli_stmt_get_result($stmt);

                    while ($review = mysqli_fetch_assoc($reviews_result)) {
                        echo '<div class="mb-3">';
                        echo '<strong>' . htmlspecialchars($review['reviewer_name']) . ':</strong> ';
                        echo '<span class="text-muted">Rating: ' . $review['rating'] . ' stars</span><br>';
                        echo htmlspecialchars($review['comment']);
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .img-holder:hover img {
        transform: scale(1.1);
    }

    .rating {
        color: #ffd700; /* Set star color to gold */
    }

    .filled {
        color: #ffd700; /* Set filled star color to gold */
    }
</style>

<script>
    function showReviewForm() {
        document.getElementById("reviewForm").style.display = "block";
    }

    function cancelReview() {
        // Reset the form without submitting
        document.getElementById("reviewForm").style.display = "none";
    }

    function showCustomerReviews() {
        var customerReviewsSection = document.getElementById("customerReviewsSection");
        customerReviewsSection.style.display = customerReviewsSection.style.display === "none" ? "block" : "none";
    }

    function updateStars(averageRating) {
        var roundedAverageRating = Math.min(Math.max(Math.round(averageRating), 0), 5);
        var starsContainer = document.getElementById("starsContainer");
        starsContainer.innerHTML = '';

        for (var i = 1; i <= 5; i++) {
            var star = document.createElement('span');
            star.className = 'star ' + (i <= roundedAverageRating ? 'filled' : '');
            star.innerHTML = '&#9733;';
            starsContainer.appendChild(star);
        }
    }

    // Initial update
    updateStars(<?php echo $averageRating; ?>);
</script>

<?php
    require_once "./template/footer.php";
} else {
    echo "Invalid request!";
    exit;
}

// Handle the submission of a new review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $reviewer_name = $_POST['reviewer_name'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Insert the review into the database
    $insert_query = "INSERT INTO reviews (reviewer_name, rating, comment, gift_id) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "sisi", $reviewer_name, $rating, $comment, $gift_id);
    mysqli_stmt_execute($stmt);

    // Update stars dynamically after a new review
    $newAverageRating = calculateAverageRating($conn, $gift_id);
    echo '<script>updateStars(' . $newAverageRating . ')</script>';
}

if (isset($conn)) {
    mysqli_close($conn);
}

// Function to calculate average rating
function calculateAverageRating($conn, $gift_id) {
    $average_query = "SELECT AVG(rating) AS avg_rating FROM reviews WHERE gift_id = ?";
    $stmt = mysqli_prepare($conn, $average_query);
    mysqli_stmt_bind_param($stmt, "i", $gift_id);
    mysqli_stmt_execute($stmt);
    $average_result = mysqli_stmt_get_result($stmt);
    $average_rating = mysqli_fetch_assoc($average_result)['avg_rating'];

    return $average_rating;
}
?>
