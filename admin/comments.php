<?php $page_title = "Comments | ZBlog"; ?>
<?php include "inc/header.php"; ?>
<?php include "inc/functions.php"; ?>
<?php include "inc/navbar.php"; ?>


<?php $comments = "active"; ?>


<div class="container-fluid">
	<div class="row">
		<?php include "inc/media_sidebar.php"; ?>
		<div class="col-sm-2">
			<?php include "inc/sidebar.php"; ?>
		</div>
		<div class="col-sm">
			
			<div class="approved-comments">

				<?php 
					if( ! session_id() ) {
						session_start();
					}
					if( isset($_SESSION['success']) && ! empty($_SESSION['success'])) {
						echo "<div class='alert alert-success'>";
						echo $_SESSION['success'];
						echo "</div>";
						$_SESSION['success'] = "";
					}
					if( isset($_SESSION['error']) && ! empty($_SESSION['error'])) {
						echo "<div class='alert alert-danger'>";
						echo $_SESSION['error'];
						echo "</div>";
						$_SESSION['error'] = "";
					}
				?>

				<h4>Approved Comments</h4>
				<div class="table-responsive">
					<table class="table table-hover table-striped table-dark">
					  <thead>
					    <tr>
					      <th scope="col">#</th>
					      <th scope="col">Created_at</th>
					      <th scope="col">Username</th>
					      <th scope="col">Comment</th>
					      <th scope="col">Post Title</th>
					      <th scope="col">Actions</th>
					    </tr>
					  </thead>
					  <tbody>

					  	<?php
					  	$number = 0;
					  	foreach(get_all_comments(1) as $comment) { $number++; ?>

					    <tr>
					      <th scope="row"><?php echo $number; ?></th>
					      <td><?php echo $comment['datetime']; ?></td>
					      <td>
					      	<?php 
					      	echo $comment['commenter_name'];
					      	?>
					      </td>
					      <td class="comment">
					      	<?php
					      	if(strlen($comment['comment']) > 100){
					      		echo substr($comment['comment'], 0, 100)." ...";
					      	}else {
					      	 echo $comment['comment'];
					      	}					      
					      	?>		
					      	</td>

					      <td class="post-title"><?php 
					      		$post_id = $comment['post_id'];
					      		$post_title = get_posts($post_id)['title'];
					      		
						      	if(strlen($post_title) > 100){
						      		echo substr($post_title, 0, 100)." ...";
						      	}else {
						      	 echo $post_title;
						      	}
					      	?>
					      </td>

					      <td class="action-links">
					      	<a class="btn btn-primary btn-sm" href="comment.php?id=<?php echo $comment['id']; ?>">Edit</a>
					      	<form onsubmit="return confirm('Are You Sure?');" action="approve_delete_comment.php" method="POST">
					      		<input type="hidden" name="id" value="<?php echo $comment['id']; ?>">
					      		<input class="btn btn-danger btn-sm" type="submit" value="Delete" name="deletecomment">
					      	</form>
					      </td>
					    </tr>

						<?php } ?>

					  </tbody>
					</table>
					<a class="btn btn-info" style="float: right;" href="comment.php">Add New Comment</a>
				</div>
			</div>


			<div class="unapproved-comments">
				<h4>Unapproved Comments</h4>
				<div class="table-responsive">
					<table class="table table-hover table-striped table-dark">
					  <thead>
					    <tr>
					      <th scope="col">#</th>
					      <th scope="col">Created_at</th>
					      <th scope="col">Username</th>
					      <th scope="col">Comment</th>
					      <th scope="col">Post Title</th>
					      <th scope="col">Actions</th>
					    </tr>
					  </thead>
					  <tbody>

					  	<?php
					  	$number = 0;
					  	foreach(get_all_comments(0) as $comment) { $number++; ?>

					    <tr>
					      <th scope="row"><?php echo $number; ?></th>
					      <td><?php echo $comment['datetime']; ?></td>
					      <td>
					      	<?php 
					      	echo $comment['commenter_name'];
					      	?>
					      </td>
					      <td class="comment">
					      	<?php
					      	if(strlen($comment['comment']) > 100){
					      		echo substr($comment['comment'], 0, 100)." ...";
					      	}else {
					      	 echo $comment['comment'];
					      	}					      
					      	?>		
					      	</td>

					      <td class="post-title"><?php 
					      		$post_id = $comment['post_id'];
					      		$post_title = get_posts($post_id)['title'];
					      		
						      	if(strlen($post_title) > 100){
						      		echo substr($post_title, 0, 100)." ...";
						      	}else {
						      	 echo $post_title;
						      	}
					      	?>
					      </td>

					      <td class="action-links">
					      	<a class="btn btn-primary btn-sm" href="comment.php?id=<?php echo $comment['id']; ?>">Edit</a>
					      	<form onsubmit="return confirm('Are You Sure?');" action="approve_delete_comment.php" method="POST">
					      		<input type="hidden" name="id" value="<?php echo $comment['id']; ?>">
					      		<input class="btn btn-danger btn-sm" type="submit" value="Delete" name="deletecomment">
					      		<input class="btn btn-info btn-sm" type="submit" value="Approve" name="approvecomment">
					      	</form>
					      </td>
					    </tr>

						<?php } ?>

					  </tbody>
					</table>
				</div>
			</div>

		</div>
	</div>
</div>

<?php include "inc/footer.php"; ?>