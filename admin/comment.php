<?php $page_title = "New Comment | ZBlog";
	include "inc/header.php";
	include "inc/functions.php";
	include "inc/navbar.php";
?>
<?php $comments = "active"; ?>

<?php 	$id = "";
		if(! session_id()) {
			session_start();
		}
		$username = $_SESSION['admin_username'];
		$email = $_SESSION['admin_email'];
		$commenter_comment = "";

	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_POST['addcomment'])) {

			$username = filter_input(INPUT_POST,'username' , FILTER_SANITIZE_STRING);
			$email = filter_input(INPUT_POST,'email' , FILTER_SANITIZE_STRING);
			$comment_comment = filter_input(INPUT_POST,'comment' , FILTER_SANITIZE_STRING);
			$post_id = filter_input(INPUT_POST,'post_id' , FILTER_SANITIZE_STRING);

			$author = "Full Name"; // Temporary Author until creating admins

			date_default_timezone_set("Africa/Cairo");
			$datetime = date('M-d-Y h:m', time());

			$error_msg = "";
			if(strlen($comment_comment) < 20 || strlen($comment_comment) > 1000) {
				$error_msg = "Comment must be between 20 and 1000";
			}

			if(empty($error_msg)) {
				if (! session_id()){
					session_start();
				}
				// Insert Data in Database
				if( insert_comment($datetime, $username, $email, $comment_comment, $post_id)) {
					$_SESSION['success'] = "Comment has been Added Successfully";
					redirect("comments.php");
				}else {
					$_SESSION['error'] = "Unable to Add Comment";
					redirect("comment.php");
				}
			}
		}else {

			if(isset($_POST['updatecomment'])){

			$id = filter_input(INPUT_POST,'id' , FILTER_SANITIZE_NUMBER_INT);

			$username = filter_input(INPUT_POST,'username' , FILTER_SANITIZE_STRING);
			$email = filter_input(INPUT_POST,'email' , FILTER_SANITIZE_STRING);
			$comment_comment = filter_input(INPUT_POST,'comment' , FILTER_SANITIZE_STRING);
			$post_id = filter_input(INPUT_POST,'post_id' , FILTER_SANITIZE_STRING);


			$error_msg = "";
			if(strlen($comment_comment) < 20 || strlen($comment_comment) > 1000) {
				$error_msg = "Title must be between 100 and 200";
			}

			if(empty($error_msg)) {
				
				$updated = update_comment($comment_comment, $post_id, $id);
				if($updated) {

					if(! session_id()){
						session_start();
					}
					$_SESSION['success'] = "Comment has been Updated Successfully";
					redirect("comments.php");
				}else {
					$_SESSION['error'] = "Unable to Update Comment";
					redirect("comment.php");
				}
			}
			}
		}


	}else if(isset($_GET['id'])){

		$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);

		$comment = get_comments($id);

		$commenter_comment = $comment['comment'];

		$comment_post_id = $comment['post_id'];

	}


?>


<div class="container-fluid">
	<div class="row">
		<?php include "inc/media_sidebar.php"; ?>
		<div class="col-sm-2">
			<?php include "inc/sidebar.php"; ?>
		</div>
		<div class="col-sm">
			<div class="comment">
				<?php if(isset($_GET['id'])) { ?>
				<h4>Edit Comment</h4>
			<?php }else {
				echo "<h4>Add New Comment</h4>";
			} ?>
				<form action="comment.php" method="POST">
					<div class="form-group">
						<input type="hidden" name="id" value="<?php echo $id; ?>">
						<input readonly value="<?php echo $username; ?>" class="form-control" type="text" name="username">
					</div>
					<div class="form-group">
						<input readonly value="<?php echo $email; ?>" class="form-control" type="email" name="email" >
					</div>
					<div class="form-group">
						<textarea required placeholder="Comment" autocomplete="off" rows="6" name="comment" class="form-control" ><?php echo $commenter_comment; ?></textarea>
						<p class="error comment-error">Comment must be between 20 and 1000 characters</p>
					</div>
					<div class="form-group">
						<select class="form-control" name="post_id">
							<?php 
								foreach (get_posts() as $post) {
									echo "<option value='{$post['id']}' ";
									if(isset($_GET['id'])) {
										if($comment_post_id === $post['id']) {
											echo "selected >";
										}else {
											echo ">";
										}
									}else {
										echo ">";
									}
									echo $post['title'];
									echo "</option>";
								}
							?>
						</select>
					</div>
					<?php if(isset($_GET['id'])) { ?>
						<input value="Update Comment" type="submit" name="updatecomment" class="btn btn-primary" style="float: right;">
					<?php }else { ?>
					<input value="Add Comment" type="submit" name="addcomment" class="btn btn-primary" style="float: right;">
					<?php } ?>
				</form>
			</div>
		</div>
	</div>
</div>











<?php include "inc/footer.php"; ?>