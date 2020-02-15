
<?php $page_title = "Dashboard | ZBlog"; ?>
<?php include "inc/header.php"; ?>
<?php include "inc/functions.php"; ?>
<?php include "inc/navbar.php"; ?>

<?php $dashboard = "active"; ?>

<div class="container-fluid">
	<div class="row">
		<?php include "inc/media_sidebar.php"; ?>
		<div class="col-sm-2">
			<?php include "inc/sidebar.php"; ?>
		</div>
		<div class="col-sm">
			<div class="dashboard">
				<h4>Dashboard</h4>
				<div class="row">
					<div class="col-sm-12">
						<div class="counts text-center">
							<div class="row">
								<div class="col-md-3 col-sm-6">
									<span class=""><span class="num"><?php echo get_number('posts'); ?></span></br> Posts</span>
								</div>
								<div class="col-md-3 col-sm-6">
									<span><span class="num"><?php echo get_number('categories'); ?></span></br> Categories</span>
								</div>
								<div class="col-md-3 col-sm-6">
									<span><span class="num"><?php echo get_number('comments'); ?></span></br> Comments</span>
								</div>
								<div class="col-md col-sm">
									<span><span class="num">2232</span></br> Users</span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="hottest-posts">
						<h5>Hottest Posts</h5>
							<?php foreach (get_hottest_posts(3) as $post) { ?>
								<a href="post.php?id=<?php echo $post['id']; ?>"><h6><?php echo $post['title']; ?></h6></a>
							<?php } ?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="recent-posts">
						<h5>Recent Posts</h5>
							<?php foreach (get_recent('posts', 3) as $post) { ?>
								<a href="post.php?id=<?php echo $post['id']; ?>"><h6><?php echo $post['title']; ?></h6></a>
							<?php } ?>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="new-links">
							<a href="post.php"><i class="fa fa-pencil"></i> Add New Post</a>
							<a href="category.php"><i class="fa fa-list"></i> Add New Category</a>
							<a href="comment.php"><i class="fa fa-comment"></i> Add New Comment</a>
							<a href="admin.php"><i class="fa fa-user-secret"></i> Add New Admin</a>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="recent-cats">
						<h5>Recent Categories</h5>
							<?php foreach (get_recent('categories',3) as $category) { ?>
								<a href="categories.php?id=<?php echo $category['id']; ?>"><h6><?php echo $category['name']; ?></h6></a>
							<?php } ?>
						</div>
					</div>
				</div>	
			</div>
		</div>
	</div>
</div>

<?php include "inc/footer.php"; ?>