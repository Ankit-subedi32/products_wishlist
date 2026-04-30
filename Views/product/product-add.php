<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
}


?>

<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <?php

    include __DIR__ . "/../../Config/connection.php";
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $target_file = '';
        $imgPath = null;
        if (!empty($_FILES["imgPath"]["name"])) {
            $target_dir = "../../assets/image/";
            // upload folder ma image janxa
            $target_file = $target_dir . basename($_FILES["imgPath"]["name"]);  // basename le file ko name matra dinxa 
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $check = getimagesize($_FILES["imgPath"]["tmp_name"]);   // return array like height , width, mime 
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";  // mime chai file type dinxa like image/jpeg , text/html
            }
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["imgPath"]["tmp_name"], $target_file)) {
                    $fileMessage = "The file " . htmlspecialchars(basename($_FILES["imgPath"]["name"])) . " has been uploaded.";
                    $imgPath = "assets/image/" . basename($_FILES["imgPath"]["name"]);
                } else {
                    $fileErr = "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            echo "No file uploaded or upload error.";
        }



        $name = $_POST['name'];
        // $imgPath = $_FILES['imgPath'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $user_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("INSERT INTO products (name, imgPath, description, price, user_id) VALUES (?,?,?,?,?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("sssdi", $name, $imgPath, $description, $price, $user_id);

        if ($stmt->execute()) {
            echo "Product added successfully!";
            header("Location: " . $_SERVER['PHP_SELF']); // for prevention of form submition on page reaload
            exit();

        } else {
            echo "Error inserting product: " . $stmt->error;
        }




    }

    ?>
    <div class="container">

        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="">Name:</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="" aria-describedby="helpId">
                <small id="helpId" class="text-muted">Help text</small>
            </div>
            <div class="form-group">
                <label for="">Image:</label>
                <input type="file" name="imgPath" id="imgPath"><br>
                <small id="helpId" class="text-muted">Help text</small>
            </div>

            <div class="form-group">
                <label for="">Description:</label>
                <input type="text" name="description" id="description" class="form-control" placeholder=""
                    aria-describedby="helpId">
                <small id="helpId" class="text-muted">Help text</small>
            </div>
            <div class="form-group">
                <label for="">Price:</label>
                <input type="text" name="price" id="price" class="form-control" placeholder=""
                    aria-describedby="helpId">
                <small id="helpId" class="text-muted">Help text</small>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success" name="submit">Submit</button>
                <button type="reset" class="btn btn-danger">Reset</button>
            </div>
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>

</html>