<?php
session_start();
if(isset($_SESSION['admin']) && $_SESSION['admin'] == true){
	header('location:admin_book.php');
}
	$title = "Admin Panel";
	require_once "./template/header.php";
?>
<div class="row justify-content-center my-5">
	<div class="col-lg-4 col-md-6 col-sm-10 col-xs-12">
		<div class="card rounded-0 shadow">
			<div class="card-header">
				<div class="card-title text-center h4 fw-bolder">Login</div>
			</div>
			<div class="card-body">
				<div class="container-fluid">
					<?php if(isset($_SESSION['err_login'])): ?>
						<div class="alert alert-danger rounded-0">
							<?= $_SESSION['err_login'] ?>
						</div>
					<?php 
						unset($_SESSION['err_login']);
						endif;
					?>
					<form class="form-horizontal" method="post" action="admin_verify.php">
						<div class="mb-3">
							<label for="name" class="control-label ">Username</label>
							<input type="text" name="name" class="form-control rounded-0">
						</div>
						<div class="mb-3">
							<label for="pass" class="control-label ">Password</label>
							<input type="password" name="pass" class="form-control rounded-0">
						</div>
						<div class="mb-3 d-grid">
							<input type="submit" name="submit" class="btn btn-primary rounded-0">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
	

<?php
	require_once "./template/footer.php";
?>