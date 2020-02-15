<?php include "inc/header.php"; ?>
<?php include "inc/navbar.php"; ?>
<?php include "inc/functions.php"; ?>

<?php 
	if(! session_id()){
		session_start();
	}
	if(isset($_SESSION['user_email']) && ! empty($_SESSION['user_email'])) {
		$user_email = $_SESSION['user_email'];
		$user = is_user($user_email);
	}
?>


<?php 

	if($_SERVER['REQUEST_METHOD'] === 'POST' ) {

		if(isset($_POST['savedata'])) {
			$id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
			$username = filter_input(INPUT_POST,'username',FILTER_SANITIZE_STRING);
			$new_email = filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
			$old_email = $user['email'];
			$image = $_FILES['image'];

			$img_name = $image['name'];
			$img_tmp_name = $image['tmp_name'];
			$img_size = $image['size'];


			$error_msg = "";
			if(strlen($username) < 6 || strlen($username) > 30) {
				$error_msg = "Username must be between 6 and 30 Characters";
			}else if(strlen($new_email) < 10 || strlen($new_email) > 100) {
				$error_msg = "Email must be between 10 and 100 Characters";
			}else {

				if(! empty($img_name)) {
					$img_extension = strtolower(explode('.', $img_name)[1]); // gfdgdfg.jpg

					$allowed_extensions = array('jpg' , 'png' , 'jpeg');

					if(! in_array($img_extension, $allowed_extensions)) {
						$error_msg = "Allowed Extensions are jpg, png and jpeg ";
					}else if( $img_size > 1200000) {
						$error_msg = "Image size must be less than 1M";
					}
				}
			}

			if(empty($error_msg)) {

				if($new_email === $old_email) {
					if(! session_id()){
						session_start();
					}
					if(update_user($username, $new_email, $img_name, $id)) {
						if(! empty($img_name)) {
							$new_path = "uploads/users/".$img_name;
							move_uploaded_file( $img_tmp_name, $new_path);
						}
						$_SESSION['data_success'] = "Your Info has Updated";
						$_SESSION['user_username'] = $username;
						$_SESSION['user_email'] = $new_email;
						header("Location: profile.php");
					}else {
						$_SESSION['data_error'] = "Your Info has not Updated";
					}

				} else {

					if(is_user($new_email)) {

						if(! session_id()){
							session_start();
						}
						$_SESSION['data_error'] = "Your Info has not Updated";
					
					} else {

						if(! session_id()){
							session_start();
						}
						if(update_user($username, $new_email, $img_name, $id)) {
							if(! empty($img_name)) {
								$new_path = "uploads/users/".$img_name;
								move_uploaded_file( $img_tmp_name, $new_path);
							}
							$_SESSION['data_success'] = "Your Info has Updated";
							$_SESSION['user_username'] = $username;
							$_SESSION['user_email'] = $new_email;
							header("Location: profile.php");
						}else {
							$_SESSION['data_error'] = "Your Info has not Updated";
						}

					}

				}

			} else {

				if(! session_id()){
					session_start();
				}
				$_SESSION['data_error'] = $error_msg;
			}


		} else {

			if(isset($_POST['updatepassword'])) {

				$email = filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
				$password = filter_input(INPUT_POST,'password',FILTER_SANITIZE_STRING);
				$confirmpassword = filter_input(INPUT_POST,'confirmpassword',FILTER_SANITIZE_STRING);

				$error_msg = "";
				if(strlen($password) < 6 || strlen($password) > 15) {
					$error_msg = "Password Length must between 6, 15 Characters";
				}else if($password !== $confirmpassword) {
					$error_msg = "Password is not Identucal to Confirm Password";
				}
				if(! session_id()){
					session_start();
				}
				if(empty($error_msg)) {
					$hashed_password = password_hash($password, PASSWORD_DEFAULT);
					if(update_user_password($hashed_password, $email)) {
						$_SESSION['password_success'] = "Password has Changed";

					}else {
						$_SESSION['password_error'] = "Password has not Changed";
					}
				}else {
					$_SESSION['password_error'] = $error_msg;
				}

			}

		}

	}

?>


