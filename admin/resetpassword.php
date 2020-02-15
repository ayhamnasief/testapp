<?php $page_title = "Reset Password | ZBlog"; ?>
<?php include "inc/header.php"; ?>
<?php include "inc/functions.php"; ?>

<?php 
	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		if(isset($_POST['submitcode']))
		{
			if(! session_id()){
				session_start();
			}

			$code = filter_input(INPUT_POST,'code', FILTER_SANITIZE_STRING);
			$email = $_SESSION['email'];
			$user_code = is_admin($email)['reset_password_code'];

			if($code === $user_code) {
				$_SESSION['success'] = "Code Submitted Successfully, Please Change You password";
				redirect("profile.php#change_password");
			}else {
				if(! session_id()) {
					session_start();
				}
				$_SESSION['error'] = "Wrong Code, Please try again";
				redirect("login.php");
			}
		}
	}
?>


<div class="container">
	<div class="reset-password-form">
		<form action="resetpassword.php" method="POST">
			<input type="text" name="code" required placeholder="Reset Password Code" autocomplete="off" class="form-control">
			<input style="margin-top: 10px;" type="submit" value="Reset Password" name="submitcode" class="form-control btn btn-default">
		</form>
	</div>
</div>