<?php include "inc/header.php"; ?>
<?php 
	$about = "";
	$home = "active";
	$contact = "";
?>
<?php include "inc/navbar.php"; ?>


<?php 
	if(isset($_GET['post_id'])) {

		$id = filter_input(INPUT_GET,'post_id',FILTER_SANITIZE_NUMBER_INT);

		$post = get_posts($id);

		if(! $post) {
			redirect("page404.php");
		}
	}else {
		redirect('index.php');
	}
?>

<?php 
	
	if($_SERVER['REQUEST_METHOD'] === 'POST') {

		if(isset($_POST['addcomment'])) {

			$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
			$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
			$comment_comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);

			date_default_timezone_set("Africa/Cairo");
			$datetime = date('M-d-Y h:m', time());

			$error_msg = "";
			if(strlen($username) < 8 || strlen($username) > 20){
				$error_msg = "Username must be between 8, 20 Characters";
			}else if(strlen($email) <= 20 || strlen($email) > 100){
				$error_msg = "Email must be between 11, 100 Characters";
			}else if(strlen($comment_comment) < 20 || strlen($comment_comment) > 500){
				$error_msg = "Comment must be between 20, 200 Characters";
			}


			if(empty($error_msg)) {

				if(insert_comment($datetime, $username, $email, $comment_comment, $id)) {

					if(! session_id()){
						session_start();
					}
					$_SESSION['user_success'] = "Comment has Added Successfully";
				} else {
					$_SESSION['user_error'] = "Comment has not Added Successfully";
				}

			}

		}

	}

?>


<div class="container-fluid single">
	
	<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-7">
			
			<div class="singlepost">
				
				<h4><?php echo $post['title']; ?></h4>
				<div class="data">
					<span class="author"><i class="fa fa-user"></i> <?php echo $post['author']; ?></span>
					<span class="datetime"><i class="fa fa-calendar"></i> <?php echo $post['datetime']; ?></span>

					<span class="comments"><i class="fa fa-comments-o"></i> <?php if(get_post_comments_number(1,$post['id']) > 0) { echo get_post_comments_number(1,$post['id']) . ' Comments';} else echo "No Comments"; ?></span>
				</div>
				<img class="post-image thumbnail" src="admin/uploads/posts/<?php echo $post['image']; ?>">

				<div class="content">
					<p class="lead"><?php echo $post['content']; ?></p>
				</div>

				<span class="category"><i class="fa fa-folder"></i> <?php echo $post['category']; ?></span>
				<?php if(! empty($post['tags'])) { ?>
					<span class="tags"><i class="fa fa-tags"></i> <?php echo $post['tags']; ?></span>
				<?php } ?>

			</div>
			<div class="post-comments">
				<h5><?php echo get_post_comments_number(1,$post['id']);?> Comments</h5>
				<?php 
					foreach (get_post_comments(1, $post['id']) as $comment) { 

						$admin = is_admin($comment['commenter_email']);

						$admin_image = "";
						if(! empty($admin)) {
							$username = $admin['username'];
							$email = $admin['email'];
							if(empty($admin['image'])){
								$admin_image = "user.jpg";
							}else {
								$admin_image = $admin['image'];
							}
						} else {
							$username = $comment['commenter_name'];
							$email = $comment['commenter_email'];
							$admin_image = "user.jpg";
						}

						?>
					
						<div class="comment">	
							<img class="admin-image" width="70" height="70" src="admin/uploads/admins/<?php echo $admin_image;?>">

							<h6 class="username"><?php echo $username; ?></h6>
							<span class="datetime"><?php echo $comment['datetime']; ?></span>
							<p class="comment"><?php echo $comment['comment']; ?></p>
						</div>

					<?php } ?>
			</div>
			<div class="add-comment">			
				<h4>Add Comment</h4>
				<form method="POST" action="singlepost.php?post_id=<?php echo $id; ?>">
					
					<div class="form-group">
						<input type="text" name="username" placeholder="Username" class="form-control" required autocomplete="off">
						<p class="error username-error">Username must be between 8, 20 Characters</p>
					</div>
					<div class="form-group">
						<input type="email" required autocomplete="off" name="email" placeholder="Email" class="form-control">
						<p class="error email-error">Email must be between 11, 100 Characters</p>
					</div>
					<div class="form-group">
						<textarea required placeholder="Your Comment" name="comment" class="form-control" rows="5" cols="10"></textarea>
						<p class="error comment-error">Comment must be between 20, 200 Characters</p>
						<input type="submit" name="addcomment" value="Add Comment" class="btn btn-default">
					</div>
				</form>
			</div>

		</div>

		<div class="col-md">
			
			<div class="sidebar">
				
				<h6 class="side-header">Recent Posts</h6>
				<?php foreach (get_recent('posts', 5) as $post) { ?>
					<div class="recent-post">
						<div class="row">
							<div class="col-sm-3">
								<a href="" class="recent-title"><img style="border-radius: 2px; margin-left: 15px;" height="60" width="60" src="admin/uploads/posts/<?php echo $post['image']; ?>"></a>
							</div>
							<div class="col-sm">
								<a href="" class="recent-title"><h6><?php echo $post['title']; ?></h6></a>	
							</div>
						</div>
					</div>
				<?php } ?>

			</div>
			<div class="sidebar">
				
				<h6 class="side-header"><i class="fa fa-list"></i> Categories</h6>
				<?php foreach (get_recent('categories', 10) as $category) { ?>
					<div class="cat">
						<a href="" class="cat-name"><h6><i class="fa fa-chevron-right"></i> <?php echo $category['name']; ?></h6></a>	
					</div>
				<?php } ?>

			</div>

		</div>

	</div>


</div>




<?php include "inc/footer.php"; ?>