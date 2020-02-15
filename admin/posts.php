<?php $page_title = "Posts | ZBlog"; ?>
<?php include "inc/header.php"; ?>
<?php include "inc/functions.php"; ?>

<?php include "inc/navbar.php"; ?>
<?php $posts = "active"; ?>
<div class="container-fluid">
	<div class="row">
		<?php include "inc/media_sidebar.php"; ?>
		<div class="col-sm-2">
			<?php include "inc/sidebar.php"; ?>
		</div>
		<div class="col-sm">
			
			<div class="posts">

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

				<h4>Posts</h4>
				<div class="table-responsive">
					<table class="table table-hover table-striped table-dark">
					  <thead>
					    <tr>
					      <th scope="col">#</th>
					      <th scope="col">datetime</th>
					      <th scope="col">Title</th>
					      <th scope="col">Content</th>
					      <th scope="col">Image</th>
					      <th scope="col">Comments</th>
					      <th scope="col">Author</th>
					      <th scope="col">Actions</th>
					    </tr>
					  </thead>
					  <tbody>

					  	<?php
					  	$number = 0;
					  	foreach(get_posts() as $post) { $number++; ?>

					    <tr>
					      <th scope="row"><?php echo $number; ?></th>
					      <td><?php echo $post['datetime']; ?></td>
					      <td class="title">
					      	<?php 
					      	if(strlen($post['title']) > 100 ){
					      		echo substr($post['title'], 0,100) . '...';
					      	}else {
					      		echo $post['title'];
					      	}
					      	?>
					      	
					      </td>
					      <td class="post-content">
					      	<?php 
					      	if(strlen($post['content']) > 100 ){
					      		echo substr($post['content'], 0,100) . '...';
					      	}else {
					      		echo $post['content'];
					      	}
					      	?>
					      	
					      </td>

					      <td>
					      	<?php if(! empty($post['image'])) { ?>
					      	<img class="" alt="Post Banner" width="100" src="uploads/posts/<?php echo $post['image']; ?>">	
					      <?php  } else {
					      	echo "No Image";
					      }
					      ?>
					      </td>
					      <td>
					      	<?php if(get_post_comments_number(1,$post['id'])) {
					      		echo "<span class='badge badge-success'>". get_post_comments_number(1,$post['id']) ."</span>";	
					      	} ?>
					      	<?php if(get_post_comments_number(0,$post['id'])) {
					      		echo "<span style='float: right;' class='badge badge-warning'>". get_post_comments_number(0,$post['id']) ."</span>";	
					      	} ?>
					      </td>
					      <td><?php echo $post['author']; ?></td>


					      <td class="action-links">
					      	<a class="btn btn-primary btn-sm" href="post.php?id=<?php echo $post['id']; ?>">Edit</a>
					      	<form onsubmit="return confirm('Are You Sure?');" action="deletepost.php" method="POST">
					      		<input type="hidden" name="id" value="<?php echo $post['id']; ?>">
					      		<input class="btn btn-danger btn-sm" type="submit" value="Delete" name="deletepost">
					      	</form>
					      </td>


					    </tr>

						<?php } ?>

					  </tbody>
					</table>
					<a class="btn btn-info" style="float: right;" href="post.php">Add New Post</a>
				</div>
			</div>

		</div>
	</div>
</div>

<?php include "inc/footer.php"; ?>