<?php

$image_info = $_FILES['image'];
$image_name = $image_info['name'];

echo $image_name;

?>


<form action="test.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="image">
    <input value="Add Post" type="submit" name="addpost">
</form>