<div class="container">
	
	<div class="user-profile">
		<h4>
			<?php 
				$firstName = explode(' ',$user['username'])[0];
				echo $firstName . "'s Profile ";
			?>

		</h4>


		<div class="user-image text-center">
			<?php if(empty($user['image'])){ ?>
			<img src="admin/uploads/admins/user.jpg" width="200" height="200" class="img-thumbnail">
		<?php } else { ?>
			<img src="uploads/users/<?php echo $user['image']; ?>" width="200" height="200" class="img-thumbnail">
		<?php } ?>
		</div>

		<div class="user-data">
			
			<div class="row">
			 	
				<div class="col-md">
					
					<div class="user-general">
						<?php 
						if(! session_id()){
							session_start();
						}
						if(isset($_SESSION['data_error']) && ! empty($_SESSION['data_error'])) { ?>
							<div class="alert alert-danger">
								<?php echo $_SESSION['data_error']; ?>
							</div>
							<?php $_SESSION['data_error'] = ""; ?>
						<?php }
						?>
						<?php 
						if(! session_id()){
							session_start();
						}
						if(isset($_SESSION['data_success']) && ! empty($_SESSION['data_success'])) { ?>
							<div class="alert alert-success">
								<?php echo $_SESSION['data_success']; ?>
							</div>
							<?php $_SESSION['data_success'] = ""; ?>
						<?php }
						?>
						<form action="profile.php" method="POST" enctype="multipart/form-data">
							
							<div class="form-group">
								
								<div class="row">
									<div class="col-md-3">
										<label for="username">Username: </label>
									</div>
									<div class="col-md-8">
										<input type="hidden" name="id" value="<?php echo $user['id']; ?>">
										<input id="username" type="text" value="<?php echo $user['username'] ?>" name="username" class="form-control">
									</div>

								</div>

							</div>
							<div class="form-group">
								
								<div class="row">
									
									<div class="col-md-3">
										<label for="email">Email: </label>
									</div>
									<div class="col-md-8">
										<input id="email" type="email" value="<?php echo $user['email'] ?>" name="email" class="form-control">
									</div>

								</div>

							</div>
							<div class="form-group">
								
								<div class="row">
									
									<div class="col-md-3">
										<label>Image: </label>
									</div>
									<div class="col-md-8">
										<input type="file" name="image" class="form-control">
										<input type="submit" value="Update Info" name="savedata" class="btn btn-default">
									</div>

								</div>

							</div>

						</form>
					</div>

				</div>

				<div class="col-md">
					<div class="user-password">
						<?php 
						if(! session_id()){
							session_start();
						}
						if(isset($_SESSION['password_error']) && ! empty($_SESSION['password_error'])) { ?>
							<div class="alert alert-danger">
								<?php echo $_SESSION['password_error']; ?>
							</div>
							<?php $_SESSION['password_error'] = ""; ?>
						<?php }
						?>
						<?php 
						if(! session_id()){
							session_start();
						}
						if(isset($_SESSION['password_success']) && ! empty($_SESSION['password_success'])) { ?>
							<div class="alert alert-success">
								<?php echo $_SESSION['password_success']; ?>
							</div>
							<?php $_SESSION['password_success'] = ""; ?>
						<?php }
						?>
						<form action="profile.php" method="POST">
							
							<div class="form-group">
								
								<div class="row">
									
									<div class="col-md-4">
										<label for="password">Password: </label>
									</div>
									<div class="col-md-8">
										<input type="hidden" name="email" value="<?php echo $user['email'] ?>">
										<input id="password" type="password" name="password" class="form-control">
									</div>

								</div>

							</div>
							<div class="form-group">
								
								<div class="row">
									
									<div class="col-md-4">
										<label for="confirmpassword">Conform Password: </label>
									</div>
									<div class="col-md-8">
										<input id="confirmpassword" type="password" name="confirmpassword" class="form-control">
										<input type="submit" value="Update Password" name="updatepassword" class="btn btn-default">
									</div>

								</div>

							</div>
						
						</form>
					</div>
				</div>

			</div>

		</div>

	</div>

</div>

<?php include "inc/footer.php"; ?>