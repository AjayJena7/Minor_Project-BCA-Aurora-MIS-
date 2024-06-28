<?php
  session_start();
  $book_isbn = $_GET['bookisbn'];
  // connect to database
  require_once "./functions/database_functions.php";
  $conn = db_connect();

  $query = "SELECT * FROM books WHERE book_isbn = '$book_isbn'";
  $result = mysqli_query($conn, $query);
  if(!$result){
    echo "Can't retrieve data " . mysqli_error($conn);
    exit;
  }

  $row = mysqli_fetch_assoc($result);
  if(!$row){
    echo "Empty book";
    exit;
  }

  $title = $row['book_title'];
  require "./template/header.php";
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="books.php" class="text-decoration-none text-muted fw-light">Publishers</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $row['book_title']; ?></li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-3 text-center book-item">
        <div class="img-holder overflow-hidden">
            <img class="img-top" src="./bootstrap/img/<?php echo $row['book_image']; ?>">
        </div>
    </div>
    <div class="col-md-9">
        <div class="card rounded-0 shadow">
            <div class="card-body">
                <div class="container-fluid">
                    <h4><?= $row['book_title'] ?></h4>
                    <hr>
                    <p><?php echo $row['book_descr']; ?></p>
                    <h4>Details</h4>
                    <table class="table">
                        <?php foreach($row as $key => $value){
                            if($key == "book_descr" || $key == "book_image" || $key == "publisherid" || $key == "book_title"){
                                continue;
                            }
                            switch($key){
                                case "book_isbn":
                                    $key = "ISBN";
                                    break;
                                case "book_title":
                                    $key = "Title";
                                    break;
                                case "book_author":
                                    $key = "Author";
                                    break;
                                case "book_price":
                                    $key = "Price";
                                    break;
                            }
                        ?>
                        <tr>
                            <td><?php echo $key; ?></td>
                            <td><?php echo $value; ?></td>
                        </tr>
                        <?php } 
                        if(isset($conn)) {mysqli_close($conn); }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require "./template/footer.php";
?>