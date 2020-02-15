<?php 
	include "inc/functions.php";
	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_POST['deletepost'])) {
			$id = filter_input(INPUT_POST,'id' , FILTER_SANITIZE_NUMBER_INT);
			if(! session_id()){
				session_start();
			}
			if( delete('posts' , $id) ) {
				$_SESSION['success'] = "Post has been Deleted Successfully";
				redirect("posts.php");
			}else {
				$_SESSION['error'] = "Unable to Delete Post";
				redirect("posts.php");
			}
		}
	}
?>