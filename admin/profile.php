<?php $page_title = "New Post | ZBlog";
	include "inc/header.php";
	include "inc/functions.php";
	include "inc/navbar.php";
?>

<?php 
	if(! session_id()) {
		session_start();
	}
	$email = $_SESSION['admin_email'];

	$admin = is_admin($email);
	$username = "";
?>


<?php 

	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		if(isset($_POST['updateinfo'])) {
			$id = filter_input(INPUT_POST,'id', FILTER_SANITIZE_NUMBER_INT);
			$username = filter_input(INPUT_POST,'username', FILTER_SANITIZE_STRING);
			$email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_EMAIL);

			$image = $_FILES['image'];

			$image_name = $image['name'];
			$image_tmp_name = $image['tmp_name'];
			$image_size = $image['size'];


			$error_msg = "";
			if(strlen($username) < 4 || strlen($username) > 30) {
				$error_msg = "Username must be between 5, 25 Characters ";
			}else if(strlen($email) < 10 || strlen($email) > 100) {
				$error_msg = "Email must be between 11, 100 Characters ";
			}else {

				if(! empty($image_name)) { 
					$img_extension = strtolower(explode('.', $image_name)[1]);

					$allowed_extensions = array('jpg' , 'png' , 'jpeg');

					if(! in_array($img_extension, $allowed_extensions)) {
						$error_msg = "Allowed Extensions are jpg, png and jpeg ";
					}else if( $img_size > 1200000) {
						$error_msg = "Image size must be less than 1M";
					}
				}
			}

			if(empty($error_msg)) {

				if (! session_id()){
					session_start();
				}
				// Insert Data in Database
				if( update_admin_profile($username,$email,$image_name, $id) ) {

					// Send email with new password to the new email to check if he has this email 

					$_SESSION['admin_username'] = $username;
					$_SESSION['admin_email'] = $email;

					if(! empty($image_name)) {
						$new_path = "uploads/admins/".$image_name;
						move_uploaded_file( $image_tmp_name, $new_path);
					}
					$_SESSION['success'] = "Your Info has been Updated Successfully";
					redirect("profile.php");
				}else {
					$_SESSION['error'] = "Unable to Update Your Info";
					redirect("profile.php");
				}

			} else {
				if(! session_id()){
					session_start();
				}
				$_SESSION['error'] = $error_msg;
			}

		}else {


			if(isset($_POST['updatepassword'])) {

				$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

				$password = $_POST['password'];
				$confirmpassword = $_POST['confirmpassword'];
				$error_msg = "";
				if(strlen($password) < 6 || strlen($password) > 20) {
					$error_msg = "Password must be between 6, 20 Characters";
				}else if($password !== $confirmpassword){
					$error_msg = "Confirm Password Correctly";
				}else {

					if(! session_id()) {
						session_start();
					}
					
					$hashpassword = password_hash($password, PASSWORD_DEFAULT );
					if(update_password($hashpassword, $id)) {
						$_SESSION['success'] = "Password has changed Successfully";
					}else {
						$_SESSION['error'] = "Password has not changed";
					}
				}

				if(! empty($error_msg)) {
					if(! session_id()) {
						session_start();
					}
					$_SESSION['error'] = $error_msg;
				}
			}
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
			<div class="profile">

				<?php 
				if(! session_id()) {
					session_start();
				}
				if(isset($_SESSION['success']) && ! empty($_SESSION['success'])) {
					echo "<div class='alert alert-success'>";
					echo $_SESSION['success'];
					echo "</div>";
					$_SESSION['success'] = "";
				}else if(isset($_SESSION['error']) && ! empty($_SESSION['error'])) {
						echo "<div class='alert alert-danger'>";
						echo $_SESSION['error'];
						echo "</div>";
						$_SESSION['error'] = "";
				}
				?>


				<div class="row">
					<div class="col-sm-4 text-center">
						<img class="admin-image img-thumbnail" width="200" height="200" src="uploads/admins/<?php echo $admin['image']; ?>">
						<span class="role-type"><?php echo $admin['role_type']; ?></span>
						<h5 class="username"><?php echo $admin['username']; ?></h5>
						<h6 class="email"><?php echo $admin['email']; ?></h6>
					</div>
					<div class="col-sm-6">
						
						<div class="update-info">
							<h6>Update Admin Info</h6>
							<form action="profile.php" method="POST" enctype="multipart/form-data">
								<input type="hidden" name="id" value="<?php echo $_SESSION['admin_id']; ?>" >
								<div class="form-group">
									<input type="text" value="<?php echo $admin['username']; ?>" name="username" placeholder="Username" required autocomplete="off" class="form-control">
								</div>
								<div class="form-group">
									<input type="email" value="<?php echo $admin['email']; ?>" name="email" placeholder="Email" autocomplete="off" class="form-control">
								</div>
								<div class="form-group">
									<input type="file" name="image" class="form-control">
									<input value="Submit" style="float: right;" type="submit" name="updateinfo" class="btn btn-default">
								</div>

							</form>
						</div>

						<div class="update-password">
							<h6>Update Admin Password</h6>
							<form action="profile.php" method="POST">
								<input type="hidden" name="id" value="<?php echo $_SESSION['admin_id']; ?>" >
								<div class="form-group">
									<input type="password" name="password" placeholder="Password" required autocomplete="off" class="form-control">
								</div>
								<div class="form-group">
									<input type="password" name="confirmpassword" placeholder="Confirm Password" required autocomplete="off" class="form-control">
								</div>
								<input value="Submit" style="float: right;" type="submit" name="updatepassword" class="btn btn-default">
							</form>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include "inc/footer.php"; ?>