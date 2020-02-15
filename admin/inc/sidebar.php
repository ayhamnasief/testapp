
<div class="sidebar">
	<ul class="list-unstyled">
		<li><a href="index.php" class="<?php echo $dashboard; ?>"><i class="fa fa-home"></i> Dashboard</a></li>
		<li><a href="posts.php" class="<?php echo $posts; ?>"><i class="fa fa-sticky-note"></i> Posts</a></li>
		<li><a href="categories.php" class="<?php echo $cats; ?>"><i class="fa fa-list"></i> Categories</a></li>
		<li><a href="comments.php" class="<?php echo $comments; ?>"><i class="fa fa-comments-o"></i> Comments 
		<?php if(get_post_comments_number(0)) {
				echo "<span style='float: right;' class='badge badge-info'>". get_post_comments_number(0) ."</span>";	
				} ?>
		</a></li>
		<li><a href="admins.php" class="<?php echo $admins; ?>"><i class="fa fa-user-secret"></i> Admins</a></li>
		<li><a href="users.php" class="<?php echo $users; ?>"><i class="fa fa-users"></i> Users</a></li>
		<li><a href="settings.php" class="<?php echo $settings; ?>"><i class="fa fa-gear"></i> Settings</a></li>
		<li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
	</ul>
</div>