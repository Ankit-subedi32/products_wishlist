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

    include "../config/connection.php";
    if (isset($_POST['register'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hashedpassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (firstname,lastname, username, password  )VALUES (?,?,?,?) ");
        if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
        $stmt->bind_param("ssss" ,$firstname,$lastname , $username ,$hashedpassword);

        $stmt->execute();

    }

    ?>
    <div class="container">

        <form action="" method="post">
            <div class="form-group">
                <label for="">Firstname:</label>
                <input type="text" name="firstname" id="firstname" class="form-control" placeholder=""
                    aria-describedby="helpId">
            </div>


            <div class="form-group">
                <label for="">Lastname:</label>
                <input type="text" name="lastname" id="lastname" class="form-control" placeholder=""
                    aria-describedby="helpId">
            </div>

            <div class="form-group">
                <label for="">Username(Email):</label>
                <input type="text" name="username" id="username" class="form-control" placeholder=""
                    aria-describedby="helpId">
            </div>

            <div class="form-group">
                <label for="">Password:</label>
                <input type="password" name="password" id="password" class="form-control" placeholder=""
                    aria-describedby="helpId">
            </div>
            <div class="text-center">
                <button type="submit" name="register" class="btn btn-primary">Submit</button>
                <button type="clear" class="btn btn-danger">Reset</button>
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