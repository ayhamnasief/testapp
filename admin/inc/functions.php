<?php

/* Categoryu functions */

function get_categories($id = "")
{
	include "connect.php";
	$sql = "";
	if (empty($id)) {
		$sql = "SELECT * FROM categories";
	} else {
		$sql = "SELECT * FROM categories WHERE id = ? ";
	}

	try {
		if (empty($id)) {
			$result = $con->query($sql);
			return $result;
		} else {
			$result = $con->prepare($sql);
			$result->bindValue(1, $id, PDO::PARAM_INT);
			$result->execute();
			return $result->fetch(PDO::FETCH_ASSOC);
		}
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return array();
	}
}

function insert_category($datetime, $name, $creater_name)
{
	$fields = array($datetime, $name, $creater_name);
	include "connect.php";
	$sql = "INSERT INTO categories (datetime, name, creater_name) VALUES (?,?,?)";

	try {
		$result = $con->prepare($sql);
		for ($i = 1; $i <= 3; $i++) {
			$result->bindValue($i, $fields[$i - 1], PDO::PARAM_STR);
		}
		return $result->execute();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}

function update_category($name, $id)
{
	include "connect.php";
	$sql = "UPDATE categories SET name = ? WHERE id = ?";

	try {
		$result = $con->prepare($sql);

		$result->bindValue(1, $name, PDO::PARAM_STR);
		$result->bindValue(2, $id, PDO::PARAM_INT);

		return $result->execute();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}
/* Post Functions  */

function insert_post($datetime, $title, $content, $author, $excerpt, $image, $category, $tags)
{
	$fields = array($datetime, $title, $content, $author, $excerpt, $image, $category, $tags);
	include "connect.php";
	$sql = "INSERT INTO posts (datetime, title, content, author, excerpt, image, category, tags) VALUES (?,?,?,?,?,?,?,?) ";

	try {
		$result = $con->prepare($sql);
		for ($i = 1; $i <= 8; $i++) {
			$result->bindValue($i, $fields[$i - 1], PDO::PARAM_STR);
		}
		return $result->execute();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}


function get_recent($table, $limit)
{
	include "connect.php";
	$sql = "SELECT * FROM $table ORDER BY datetime DESC LIMIT $limit";
	try {
		$result = $con->prepare($sql);
		$result->execute();
		return $result->fetchAll(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return array();
	}
}

function get_posts($id = "")
{
	include "connect.php";
	$sql = "";
	if (empty($id)) {
		$sql = "SELECT * FROM posts ORDER BY datetime DESC";
	} else {
		$sql = "SELECT * FROM posts WHERE id = ? ";
	}

	try {
		if (!empty($id)) {
			$result = $con->prepare($sql);
			$result->bindValue(1, $id, PDO::PARAM_INT);
			$result->execute();
			return $result->fetch(PDO::FETCH_ASSOC);
		} else {
			$result = $con->query($sql);
			return $result;
		}
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		if (empty($id)) {
			return array();
		} else {
			return false;
		}
	}
}


function delete($table, $id)
{
	include "connect.php";
	$sql = "DELETE FROM $table WHERE id = ? ";
	try {
		$result = $con->prepare($sql);
		$result->bindValue(1, $id, PDO::PARAM_INT);

		return $result->execute();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}

function update_post($title, $content, $excerpt, $image = "", $category, $tags, $id)
{
	$fields = array($title, $content, $excerpt, $category, $tags);
	include "connect.php";
	$sql = "";
	if (empty($image)) {
		$sql = "UPDATE posts SET title = ?, content = ?, excerpt = ?, category = ?, tags = ? WHERE id = ?";
	} else {
		$sql = "UPDATE posts SET title = ?, content = ?, excerpt = ?, category = ?, tags = ?, image = ? WHERE id = ?";
	}
	try {

		$result = $con->prepare($sql);
		for ($i = 1; $i <= 5; $i++) {
			$result->bindValue($i, $fields[$i - 1], PDO::PARAM_STR);
		}

		if (!empty($image)) {
			$result->bindValue(6, $image, PDO::PARAM_STR);
			$result->bindValue(7, $id, PDO::PARAM_INT);
		} else {
			$result->bindValue(6, $id, PDO::PARAM_INT);
		}
		return $result->execute();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}


// Admin Functions
function insert_admin($datetime, $username, $email, $password, $roletype, $created_by, $img_name)
{
	$fields = array($datetime, $username, $email, $password, $roletype, $created_by, $img_name);
	include "connect.php";
	$sql = "INSERT INTO admins (datetime, username, email, password, role_type, created_by, image) VALUES (?,?,?,?,?,?,?) ";

	try {
		$result = $con->prepare($sql);
		for ($i = 1; $i <= 7; $i++) {
			$result->bindValue($i, $fields[$i - 1], PDO::PARAM_STR);
		}
		return $result->execute();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}

function get_admins($id = "")
{
	include "connect.php";
	$sql = "";
	if (empty($id)) {
		$sql = "SELECT * FROM admins ORDER BY datetime DESC";
	} else {
		$sql = "SELECT * FROM admins WHERE id = ? ";
	}
	try {

		if (!empty($id)) {
			$result = $con->prepare($sql);
			$result->bindValue(1, $id, PDO::PARAM_INT);
			$result->execute();
			return $result->fetch(PDO::FETCH_ASSOC);
		} else {
			$result = $con->query($sql);
			return $result;
		}
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return array();
	}
}

function update_admin($username, $roletype, $img_name, $id)
{

	$fields = array($username, $roletype, $img_name);
	include "connect.php";
	$sql = "";
	if (empty($img_name)) {
		$sql = "UPDATE admins SET username = ?, role_type = ? WHERE id = ?";
	} else {
		$sql = "UPDATE admins SET username = ?, role_type = ?, image = ? WHERE id = ?";
	}
	try {

		$result = $con->prepare($sql);
		for ($i = 1; $i <= 2; $i++) {
			$result->bindValue($i, $fields[$i - 1], PDO::PARAM_STR);
		}

		if (!empty($img_name)) {
			$result->bindValue(3, $img_name, PDO::PARAM_STR);
			$result->bindValue(4, $id, PDO::PARAM_INT);
		} else {
			$result->bindValue(3, $id, PDO::PARAM_INT);
		}
		return $result->execute();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}


function is_admin($email)
{

	include "connect.php";
	$sql = "SELECT * FROM admins WHERE email = ? ";
	try {

		$result = $con->prepare($sql);
		$result->bindValue(1, $email, PDO::PARAM_STR);
		$result->execute();
		return $result->fetch(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}

function update_reset_password_code($email)
{

	include "connect.php";
	$newcode = rand(10000, 99999);
	$sql = "UPDATE admins SET reset_password_code = $newcode WHERE email = ? ";
	try {
		$result = $con->prepare($sql);
		$result->bindValue(1, $email, PDO::PARAM_STR);
		return $result->execute();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}


// User Function

function get_users($id = "")
{
	include "connect.php";
	$sql = "";
	if (empty($id)) {
		$sql = "SELECT * FROM users ORDER BY id DESC";
	} else {
		$sql = "SELECT * FROM users WHERE id = ? ";
	}
	try {

		if (!empty($id)) {
			$result = $con->prepare($sql);
			$result->bindValue(1, $id, PDO::PARAM_INT);
			$result->execute();
			return $result->fetch(PDO::FETCH_ASSOC);
		} else {
			$result = $con->query($sql);
			return $result;
		}
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return array();
	}
}


/* Comment Functions  */

function get_all_comments($approve)
{
	include "connect.php";
	$sql = "";
	if ($approve === 1) {
		$sql = "SELECT * FROM comments WHERE approve = 1";
	} else {
		$sql = "SELECT * FROM comments WHERE approve = 0 ";
	}
	try {
		$result = $con->prepare($sql);
		$result->execute();
		return $result->fetchAll(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return array();
	}
}

function get_comments($id = "")
{
	include "connect.php";
	$sql = "";
	if (empty($id)) {
		$sql = "SELECT * FROM comments ORDER BY datetime DESC";
	} else {
		$sql = "SELECT * FROM comments WHERE id = ? ";
	}

	try {
		if (!empty($id)) {
			$result = $con->prepare($sql);
			$result->bindValue(1, $id, PDO::PARAM_INT);
			$result->execute();

			return $result->fetch(PDO::FETCH_ASSOC);
		} else {
			$result = $con->query($sql);
			return $result;
		}
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return array();
	}
}

function update_comment($comment, $post_id, $id)
{
	include "connect.php";
	$sql = "UPDATE comments SET comment = ?, post_id = ? WHERE id = ?";
	try {
		$result = $con->prepare($sql);
		$result->bindValue(1, $comment, PDO::PARAM_STR);
		$result->bindValue(2, $post_id, PDO::PARAM_INT);
		$result->bindValue(3, $id, PDO::PARAM_INT);
		return $result->execute();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}

function insert_comment($datetime, $username, $email, $comment_comment, $post_id)
{
	$fields = array($datetime, $username, $email, $comment_comment);
	include "connect.php";
	$sql = "INSERT INTO comments (datetime, commenter_name, commenter_email, comment, post_id) VALUES (?,?,?,?,?) ";

	try {
		$result = $con->prepare($sql);
		for ($i = 1; $i <= 4; $i++) {
			$result->bindValue($i, $fields[$i - 1], PDO::PARAM_STR);
		}
		$result->bindValue(5, $post_id, PDO::PARAM_INT);
		return $result->execute();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}

function approve($id)
{

	include "connect.php";
	$sql = "UPDATE comments SET approve = 1 WHERE id = ? ";

	try {

		$result = $con->prepare($sql);
		$result->bindValue(1, $id, PDO::PARAM_INT);
		return $result->execute();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}

function get_post_comments_number($approve, $id = "")
{
	include "connect.php";
	$sql = "";
	if (empty($id)) {
		$sql = "SELECT * FROM comments WHERE approve = $approve";
	} else {
		$sql = "SELECT * FROM comments WHERE approve = $approve AND post_id = $id ";
	}
	try {
		$result = $con->prepare($sql);
		$result->execute();
		return $result->rowCount();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return 0;
	}
}

function get_post_comments($approve, $id = "")
{
	include "connect.php";
	$sql = "";
	if (empty($id)) {
		$sql = "SELECT * FROM comments WHERE approve = $approve";
	} else {
		$sql = "SELECT * FROM comments WHERE approve = $approve AND post_id = $id ";
	}
	try {
		$result = $con->prepare($sql);
		$result->execute();
		return $result->fetchAll(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return 0;
	}
}

/* Setting Functions */


function get_settings()
{
	include "connect.php";
	$sql = "SELECT * FROM settings";

	try {
		$result = $con->query($sql);
		return $result;
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return array();
	}
}

function update_general_settings($name, $tagline, $image = "")
{
	include "connect.php";
	$sql = "";
	if (empty($image)) {
		$sql = "UPDATE settings SET name = ?, tagline = ? ";
	} else {
		$sql = "UPDATE settings SET name = ?, tagline = ?, logo = ? ";
	}
	try {
		$result = $con->prepare($sql);
		$result->bindValue(1, $name, PDO::PARAM_STR);
		$result->bindValue(2, $tagline, PDO::PARAM_STR);

		if (!empty($image)) {
			$result->bindValue(3, $image, PDO::PARAM_STR);
		}
		return $result->execute();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}


function update_posts_settings($hpn, $posts_order, $rpn, $relatedpn)
{
	include "connect.php";
	$sql = "";
	$sql = "UPDATE settings SET home_posts_number = ?, posts_order = ?, recent_posts_number = ?, related_posts_number = ? ";

	try {
		$result = $con->prepare($sql);
		$result->bindValue(1, $hpn, PDO::PARAM_INT);
		$result->bindValue(2, $posts_order, PDO::PARAM_STR);
		$result->bindValue(3, $rpn, PDO::PARAM_INT);
		$result->bindValue(4, $relatedpn, PDO::PARAM_INT);

		return $result->execute();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}

/* Dashboard Functions */

function get_number($table)
{
	include "connect.php";
	$sql = "SELECT * FROM $table";
	try {
		$result = $con->prepare($sql);
		$result->execute();
		return $result->rowCount();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return array();
	}
}

function get_hottest_posts($limit = "")
{
	include "connect.php";
	$sql = "SELECT p.id,p.title,c.postcount FROM posts AS p
	INNER JOIN (
		SELECT post_id, count(*) AS postcount FROM comments GROUP BY post_id
	) AS c
	ON p.id = c.post_id 
	ORDER BY c.postcount DESC LIMIT $limit";
	try {
		$result = $con->prepare($sql);
		$result->execute();
		return $result->fetchAll(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return array();
	}
}


/*  Profile Functions */

function update_admin_profile($username, $email, $image, $id)
{
	$fields = array($username, $email);
	include "connect.php";
	$sql = "";
	if (empty($image)) {
		$sql = "UPDATE admins SET username = ?, email = ? WHERE id = ?";
	} else {
		$sql = "UPDATE admins SET username = ?, email = ?, image = ? WHERE id = ?";
	}
	try {

		$result = $con->prepare($sql);
		for ($i = 1; $i <= 2; $i++) {
			$result->bindValue($i, $fields[$i - 1], PDO::PARAM_STR);
		}

		if (!empty($image)) {
			$result->bindValue(3, $image, PDO::PARAM_STR);
			$result->bindValue(4, $id, PDO::PARAM_INT);
		} else {
			$result->bindValue(3, $id, PDO::PARAM_INT);
		}
		return $result->execute();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}

function update_password($password, $id)
{
	include "connect.php";
	$sql = "UPDATE admins SET password = ? WHERE id = ? ";
	try {
		$result = $con->prepare($sql);
		$result->bindValue(1, $password, PDO::PARAM_STR);
		$result->bindValue(2, $id, PDO::PARAM_STR);
		return $result->execute();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}





function redirect($location)
{
	header("Location: $location");
	exit;
}


function get_cats($id = "")
{
	include "connect.php";
	$sql = "SELECT * FROM cat";

	try {
		$result = $con->query($sql);
		return $result;
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return array();
	}
}

function insert_emp($fname, $lname, $cat_id)
{
	include "connect.php";
	$sql = "INSERT INTO emp_info (fname, lname, cat_id) VALUES (?,?,?) ";

	try {
		$result = $con->prepare($sql);
			$result->bindValue(1, $fname, PDO::PARAM_STR);
			$result->bindValue(2, $lname, PDO::PARAM_STR);
			$result->bindValue(3, $cat_id, PDO::PARAM_INT);
		return $result->execute();
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
		return false;
	}
}
