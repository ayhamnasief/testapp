<?php $page_title = "Categories"; ?>
<?php include "inc/header.php"; ?>
<?php include "inc/functions.php"; ?>
<?php include "inc/navbar.php"; ?>

<?php $cats = "active"; ?>

<?php 
	$name = "";
		
	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		if(isset($_POST['addcategory'])){

			$name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);
			date_default_timezone_set("Africa/Cairo");
			$datetime = date('M-d-Y h:m', time());
			$creater_name = "Full Name"; // temporary name until creating admins

			$error_msg = "";
			if( strlen($name) < 5 || strlen($name) > 50) {
				$error_msg = "Category Name must be between 5 and 50 character";
			}

			if(empty($error_msg)) {
				if (! session_id()){
					session_start();
				}
				if(insert_category($datetime,$name,$creater_name)) {
					$_SESSION['success'] = "Category has been Added Successfully";
					redirect("categories.php");					
				}else {
					$_SESSION['error'] = "Unable to Insert Category";
					redirect("categories.php");	
				}
			}else {
				if (! session_id()){
					session_start();
				}
				$_SESSION['error'] = $error_msg;
				redirect("categories.php");	
			}	

		} else {

			if(isset($_POST['updatecategory'])) {

				$id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
				$name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_STRING);

				$error_msg = "";
				if( strlen($name) < 5 || strlen($name) > 50) {
					$error_msg = "Category Name must be between 5 and 50 character";
				}

				if(empty($error_msg)) {
					if (! session_id()){
						session_start();
					}
					if(update_category($name, $id)) {
						$_SESSION['success'] = "Category has been Updated Successfully";
						redirect("categories.php");					
					}else {
						$_SESSION['error'] = "Unable to Update Category";
						redirect("categories.php");	
					}
				}else {
					if (! session_id()){
						session_start();
					}
					$_SESSION['error'] = $error_msg;
					redirect("categories.php");	
				}	
			}
		}

	} else {
		if(isset($_GET['id']) && ! empty($_GET['id'])) {

			$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);

			$category = get_categories($id);
			$name = $category['name'];
		}
	}

?>


<div class="container-fluid">
	<div class="row">
		<?php include "inc/media_sidebar.php"; ?>
		<div class="col-sm-2">
			<?php include "inc/sidebar.php"; ?>
		</div>
		<div class="col-sm">
			
			<div class="categories">
				<?php 
					if( ! session_id() ) {
						session_start();
					}
					if( isset($_SESSION['success']) && ! empty($_SESSION['success'])) {
						echo "<div class='alert alert-success'>";
						echo $_SESSION['success'];
						echo "</div>";
						$_SESSION['success'] = "";
					}
					if( isset($_SESSION['error']) && ! empty($_SESSION['error'])) {
						echo "<div class='alert alert-danger'>";
						echo $_SESSION['error'];
						echo "</div>";
						$_SESSION['error'] = "";
					}
				?>

				<h4>Categories</h4>
				<form action="categories.php" method="POST">
					<div class="row">
						<div class="col-sm-10">
							<div class="form-group">
								<input type="hidden" name="id" value="<?php echo $id; ?>">
								<input value="<?php echo $name; ?>" type="text" name="name" class="form-control" placeholder="Category Name" autocomplete="off" required>
							</div>
						</div>
						<div class="col-sm">
							<?php if(isset($_GET['id'])) {
								echo '<input value="Update Category" type="submit" name="updatecategory" class="btn btn-primary">';
							}else { ?>
								<input value="Add Category" type="submit" name="addcategory" class="btn btn-primary">
							<?php } ?>
						</div>
					</div>
				</form>
				<div class="table-responsive">
					<table class="table table-hover table-striped table-dark">
					  <thead>
					    <tr>
					      <th scope="col">#</th>
					      <th scope="col">datetime</th>
					      <th scope="col">Category</th>
					      <th scope="col">Created_by</th>
					      <th scope="col">Actions</th>
					    </tr>
					  </thead>
					  <tbody>

					  	<?php
					  	$number = 0;
					  	foreach(get_categories() as $category) { $number++; ?>

					    <tr>
					      <th scope="row"><?php echo $number; ?></th>
					      <td><?php echo $category['datetime']; ?></td>
					      <td class="cat_name">
					      	<?php 
					      		echo $category['name'];
					      	?>
					      	
					      </td>
					      <td>
					      	<?php 
					      		echo $category['creater_name'];
					      	?>
					      </td>
					      <td class="action-links">
					      	<a class="btn btn-primary btn-sm" href="categories.php?id=<?php echo $category['id']; ?>">Edit</a>
					      	<form onsubmit="return confirm('Are You Sure?');" action="deletecategory.php" method="POST">
					      		<input type="hidden" name="id" value="<?php echo $category['id']; ?>">
					      		<input class="btn btn-danger btn-sm" type="submit" value="Delete" name="deletecategory">
					      	</form>
					      </td>
					    </tr>
						<?php } ?>
					  </tbody>
					</table>
				</div>
			</div>

		</div>
	</div>
</div>

<?php include "inc/footer.php"; ?>