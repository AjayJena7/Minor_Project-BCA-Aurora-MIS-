<?php
	session_start();
	require_once "./functions/admin.php";
	$title = "Add new book";
	require "./template/header.php";
	require "./functions/database_functions.php";
	$conn = db_connect();

	if(isset($_POST['add'])){
		$isbn = trim($_POST['isbn']);
		$isbn = mysqli_real_escape_string($conn, $isbn);
		
		$title = trim($_POST['title']);
		$title = mysqli_real_escape_string($conn, $title);

		$author = trim($_POST['author']);
		$author = mysqli_real_escape_string($conn, $author);
		
		$descr = trim($_POST['descr']);
		$descr = mysqli_real_escape_string($conn, $descr);
		
		$price = floatval(trim($_POST['price']));
		$price = mysqli_real_escape_string($conn, $price);
		
		$publisher = trim($_POST['publisher']);
		$publisherid = mysqli_real_escape_string($conn, $publisher);

		// add image
		if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
			$image = $_FILES['image']['name'];
			$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
			$uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . "bootstrap/img/";
			$uploadDirectory .= $image;
			move_uploaded_file($_FILES['image']['tmp_name'], $uploadDirectory);
		}

		$query = "INSERT INTO books (`book_isbn`, `book_title`, `book_author`, `book_image`, `book_descr`, `book_price`, `publisherid`) VALUES ('" . $isbn . "', '" . $title . "', '" . $author . "', '" . $image . "', '" . $descr . "', '" . $price . "', '" . $publisherid . "')";
		$result = mysqli_query($conn, $query);
		if($result){
			$_SESSION['book_success'] = "New Book has been added successfully";
			header("Location: admin_book.php");
		} else {
			$err =  "Can't add new data " . mysqli_error($conn);

		}
	}
?>
	<h4 class="fw-bolder text-center">Add New Book</h4>
	<center>
	<hr class="bg-warning" style="width:5em;height:3px;opacity:1">
	</center>
	<div class="row justify-content-center">
		<div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
			<div class="card rounded-0 shadow">
				<div class="card-body">
					<div class="container-fluid">
						<?php if(isset($err)): ?>
							<div class="alert alert-danger rounded-0">
								<?= $_SESSION['err_login'] ?>
							</div>
						<?php 
							endif;
						?>
						<form method="post" action="admin_add.php" enctype="multipart/form-data">
								<div class="mb-3">
									<label class="control-label">ISBN</label>
									<input class="form-control rounded-0" type="text" name="isbn">
								</div>
								<div class="mb-3">
									<label class="control-label">Title</label>
									<input class="form-control rounded-0" type="text" name="title" required>
								</div>
								<div class="mb-3">
									<label class="control-label">Author</label>
									<input class="form-control rounded-0" type="text" name="author" required>
								</div>
							
								<div class="mb-3">
									<label class="control-label">Image</label>
									<input class="form-control rounded-0" type="file" name="image">
								</div>
								<div class="mb-3">
									<label class="control-label">Description</label>
									<textarea class="form-control rounded-0" name="descr" cols="40" rows="5"></textarea>
								</div>
								<div class="mb-3">
									<label class="control-label">Price</label>
									<input class="form-control rounded-0" type="text" name="price" required>
								</div>
								<div class="mb-3">
									<label class="control-label">Publisher</label>
									<select class="form-select rounded-0"  name="publisher" required>
										<option value="" disabled selected>Please Select Here</option>
										<?php 
										$psql = mysqli_query($conn, "SELECT * FROM `publisher` order by publisher_name asc");
										while($row = mysqli_fetch_assoc($psql)):
										?>
										<option value="<?= $row['publisherid'] ?>"><?= $row['publisher_name'] ?></option>
										<?php endwhile; ?>
									</select>

								</div>
								<div class="text-center">
									<button type="submit" name="add"  class="btn btn-primary btn-sm rounded-0">Save</button>
									<button type="reset" class="btn btn-default btn-sm rounded-0 border">Cancel</button>
								</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	if(isset($conn)) {mysqli_close($conn);}
	require_once "./template/footer.php";
?>