<?php 
	include "inc/functions.php";
	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_POST['deletecategory'])) {
			$id = filter_input(INPUT_POST,'id' , FILTER_SANITIZE_NUMBER_INT);
			if(! session_id()){
				session_start();
			}
			if( delete('categories' , $id) ) {
				$_SESSION['success'] = "Category has been Deleted Successfully";
				redirect("categories.php");
			}else {
				$_SESSION['error'] = "Unable to Delete Category";
				redirect("categories.php");
			}
		}
	}
?>