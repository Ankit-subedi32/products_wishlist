<?php
include __DIR__ . "/../../Config/connection.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}
if($_SESSION['role'] == 'user'){
    header("Location: ../landing/index.php");
}


$user_id = $_SESSION['user_id'];

// GET product data
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($_SESSION['role'] == 'superadmin') {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
        $stmt->bind_param("i", $id);
    } else {

        $stmt = $conn->prepare("SELECT * FROM products WHERE id=? AND user_id=?");
        $stmt->bind_param("ii", $id, $user_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
}

// UPDATE
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // keep old image by default
    $imgPath = $_POST['old_img'];

    // if new image uploaded
    if (!empty($_FILES['imgPath']['name'])) {
        $target_dir = "../../assets/image/";
        $target_file = $target_dir . basename($_FILES["imgPath"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["imgPath"]["tmp_name"]);   // return array like height , width, mime 
        if ($check === false) {
            $fileErr = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["imgPath"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "<div class='alert alert-danger'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";

            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        }
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["imgPath"]["tmp_name"], $target_file)) {

                $imgPath = "assets/image/" . $filename;
            } else {
                $fileErr = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "No file uploaded or upload error.";
    }
    if ($_SESSION['role'] == 'superadmin') {
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, imgPath=? WHERE id=? ");
        $stmt->bind_param("ssdsi", $name, $description, $price, $imgPath, $id);
    } else {
        // update querye
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, imgPath=? WHERE id=? AND user_id=?");
        $stmt->bind_param("ssdsii", $name, $description, $price, $imgPath, $id, $user_id);
    }
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating product";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-4">
        <h3>Edit Product</h3>

        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $row['id']; ?>">
            <input type="hidden" name="old_img" value="<?= $row['imgPath']; ?>">
            <!-- hidden garyara chai id ra old image lai use  garyaxam id na huni vaya kun ko edit garne taha hidaina  aane old_img chai purano image ko laghi yedi naya image upload garyana vane   -->

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="<?= $row['name']; ?>">
            </div>

            <div class="form-group">
                <label>Current Image</label><br>
                <img src="../../<?= $row['imgPath']; ?>" width="120"><br><br>
                <input type="file" name="imgPath" class="form-control">
            </div>

            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description" class="form-control" value="<?= $row['description']; ?>">
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="text" name="price" class="form-control" value="<?= $row['price']; ?>">
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="index.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

</body>

</html>