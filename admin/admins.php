<?php $page_title = "Admins | ZBlog"; ?>
<?php include "inc/header.php"; ?>
<?php include "inc/functions.php"; ?>
<?php include "inc/navbar.php"; ?>

<?php $admins = "active"; ?>

<div class="container-fluid">
	<div class="row">
		<?php include "inc/media_sidebar.php"; ?>
		<div class="col-sm-2">
			<?php include "inc/sidebar.php"; ?>
		</div>
		<div class="col-sm">
			
			<div class="admins">

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

				<h4>Admins</h4>
				<div class="table-responsive">
					<table class="table table-hover table-striped table-dark">
					  <thead>
					    <tr>
					      <th scope="col">#</th>
					      <th scope="col">Created_at</th>
					      <th scope="col">Username</th>
					      <th scope="col">Image</th>
					      <th scope="col">Role Type</th>
					      <th scope="col">Actions</th>
					    </tr>
					  </thead>
					  <tbody>

					  	<?php
					  	$number = 0;
					  	foreach(get_admins() as $admin) { $number++; ?>

					    <tr>
					      <th scope="row"><?php echo $number; ?></th>
					      <td><?php echo $admin['datetime']; ?></td>
					      <td>
					      	<?php 
					      	echo $admin['username'];
					      	?>
					      </td>
					      <td>
					      	<?php if(! empty($admin['image'])) { ?>
					      	<img class="" alt="Admin Banner" height="100" width="100" src="uploads/admins/<?php echo $admin['image']; ?>">	
					      <?php  } else {
					      	echo "No Image";
					      }
					      ?>
					      </td>

					      <td><?php echo $admin['role_type']; ?></td>


					      <td class="action-links">
					      	<a class="btn btn-primary btn-sm" href="admin.php?id=<?php echo $admin['id']; ?>">Edit</a>
					      	<form onsubmit="return confirm('Are You Sure?');" action="deleteadmin.php" method="POST">
					      		<input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
					      		<input class="btn btn-danger btn-sm" type="submit" value="Delete" name="deleteadmin">
					      	</form>
					      </td>
					    </tr>

						<?php } ?>

					  </tbody>
					</table>
					<a class="btn btn-info" style="float: right;" href="admin.php">Add New Admin</a>
				</div>
			</div>

		</div>
	</div>
</div>

<?php include "inc/footer.php"; ?>