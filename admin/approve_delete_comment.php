<?php 
	include "inc/functions.php";
	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_POST['deletecomment'])) {
			$id = filter_input(INPUT_POST,'id' , FILTER_SANITIZE_NUMBER_INT);
			if(! session_id()){
				session_start();
			}
			if( delete('comments' , $id) ) {
				$_SESSION['success'] = "Comment has been Deleted Successfully";
				redirect("comments.php");
			}else {
				$_SESSION['error'] = "Unable to Delete Comment";
				redirect("comments.php");
			}
		}else {
		
			if(isset($_POST['approvecomment'])){

				$id = filter_input(INPUT_POST,'id' , FILTER_SANITIZE_NUMBER_INT);
				if(! session_id()){
					session_start();
				}
				if(approve($id) ) {
					$_SESSION['success'] = "Comment has been Approved Successfully";
					redirect("comments.php");
				}else {
					$_SESSION['error'] = "Unable to Approve Comment";
					redirect("comments.php");
				}

			}

		}
	}
?>