<?php
	// the shopping cart needs sessions, to start one
	/*
		Array of session(
			cart => array (
				book_isbn (get from $_GET['book_isbn']) => number of books
			),
			items => 0,
			total_price => '0.00'
		)
	*/
	session_start();
	require_once "./functions/database_functions.php";
	// print out header here
	$title = "Checking out";
	require "./template/header.php";
	?>
	<h4 class="fw-bolder text-center">Checkout</h4>
      <center>
        <hr class="bg-warning" style="width:5em;height:3px;opacity:1">
      </center>
<?php
	if(isset($_SESSION['cart']) && (array_count_values($_SESSION['cart']))){
?>
	<div class="card rounded-0 shadow mb-3">
		<div class="card-body">
			<div class="container-fluid">
			<table class="table">
				<tr>
					<th>Item</th>
					<th>Price</th>
					<th>Quantity</th>
					<th>Total</th>
				</tr>
					<?php
						foreach($_SESSION['cart'] as $isbn => $qty){
							$conn = db_connect();
							$book = mysqli_fetch_assoc(getBookByIsbn($conn, $isbn));
					?>
				<tr>
					<td><?php echo $book['book_title'] . " by " . $book['book_author']; ?></td>
					<td><?php echo "$" . $book['book_price']; ?></td>
					<td><?php echo $qty; ?></td>
					<td><?php echo "$" . $qty * $book['book_price']; ?></td>
				</tr>
				<?php } ?>
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th><?php echo $_SESSION['total_items']; ?></th>
					<th><?php echo "$" . $_SESSION['total_price']; ?></th>
				</tr>
			</table>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-lg-5 col-md-8 col-sm-10 col-xs-12">
			<div class="card rounded-0 shadow">
				<div class="card-header">
					<div class="card-title h6 fw-bold">Please Fill the following form</div>
				</div>
				<div class="card-body container-fluid">
					<form method="post" action="purchase.php" class="form-horizontal">
						<?php if(isset($_SESSION['err']) && $_SESSION['err'] == 1){ ?>
							<p class="text-danger">All fields have to be filled</p>
							<?php } ?>
						<div class="mb-3">
							<label for="name" class="control-label">Name</label>
							<input type="text" name="name" class="form-control rounded-0">
						</div>
						<div class="mb-3">
							<label for="address" class="control-label">Address</label>
							<input type="text" name="address" class="form-control rounded-0">
						</div>
						<div class="mb-3">
							<label for="city" class="control-label">City</label>
							<input type="text" name="city" class="form-control rounded-0">
						</div>
						<div class="mb-3">
							<label for="zip_code" class="control-label">Zip Code</label>
							<input type="text" name="zip_code" class="form-control rounded-0">
						</div>
						<div class="mb-3">
							<label for="country" class="control-label">Country</label>
							<input type="text" name="country" class="form-control rounded-0">
						</div>
						<div class="mb-3 d-grid">
							<input type="submit" name="submit" value="Purchase" class="btn btn-primary rounded-0">
						</div>
					</form>
					<p class="fw-light fst-italic"><small class="text-muted">Please press Purchase to confirm your purchase, or Continue Shopping to add or remove items.</small></p>
				</div>
			</div>
		</div>
	</div>
	
<?php
	} else {
		echo "<p class=\"text-warning\">Your cart is empty! Please make sure you add some books in it!</p>";
	}
	if(isset($conn)){ mysqli_close($conn); }
	require_once "./template/footer.php";
?>