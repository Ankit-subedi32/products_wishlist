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

    $imgPath = null;
    function test($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $target_file = '';

        if (empty($_POST["name"])) {
            $nameErr = "Cannot be Empty";

        } else {
            $name = test($_POST["name"]);
            if (!preg_match("/^[A-Za-z]+(?: [A-Za-z]+)*$/", $name)) {
                $nameErr = "Invalid Name Fomat";
            }


        }
        if (empty($_POST["description"])) {
            $descErr = "Cannot be Empty";

        } else {
            $description = test($_POST["description"]);
            if (!preg_match("/^[A-Za-z0-9 .,!?'-]+$/", $description)) {
                $descErr = "Invalid Description Format";
            }

        }
        if (empty($_POST["price"])) {
            $descErr = "Cannot be Empty";

        } else {
            $price = test($_POST["price"]);
            if (!preg_match("/^[0-9]+(\.[0-9]{1,2})?$/", $price)) {
                $priceErr = "Invalid Price Format";
            }

        }





        if (!empty($_FILES["imgPath"]["name"])) {
            $target_dir = "../../assets/image/";
            // upload folder ma image janxa
            $target_file = $target_dir . basename($_FILES["imgPath"]["name"]);  // basename le file ko name matra dinxa 
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

            // Allow certain file formats
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
                    $fileMessage = "The file " . htmlspecialchars(basename($_FILES["imgPath"]["name"])) . " has been uploaded.";
                    $imgPath = "assets/image/" . basename($_FILES["imgPath"]["name"]);
                } else {
                    $fileErr = "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            echo "No file uploaded or upload error.";
        }



        if (!empty($name) && !empty($description) && !empty($imgPath) && !empty($price)) {


            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $user_id = $_SESSION['user_id'];
            $checkSql = "SELECT id FROM products WHERE name = ? AND imgPath = ? AND description = ? AND price =? AND user_id =?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("sssdi", $name, $imgPath, $description, $price, $user_id);
            $checkStmt->execute();
            $checkStmt->store_result();   // pull rows(memory ma save gar aaila paxi check garxu vaneko jasto)
            if ($checkStmt->num_rows > 0) {
                //already exist xa file 
                echo "<div class='alert alert-danger'>Error: A news item with this name already exists</div>";
            } else {
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
                $stmt->close();
            }
            $checkStmt->close();
            $conn->close();
        }



    }

    ?>
    <div class="container">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="">Product Name:</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="" aria-describedby="helpId">
                <small class="text-danger" id="helpId" class="text-muted"><?php if (!empty($nameErr))
                    echo $nameErr; ?></small>
            </div>
              <?php

echo "<pre>";
print_r($_SESSION['role'] ?? 'ROLE NOT SET');
echo "</pre>";
?>
            <div class="form-group">
                <label for="">Image:</label>
                <input type="file" name="imgPath" id="imgPath"><br>
                <small class="text-danger" id="helpId" class="text-muted"><?php if (!empty($fileErr))
                    echo $fileErr; ?></small>
            </div>

            <div class="form-group">
                <label for="">Description:</label>
                <input type="text" name="description" id="description" class="form-control" placeholder=""
                    aria-describedby="helpId">
                <small class="text-danger" id="helpId" class="text-muted"><?php if (!empty($descErr))
                    echo $descErr; ?></small>
            </div>
            <div class="form-group">
                <label for="">Price:</label>
                <input type="text" name="price" id="price" class="form-control" placeholder=""
                    aria-describedby="helpId">
                <small class="text-danger" id="helpId" class="text-muted"><?php if (!empty($priceErr))
                    echo $priceErr; ?></small>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success" name="submit">Submit</button>
                <button type="reset" class="btn btn-danger">Reset</button>
                <a href="index.php" class="btn btn-info">All Products</a>
            </div>
        </form>
    </div>


    <script>


    </script>

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