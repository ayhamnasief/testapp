<?php $page_title = "Test"; ?>
<?php include "inc/header.php"; ?>
<?php include "inc/functions.php"; ?>


<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['addUser_submit'])) {
        $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
        $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
        $cat = filter_input(INPUT_POST, 'cat', FILTER_SANITIZE_NUMBER_INT);
        insert_emp($fname, $lname, $cat);
    }

    $image = $_FILES['image'];

    $img_name = $image['name'];
    $img_tmp_name = $image['tmp_name'];
    $img_size = $image['size'];

    if (!empty($img_name)) {
        $new_path = "uploads/posts/" . $img_name;
        move_uploaded_file($img_tmp_name, $new_path);
        echo $new_path;
    } else {
        echo "No Image found";
    }
}
?>
<div class="container-fluid">
    <form method="POST" action="test.php" enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm">
                <input class="form-control" type="text" placeholder="First name" name="fname">
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <input class="form-control" type="text" placeholder="Last name" name="lname">
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <select class="form-control" name="cat">
                    <?php
                    foreach (get_cats() as $value) {
                        echo "<option value='{$value['id']}'>";
                        echo "{$value['name']}";
                        echo "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <input type="file" name="image" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <input type="submit" name="addUser_submit" value="Add" class="btn btn-primary" style="float:right">
            </div>
        </div>

    </form>
</div>