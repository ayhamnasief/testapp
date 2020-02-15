<?php $page_title = "Users | ZBlog"; ?>
<?php include "inc/header.php"; ?>
<?php include "inc/functions.php"; ?>
<?php include "inc/navbar.php"; ?>

<?php $users = "active"; ?>

<div class="container-fluid">
	<div class="row">
		<?php include "inc/media_sidebar.php"; ?>
		<div class="col-sm-2">
			<?php include "inc/sidebar.php"; ?>
		</div>
		<div class="col-sm">
			
			<div class="users">

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

				<h4>Users</h4>
				<div class="table-responsive">
					<table class="table table-hover table-striped table-dark">
					  <thead>
					    <tr>
					      <th scope="col">#</th>
					      <th scope="col">Username</th>
					      <th scope="col">Email</th>
					      <th scope="col">Image</th>
					      <th scope="col">Actions</th>
					    </tr>
					  </thead>
					  <tbody>

					  	<?php
					  	$number = 0;
					  	foreach(get_users() as $user) { $number++; ?>

					    <tr>
					      <th scope="row"><?php echo $number; ?></th>
					      <td>
					      	<?php 
					      	echo $user['username'];
					      	?>
					      </td>
					      <td>
					      	<?php 
					      	echo $user['email'];
					      	?>
					      </td>
					      <td>
					      	<?php if(! empty($user['image'])) { ?>
					      	<img class="" alt="User Banner" height="100" width="100" src="uploads/users/<?php echo $user['image']; ?>">	
					      <?php  } else {
					      	echo "No Image";
					      }
					      ?>
					      </td>

					      <td class="action-links">
					      	<form onsubmit="return confirm('Are You Sure?');" action="deleteuser.php" method="POST">
					      		<input type="hidden" name="id" value="<?php echo $user['id']; ?>">
					      		<input class="btn btn-danger btn-sm" type="submit" value="Delete" name="deleteuser">
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