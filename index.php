<?php include "inc/header.php"; ?>
<?php 
	$about = "";
	$home = "active";
	$contact = "";
?>
<?php include "inc/navbar.php"; ?>
<?php include "inc/functions.php"; ?>


<?php 

	$posts_number = get_number("posts"); // 100

	$pagination = ceil($posts_number / $home_posts_number);


	$posts = "";
	$page = 1;
	if($_SERVER['REQUEST_METHOD'] === "GET") {

		if(isset($_GET['s'])) {
			$s = filter_input(INPUT_GET,'s', FILTER_SANITIZE_STRING);
			$posts = search_posts($s);
			if(empty($posts)) { ?>
				<h4 style="margin: 50px 0 0 50px;" >Sorry, We Can't Find Your Request.</h4>
			<?php }
		}else if(isset($_GET['category'])){
			$cat_name = filter_input(INPUT_GET,'category', FILTER_SANITIZE_STRING);
			$posts = get_posts_by_category($cat_name); 
			if(empty($posts)) { ?>
				<h4 style="margin: 50px 0 0 50px;" >Sorry, We Can't Find Your Request.</h4>
			<?php }
		}else if(isset($_GET['author'])){
			$author = filter_input(INPUT_GET,'author', FILTER_SANITIZE_STRING);
			$posts = get_posts_by_author($author); 
			if(empty($posts)) { ?>
				<h4 style="margin: 50px 0 0 50px;" >Sorry, We Can't Find Your Request.</h4>
			<?php }
		}
		else if(isset($_GET['page'])) {
			$page = filter_input(INPUT_GET,'page',FILTER_SANITIZE_NUMBER_INT);

			if($page == 0){
				$from = 0;
				$to = $home_posts_number;
			}else {
				$from = ($page * $home_posts_number) - $home_posts_number;
				$to = $from + $home_posts_number;
			}
			$posts = get_home_posts($from, $to);
		}
		else {
			$from = 0;
			$to = $home_posts_number;
			$posts = get_home_posts($from, $to);

		}


	}else {
		$from = 0;
		$to = 9;
		$posts = get_home_posts($from, $to);
	}


?>



<div class="container-fluid">
	
	<div class="row">
		
		<div class="col-md-8">
			
			<div class="posts">
				
				<div class="row">
					
					<?php foreach ($posts as $post) { ?>
						
						<div class="col-md-4">
							<div class="post">
								<?php if(! empty($post['image'])) { ?>
									<a href="singlepost.php?post_id=<?php echo $post['id']; ?>"><img style="border-radius: 2px;" src="admin/uploads/posts/<?php echo $post['image']; ?>"></a>
								<?php } ?>
								<a class="title" href="singlepost.php?post_id=<?php echo $post['id']; ?>"><h6><?php echo $post['title']; ?></h6></a>

								<?php if(! empty($post['excerpt'])) {
									$excerpt = "";
									if(strlen($post['excerpt']) > 100){
										$excerpt = substr($post['excerpt'],0, 100) . ' ...';
										$excerpt .= " <br><a href='' class='read-more btn btn-danger btn-sm'>Read More</a>";
									}else {
										$excerpt = $post['excerpt'];
										$excerpt .= " <br><a href='singlepost.php?post_id={$post['id']}' class=' read-more btn btn-danger btn-sm'>Read More</a>";
									}
									?>
									<p class="excerpt"><?php echo $excerpt; ?></p>
								<?php }else {
									$content = substr($post['content'],0, 100) . ' ...';
									$content .= " <br><a href='' class=' read-more btn btn-danger btn-sm'>Read More</a>"; ?>
									<p class="excerpt"><?php echo $content; ?></p>
								<?php } ?>

								
								
								
							</div>
						</div>

					<?php } ?>

				</div>

			</div>

			<div class="related-posts">
				
				<div class="author-image">
					
				</div>

			</div>



			<nav aria-label="...">
			  <ul class="pagination">
			    <li class="page-item  <?php if(number_format($page) == 1) echo 'disabled'; ?>">
			      <a class="page-link" href="index.php?page=<?php echo number_format($page - 1); ?>" tabindex="-1" aria-disabled="true">Previous</a>
			    </li>

			    <?php 
			    for($i = 1; $i <= $pagination; $i++) { ?>
					<li class="page-item <?php if($page == $i) echo 'active' ?>"><a class="page-link" href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
			   <?php }
			    ?>
			    <li class="page-item <?php if(number_format($page + 1) == $i) echo 'disabled'; ?>">
			      <a class="page-link" href="index.php?page=<?php echo number_format($page + 1); ?>">Next</a>
			    </li>
			  </ul>
			</nav>

		</div>
		<div class="col-md">
			
			<div class="sidebar">
				
				<h6 class="side-header">Recent Posts</h6>
				<?php foreach (get_recent('posts', 5) as $post) { ?>
					<div class="recent-post">
						<div class="row">
							<div class="col-sm-3">
								<a href="singlepost.php?post_id=<?php echo $post['id']; ?>" class="recent-title"><img style="border-radius: 2px; margin-left: 15px;" height="60" width="60" src="admin/uploads/posts/<?php echo $post['image']; ?>"></a>
							</div>
							<div class="col-sm">
								<a href="singlepost.php?post_id=<?php echo $post['id']; ?>" class="recent-title"><h6><?php echo $post['title']; ?></h6></a>	
							</div>
						</div>
					</div>
				<?php } ?>

			</div>
			<div class="sidebar">
				
				<h6 class="side-header"><i class="fa fa-list"></i> Categories</h6>
				<?php foreach (get_recent('categories', 10) as $category) { ?>
					<div class="cat">
						<a href="index.php?category=<?php echo $category['name']; ?>" class="cat-name"><h6><i class="fa fa-chevron-right"></i> <?php echo $category['name']; ?></h6></a>	
					</div>
				<?php } ?>

			</div>

		</div>


	</div>

</div>



<?php include "inc/footer.php"; ?>