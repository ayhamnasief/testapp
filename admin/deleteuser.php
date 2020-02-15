<?php 
	include "inc/functions.php";
	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_POST['deleteuser'])) {
			$id = filter_input(INPUT_POST,'id' , FILTER_SANITIZE_NUMBER_INT);
			if(! session_id()){
				session_start();
			}
			if( delete('users' , $id) ) {
				$_SESSION['success'] = "User has been Deleted Successfully";
				redirect("users.php");
			}else {
				$_SESSION['error'] = "Unable to Delete User";
				redirect("users.php");
			}
		}
	}
?>