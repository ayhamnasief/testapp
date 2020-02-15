<?php 
	$page_title = "New Admin | ZBlog";
	include "inc/header.php";
	include "inc/functions.php";
	include "inc/navbar.php";
?>

<?php $admins = "active"; ?>

<?php 	
		$id = "";
		$username = "";
		$email = "";
		$excerpt = "";
		$tags = "";

	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_POST['addadmin'])) {

			$username = filter_input(INPUT_POST,'username' , FILTER_SANITIZE_STRING);
			$email = filter_input(INPUT_POST,'email' , FILTER_SANITIZE_STRING);
			$roletype = filter_input(INPUT_POST,'roletype' , FILTER_SANITIZE_STRING);

			$created_by = "Full Name"; // Temporary Author until creating admins

			date_default_timezone_set('asia/dubai');
			$datetime = date('M-d-Y h:m', time());

			$password = password_hash('11111111',PASSWORD_DEFAULT);

			$image = $_FILES['image'];

			$img_name = $image['name'];
			$img_tmp_name = $image['tmp_name'];
			$img_size = $image['size'];


			$error_msg = "";
			if(strlen($username) < 5 || strlen($username) > 30) {
				$error_msg = "Username must be between 5 and 30 Characters";
			}else if(strlen($email) < 10 || strlen($email) > 100) {
				$error_msg = "Email must be between 10 and 100 Characters";
			}else {

				if(! empty($img_name)) { 
					$img_extension = strtolower(explode('.', $img_name)[1]); // gfdgdfg.jpg

					$allowed_extensions = array('jpg' , 'png' , 'jpeg');

					if(! in_array($img_extension, $allowed_extensions)) {
						$error_msg = "Allowed Extensions are jpg, png and jpeg ";
					}else if( $img_size > 1000000) {
						$error_msg = "Image size must be less than 1M";
					}
				}
			}

			if(empty($error_msg)) {
				if (! session_id()){
					session_start();
				}
				// Insert Data in Database
				if( insert_admin($datetime, $username, $email,$password,$roletype, $created_by, $img_name) ) {
					
					// send password to admin
					if (password_verify('11111111', $password)) {
						$send_password = "11111111";
						$subject = "Recieve Your Password";

						$message = "You have been added in ZBlog Website as Admin, Congrats Your password is $send_password , You can change it in your admin panel";

						mail($email, $subject, $message);
					}
					if(! empty($img_name)) {
						$new_path = "uploads/admins/".$img_name;
						move_uploaded_file( $img_tmp_name, $new_path);
					}
					$_SESSION['success'] = "Admin has been Added Successfully";
					redirect("admins.php");
				}else {
					$_SESSION['error'] = "Unable to Add Admin";
					redirect("admins.php");
				}
			}
		}else {

			if(isset($_POST['updateadmin'])){

			$id = filter_input(INPUT_POST,'id' , FILTER_SANITIZE_NUMBER_INT);

			$username = filter_input(INPUT_POST,'username' , FILTER_SANITIZE_STRING);
			$email = filter_input(INPUT_POST,'email' , FILTER_SANITIZE_STRING);
			$roletype = filter_input(INPUT_POST,'roletype' , FILTER_SANITIZE_STRING);

			$image = $_FILES['image'];

			$img_name = $image['name'];
			$img_tmp_name = $image['tmp_name'];
			$img_size = $image['size'];


			$error_msg = "";
			if(strlen($username) < 5 || strlen($username) > 30) {
				$error_msg = "Username must be between 5 and 30 Characters";
			}else if(strlen($email) < 10 || strlen($email) > 100) {
				$error_msg = "Email must be between 10 and 100 Characters";
			}else {

				if(! empty($img_name)) { 
					$img_extension = strtolower(explode('.', $img_name)[1]); // gfdgdfg.jpg

					$allowed_extensions = array('jpg' , 'png' , 'jpeg');

					if(! in_array($img_extension, $allowed_extensions)) {
						$error_msg = "Allowed Extensions are jpg, png and jpeg ";
					}else if( $img_size > 1000000) {
						$error_msg = "Image size must be less than 1M";
					}
				}
			}

			if(empty($error_msg)) {
				if (! session_id()){
					session_start();
				}
				// Insert Data in Database
				if( update_admin($username,$roletype,$img_name, $id) ) {
					if(! empty($img_name)) {
						$new_path = "uploads/admins/".$img_name;
						move_uploaded_file( $img_tmp_name, $new_path);
					}
					$_SESSION['success'] = "Admin has been Updated Successfully";
					redirect("admins.php");
				}else {
					$_SESSION['error'] = "Unable to Update Admin";
					redirect("admins.php");
				}
			}
		}
	}


	}else if(isset($_GET['id'])){

		$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);

		$admin = get_admins($id);

		$username = $admin['username'];
		$email = $admin['email'];
		$roletype = $admin['role_type'];
		$image = $admin['image'];

		$roletypes = array("Admin", "Subscriber");
	}


?>


<div class="container-fluid">
	<div class="row">
		<?php include "inc/media_sidebar.php"; ?>
		<div class="col-sm-2">
			<?php include "inc/sidebar.php"; ?>
		</div>
		<div class="col-sm">
			<div class="admin">
				<?php if(isset($_GET['id'])) { ?>
				<h4>Edit Admin</h4>
			<?php }else {
				echo "<h4>Add New Admin</h4>";
			} ?>
				<form action="admin.php" method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<input type="hidden" name="id" value="<?php echo $id; ?>">
						<input value="<?php echo $username; ?>" class="form-control" type="text" name="username" placeholder="Username" required autocomplete="off" >
						<p class="error username-error">Username must be between 5 and 30 characters</p>
					</div>
					<div class="form-group">
						<input value="<?php echo $email; ?>" class="form-control" type="email" name="email" placeholder="Email" required autocomplete="off" >
						<p class="error email-error">Email must be between 10 and 100 characters</p>
					</div>
					<div class="form-group">
						<select class="form-control" name="roletype">

							<?php if(isset($_GET['id'])) { ?>

							<?php foreach ($roletypes as $role) { ?>
								<option value="<?php echo $role ?>" <?php if($role === $roletype) {echo "selected >";}else {
									echo ">";
								} ?>

									<?php echo $role ?></option>
							<?php } 
								}else { ?>
									<option value="Admin" >Admin</option>
									<option value="Subscriber" >Subscriber</option>
								<?php }
							?>
						</select>
					</div>
					<?php 
						if(isset($_GET['id'])) {

							if(! empty($image)){ ?>
								<label style="margin-left: 15px;">Admin Photo: <img width="100" src="uploads/admins/<?php echo $image; ?>" ></label>
						<?php	}

						}
					?>
					<?php if(! empty($post['image'])){ ?>
						<label>Post Image: </label>
						<img width="100" src="uploads/posts/<?php echo $post['image'];?>">
					<?php } ?>

					<div class="form-group">
						<input type="file" name="image" class="form-control">
					</div>
					<?php if(isset($_GET['id'])) { ?>
						<input value="Update Admin" type="submit" name="updateadmin" class="btn btn-primary" style="float: right;">
					<?php }else { ?>
					<input value="Add Admin" type="submit" name="addadmin" class="btn btn-primary" style="float: right;">
					<?php } ?>
				</form>
			</div>
		</div>
	</div>
</div>


<?php include "inc/footer.php"; ?>