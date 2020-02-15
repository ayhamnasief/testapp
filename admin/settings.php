<?php $page_title = "Settings | ZBlog"; ?>
<?php include "inc/header.php"; ?>
<?php include "inc/functions.php"; ?>
<?php include "inc/navbar.php"; ?>
<?php $settings = "active"; ?>

<?php 
	if($_SERVER['REQUEST_METHOD'] === "POST") {

		if(isset($_POST['save-general'])) {
			$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
			$tagline = filter_input(INPUT_POST, 'tagline', FILTER_SANITIZE_STRING);

			$logo = $_FILES['logo'];

			$logo_name = $logo['name'];
			$logo_tmp_name = $logo['tmp_name'];
			$logo_size = $logo['size'];

			$error_msg = "";
			if(strlen($name) < 3 || strlen($name) > 20) {
				$error_msg = "Site Name must be between 3, 20 Character ";
			}else if(strlen($tagline) < 10 || strlen($tagline) > 100) {
				$error_msg = "Site Tagline must be between 10, 100 Character";
			} else {

				if(! empty($logo_name)) { 
					$img_extension = strtolower(explode('.', $logo_name)[1]); // gfdgdfg.jpg

					$allowed_extensions = array('jpg' , 'png' , 'jpeg');

					if(! in_array($img_extension, $allowed_extensions)) {
						$error_msg = "Allowed Extensions are jpg, png and jpeg ";
					}else if( $logo_size > 2000000) {
						$error_msg = "Logo size must be less than 2M";
					}
				}
			}

			if(empty($error_msg)) {
				$updated = "";
				if(empty($logo_name)) {
					$updated = update_general_settings($name, $tagline);
				}else {
					$updated = update_general_settings($name, $tagline, $logo_name);
				}

				if($updated) {

					if(! empty($logo_name)) {
						$new_path = "uploads/".$logo_name;
						move_uploaded_file($logo_tmp_name, $new_path);
					}

					if(! session_id()) {
						session_start();
					}
					$_SESSION['success'] = "Settings has Changed";
				}else {
					$_SESSION['error'] = "Settings has not Changed";
				}
			}else {
				$_SESSION['error'] = $error_msg;
			}

		}else {

			if(isset($_POST['save-posts'])) {

				$hpn = filter_input(INPUT_POST,'hpn',FILTER_SANITIZE_NUMBER_INT);
				$posts_order = filter_input(INPUT_POST,'posts_order',FILTER_SANITIZE_STRING);
				$rpn = filter_input(INPUT_POST,'rpn',FILTER_SANITIZE_NUMBER_INT);
				$relatedpn = filter_input(INPUT_POST,'relatedpn',FILTER_SANITIZE_NUMBER_INT);

				if(update_posts_settings($hpn, $posts_order, $rpn, $relatedpn)) {
					if(! session_id()) {
						session_start();
					}
					$_SESSION['success'] = "Post Settings has Changed";
				}else {
					$_SESSION['error'] = "Post Settings has not Changed";
				}
			}

		}		
	}

?>

<?php 
	foreach (get_settings() as $setting) {
		$name = $setting['name'];
		$tagline = $setting['tagline'];

		$logo = $setting['logo'];

		$hpn = $setting['home_posts_number'];
		$posts_order = $setting['posts_order'];
		$rpn = $setting['recent_posts_number'];
		$relatedpn = $setting['related_posts_number'];
	}
?>

<div class="container-fluid">
	<div class="row">
		<?php include "inc/media_sidebar.php"; ?>
		<div class="col-sm-2">
			<?php include "inc/sidebar.php"; ?>
		</div>
		<div class="col-sm">
			<div class="settings">
				
				<div class="general-settings">
					<?php 
					if(! session_id()) {
						session_start();
					}
					if(isset($_SESSION['success']) && ! empty($_SESSION['success'])) {
						echo "<div class='alert alert-success'>";
						echo $_SESSION['success'];
						echo "</div>";
						$_SESSION['success'] = "";
					}else if(isset($_SESSION['success']) && ! empty($_SESSION['success'])) {
						echo "<div class='alert alert-danger'>";
						echo $_SESSION['error'];
						echo "</div>";
						$_SESSION['error'] = "";
					}
					?>
					<h4>General Settings</h4>
					<form action="settings.php" method="POST" enctype="multipart/form-data">
						<div class="row">
							<div class="col-sm-2">
								<label>Site Name : </label>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<input value="<?php echo $name; ?>" type="text" name="name" class="form-control" placeholder="Site Name" required autocomplete="off">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2">
								<label>Site Tagline : </label>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<input value="<?php echo $tagline; ?>" type="text" name="tagline" class="form-control" placeholder="Site Tagline" required autocomplete="off">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2">
								<label>Site Logo : </label>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<img class="logo" width="200" height="200" src="uploads/<?php echo $logo; ?>">
									<input type="file" name="logo" class="form-control">
									<input style="float: right;" type="submit" name="save-general" class="btn btn-info" value="Save Changes">
								</div>
							</div>
						</div>
					</form>
				</div>
				<hr>
				<h4>Posts Settings</h4>
					<form action="settings.php" method="POST">
						<div class="row">
							<div class="col-sm-2">
								<label>Home Posts Number : </label>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<input value="<?php echo $hpn; ?>"  min="2" max="20" type="number" name="hpn" class="form-control">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2">
								<label>Posts Order : </label>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<select class="form-control" name="posts_order">
										<?php 
											if($posts_order === "newest") {
												echo "<option value='newest' selected >Newest";
												echo "</option>";
												echo "<option value='oldest'>Oldest";
												echo "</option>";
											}else {
												echo "<option value='newest' >Newest";
												echo "</option>";
												echo "<option value='oldest' selected>Oldest";
												echo "</option>";
											}
										?>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2">
								<label>Recent Posts Number : </label>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<input value="<?php echo $rpn; ?>" min="2" max="10" type="number" name="rpn" class="form-control">	
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2">
								<label>Related Posts Number : </label>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<input value="<?php echo $relatedpn; ?>" min="1" max="3" type="number" name="relatedpn" class="form-control">	
									<input style="float: right;" class="btn btn-info" type="submit" name="save-posts" value="Save Changes">
								</div>
							</div>
						</div>
					</form>
				</div>

			</div>
		</div>
	</div>
</div>

<?php include "inc/footer.php"; ?>