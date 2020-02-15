<?php
	include "inc/header.php";  
	$about = "";
	$home = "";
	$contact = "active";
?>
<?php include "inc/navbar.php"; ?>


<?php 
	
	if($_SERVER['REQUEST_METHOD'] === 'POST') {


		if(isset($_POST['sendmessage'])) {


			$username = filter_input(INPUT_POST,'username' ,FILTER_SANITIZE_STRING);
			$email = filter_input(INPUT_POST,'email' ,FILTER_SANITIZE_EMAIL);
			$subject = filter_input(INPUT_POST,'subject' ,FILTER_SANITIZE_STRING);
			$message = filter_input(INPUT_POST,'message' ,FILTER_SANITIZE_STRING);


			$error_msg = "";
			if(strlen($username) < 8 || strlen($username) > 20){
				$error_msg = "Username must be between 8, 20 Characters";
			}else if(strlen($email) <= 10 || strlen($email) > 100){
				$error_msg = "Email must be between 11, 100 Characters";
			}else if(strlen($subject) < 5 || strlen($subject) > 100){
				$error_msg = "Subject must be between 5, 100 Characters";
			}else if(strlen($message) < 20 || strlen($message) > 500){
				$error_msg = "Your Message must be between 20, 100 Characters";
			}

			if(empty($error_msg)) {
				$message .= " Reply in < $email >";
				if(! session_id()){
					session_start();
				}
				if(mail('soltan_algaram41@yahoo.com', $subject, $message)) {
					$_SESSION['email_success'] = "has been sent";
				}else {
					$_SESSION['email_fail'] = "has not been sent";
				}
			}


		}


	}

?>



<div class="contact-page">
	<div class="container">
		<div class="form">

			<?php 
			if(! session_id()){
				session_start();
			}
			if(isset($_SESSION['email_success']) && ! empty($_SESSION['email_success'])){
			?>
				<div class="alert alert-success alert-dismissible fade show" role="alert">
				  Your <strong>Email </strong> <?php echo $_SESSION['email_success'] ?>
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				    <span aria-hidden="true">&times;</span>
				  </button>
				</div>
				<?php $_SESSION['email_success'] = ""; ?>
			<?php }else if(isset($_SESSION['email_fail']) && ! empty($_SESSION['email_fail'])){ ?>
				<div class="alert alert-success alert-dismissible fade show" role="alert">
				  Your <strong>Email </strong> <?php echo $_SESSION['email_fail'] ?>
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				    <span aria-hidden="true">&times;</span>
				  </button>
				</div>
				<?php $_SESSION['email_fail'] = ""; ?>
			<?php }
				

			?>

			<h1 class="text-center">Contact Us</h1>
			<p class="text-center"> We'd <i class="fa fa-heart"></i> to help</p>
			<form action="contact.php" method="POST">
				<div class="form-group">
					<input class="form-control" type="text" name="username" placeholder="Username" autocomplete="off" required>	
					<p class="error username-error">Username must be between 8, 20 Characters</p>
				</div>
				<div class="form-group">
					<input class="form-control" type="email" name="email" placeholder="Email" autocomplete="off" required>	
					<p class="error email-error">Email must be between 11, 100 Characters</p>
				</div>
				<div class="form-group">
					<input class="form-control" type="text" name="subject" placeholder="Subject" autocomplete="off" required>	
					<p class="error subject-error">Subject must be between 5, 100 Characters</p>
				</div>
				<div class="form-group">
					<textarea rows="5" class="form-control" name="message" placeholder="Your Message" autocomplete="off" required ></textarea>
					<input type="submit" value="Send Message" name="sendmessage" class="btn btn-default">
					<p class="error message-error">Your Message must be between 20, 100 Characters</p>
					<div class="clearfix"></div>
				</div>

			</form>

		</div>

	</div>

</div>










<?php include "inc/footer.php"; ?>