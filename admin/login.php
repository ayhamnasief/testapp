
<?php $page_title = "Admin Login | Zblog"; ?>
<?php include "inc/header.php"; ?>
<?php include "inc/functions.php"; ?>

<?php 
$email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_EMAIL);
$admin_found = is_admin($email);
echo $admin_found;
?>

<?php 
	
	if($_SERVER["REQUEST_METHOD"] === 'POST') {

		if(isset($_POST['login'])) {

			$email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_EMAIL);
			$password = $_POST['password'];

			$admin_found = is_admin($email);

			if(! empty($admin_found)) {
				
				// check password
				if( password_verify($password, $admin_found['password']) ) {
					if(! session_id()) {
						session_start();
					}

					$_SESSION['admin_id'] = $admin_found['id'];
					$_SESSION['admin_username'] = $admin_found['username'];
					$_SESSION['admin_email'] = $admin_found['email'];

					update_reset_password_code($_SESSION['admin_email']);

					redirect('index.php');
				}else {
					if(! session_id()) {
						session_start();
					}
					$_SESSION['error'] = "Wrong Password, If You can not remmember Your password click 
					<a href='' data-toggle='modal' data-target='#forgotpassword' class='forgot-password' > Forgot my Password</a>";
				}

			}else {
				// show error wrong email
				if(! session_id()) {
					session_start();
				}
				$_SESSION['error'] = "Wrong Email, You can not access";
			}

		}else {

			if(isset($_POST['resetpassword'])) {

				$email = filter_input(INPUT_POST,'email' , FILTER_SANITIZE_EMAIL);
				$admin = is_admin($email);

				if(! empty($admin)) {
					$reset_password_code = $admin['reset_password_code'];
					$subject = "Reset Your Password";

					$message = "You can Reset You password By Copy this code ( $reset_password_code ) into reset password page";

					if(mail($email,$subject , $message)) {
						if(! session_id()) {
							session_start();
						}
						$_SESSION['email'] = $email;
						redirect("resetpassword.php");
					}else {
						if(! session_id()){
							session_start();
						}
						$_SESSION['error'] = "Unable to send email, Please try again";
					}
				}else {
					if(! session_id()){
							session_start();
					}
					$_SESSION['error'] = "Wrong Email";
				}

			}

		}

	}

?>


<div class="login">
	
	<div class="login-form">
		<?php 
			if(! session_id()) {
				session_start();
			}
			if(isset($_SESSION['error'])) {
				echo "<div class='alert alert-default'>";
				echo $_SESSION['error'];
				echo "</div>";
				$_SESSION['error'] = "";
			}
		?>
		<h6>Hello Admin</h6>
		<form action="login.php" method="POST">
			
			<div class="form-group">
				<input type="email" name="email" placeholder="Email" required autocomplete="off" class="form-control">
				<i class="fa fa-envelope"></i>
			</div>
			<div class="form-group">
				<input type="password" name="password" placeholder="Password" required autocomplete="off" class="form-control">
				<i class="fa fa-unlock-alt"></i>
			</div>
			<input type="submit" name="login" value="Login" class="btn btn-default form-control">
		</form>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="forgotpassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reset Your Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="login.php" method="POST">
        	<input type="email" name="email" placeholder="Email :" required autocomplete="off" class="form-control">
        	<input style="margin-top: 10px;" type="submit" name="resetpassword" value="Send" class="form-control btn btn-default">
        </form>
      </div>
    </div>
  </div>
</div>

<?php include "inc/footer.php"; ?>