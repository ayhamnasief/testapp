<?php include "inc/header.php"; ?>
<?php include "inc/functions.php"; ?>

<div class="registeration">
<a class="navbar-brand logo" href=""><span>ZB</span><span>log</span></a>


<?php 

	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		if(isset($_POST['signup'])) {

			$username = filter_input(INPUT_POST,'username', FILTER_SANITIZE_STRING);
			$email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_EMAIL);
			$password = filter_input(INPUT_POST,'password', FILTER_SANITIZE_STRING);
			$confirmpassword = filter_input(INPUT_POST,'confirmpassword', FILTER_SANITIZE_STRING);

			$error_msg = "";
			if(strlen($username) < 8 || strlen($username) > 20) {
				$error_msg = "Username must be between 8, 20 Characters";
			}else if(strlen($email) < 10 || strlen($email) > 100) {
				$error_msg = "Email must be between 10, 100 Characters";
			}else if(strlen($password) < 8 || strlen($password) > 15) {
				$error_msg = "Password must be between 8, 15 Characters";
			}else if($password !== $confirmpassword) {
				$error_msg = "Confirm Password is not Identical to Password";
			}

			if(! session_id()){
				session_start();
			}
			if(empty($error_msg)) {
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);
				if(add_user($username, $email, $hashed_password)) {
					$_SESSION['user_email'] = $email;
					$_SESSION['user_username'] = $username;
					header("Location: index.php");exit;
				}else {
					$_SESSION['register_error'] = "Registeration Failed";
				}
			}else {
				$_SESSION['register_error'] = $error_msg;
			}

		}else {

			if(isset($_POST['signin'])) {

				$email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_EMAIL);
				$password = filter_input(INPUT_POST,'password', FILTER_SANITIZE_STRING);

				$user_found = is_user($email);

			if(! empty($user_found)) {
				
				// check password
				if( password_verify($password, $user_found['password']) ) {
					if(! session_id()) {
						session_start();
					}
					$_SESSION['user_id'] = $user_found['id'];
					$_SESSION['user_username'] = $user_found['username'];
					$_SESSION['user_email'] = $user_found['email'];

					header("Location: index.php");exit;
				}else {
					if(! session_id()) {
						session_start();
					}
					$_SESSION['login_error'] = "Wrong Password";
				}

			}else {
				// show error wrong email
				if(! session_id()) {
					session_start();
				}
				$_SESSION['login_error'] = "Wrong Email, You can not access";
			}

		}

	}

}

?>




<div class="container">
	<div class="row">
		
		<div class="col-md">
			<div class="signup">
				<?php 
				if(! session_id()){
					session_start();
				}
				if(isset($_SESSION['register_error']) && ! empty($_SESSION['register_error'])) { ?>
					<div class="alert alert-danger">
						<?php echo $_SESSION['register_error']; ?>
					</div>
					<?php $_SESSION['register_error'] = ""; ?>
				<?php }
				?>
				<h2>Sign up</h2>
				<form method="POST" action="signin.php">
					<div class="form-group">
						<input type="text" name="username" placeholder="Username" required autocomplete="off" class="form-control">
					</div>
					<div class="form-group">
						<input type="email" name="email" placeholder="Email" required autocomplete="off" class="form-control">
					</div>
					<div class="form-group">
						<input type="password" name="password" placeholder="Password" required class="form-control">
					</div>
					<div class="form-group">
						<input type="password" name="confirmpassword" placeholder="Confirm Password" required class="form-control">
						<input type="submit" value="Sign-up" name="signup" class="btn btn-default">
					</div>
				</form>
			</div>
		</div>

		<div class="col-md">
			<div class="signin">
				<?php 
				if(! session_id()){
					session_start();
				}
				if(isset($_SESSION['login_error']) && ! empty($_SESSION['login_error'])) { ?>
					<div class="alert alert-danger">
						<?php echo $_SESSION['login_error']; ?>
					</div>
					<?php $_SESSION['login_error'] = ""; ?>
				<?php }
				?>
				<h2>Sign in</h2>
				<form method="POST" action="signin.php">
					<div class="form-group">
						<input type="email" name="email" placeholder="Email" required autocomplete="off" class="form-control">
					</div>
					<div class="form-group">
						<input type="password" name="password" placeholder="Password" required class="form-control">
						<input type="submit" value="Login" name="signin" class="btn btn-default">
					</div>
				</form>
			</div>
		</div>

	</div>

</div>
</div>