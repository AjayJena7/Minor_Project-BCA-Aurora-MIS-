<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./bootstrap/css/styles.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="./bootstrap/js/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="./bootstrap/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="clear-fix pt-5 pb-3"></div>
    <nav class="navbar navbar-expand-lg navbar-expand-md navbar-light bg-warning bg-gradient fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav" aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="index.php">Aurora Book & Gift house</a>
            </div>

            <div class="collapse navbar-collapse" id="topNav">
                <ul class="nav navbar-nav">
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == true): ?>
                        <li class="nav-item"><a class="nav-link" href="admin_book.php"><span class="fa fa-th-list"></span> Book List</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin_add.php"><span class="far fa-plus-square"></span> Add New Book</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin_signout.php"><span class="fa fa-sign-out-alt"></span> Logout</a></li>
                        <li class="nav-item"><a class="nav-link" href="bill1.php"><span class="fas fa-print"></span> Print Bill</a></li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin_gift.php">
                                <span class="fa fa-gift"></span> Gifts
                            </a>
                        </li>
                        <!-- New Staff Navigation Item -->
                        <li class="nav-item">
                            <a class="nav-link" href="admin_staff.php">
                                <span class="fas fa-users"></span> Staff
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="publisher_list.php"><span class="fa fa-paperclip"></span> Publisher</a></li>
                        <li class="nav-item"><a class="nav-link" href="books.php"><span class="fa fa-book"></span> Books</a></li>
                        <li class="nav-item">
                            <a class="nav-link" href="gifts.php">
                                <span class="fa fa-gift"></span> Gifts
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <?php
    // Check if the current page is the "Gifts" page
    $isGiftsPage = isset($title) && $title == "Gifts" && isset($gifts) && !empty($gifts);
    ?>

    <?php if ($isGiftsPage): ?>
        <!-- Display only the first gift on the Gifts page -->
        <div class="container">
            <h1>Welcome to Aurora Gift Store</h1>
            <hr>
            <div class="lead text-center text-dark fw-bolder h4 mt-5">Featured Gift</div>
            <center>
                <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
            </center>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 py-2 mb-2">
                    <div class="card rounded-0 shadow book-item text-reset text-decoration-none">
                        <div class="img-holder overflow-hidden">
                            <img class="img-top" src="./bootstrap/img/<?php echo $gifts[0]['gift_image']; ?>">
                        </div>
                        <div class="card-body">
                            <div class="card-title fw-bolder h5 text-center"><?= $gifts[0]['gift_name'] ?></div>
                            <p class="card-text"><?= $gifts[0]['gift_description'] ?></p>
                            <p class="card-text">Price: $<?= $gifts[0]['gift_price'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- (existing body content) -->

    <!-- (existing footer code) -->

</body>

</html>
