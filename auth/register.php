<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!--  meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <?php

    include "../config/connection.php";
    $errors = [];
    function test($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $name_pattern = "/^[A-Z][a-z]+(?: [A-Z][a-z]+)*$/";
    $phone_pattern = "/^[0-9+\-\s]{7,15}$/";
    $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#]).{8,}$/";
    if (isset($_POST['register'])) {
        $firstname = test($_POST['firstname']);
        $lastname = test($_POST['lastname']);
        $username = test($_POST['username']);
        $password = test($_POST['password']);



        if (!preg_match($name_pattern, $firstname)) {
            $errors[] = "Invalid firstname format";
        }
        if (!preg_match($name_pattern, $lastname)) {
            $errors[] = "Invalid lastname format";
        }
        if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        if (!preg_match($password_pattern, $password)) {
            $errors[] = "Weak password";
        }


        if (empty($firstname) || empty($lastname) || empty($username) || empty($password)) {
            $errors[] = "Please Fill all fileds";
        }


        if (empty($errors)) {
            $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (firstname,lastname, username, password  )VALUES (?,?,?,?) ");
            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("ssss", $firstname, $lastname, $username, $hashedpassword);
            if ($stmt->execute()) {
                echo "<script>alert('Signup successful')</script>";
                echo "<script>window.location='login.php'</script>";
            } else {
                echo "Database Error: " . $conn->error;
            }
            $stmt->close();

        } else {
            foreach ($errors as $error) {
                echo " <div class='alert alert-danger'>$error</div>";
            }
        }



    }

    ?>
    <div class="container">
        <form action="" method="post">
            <div class="form-group">
                <label for="">Firstname:</label>
                <input type="text" name="firstname" id="firstname" class="form-control" placeholder=""
                    aria-describedby="firstnameMsg">

                <small id="firstnameMsg" class="text-danger"></small>
            </div>


            <div class="form-group">
                <label for="">Lastname:</label>
                <input type="text" name="lastname" id="lastname" class="form-control" placeholder=""
                    aria-describedby="lastnameMsg">
                <small id="lastnameMsg" class="text-danger"></small>
            </div>

            <div class="form-group">
                <label for="">Username(Email):</label>
                <input type="email" name="username" id="username" class="form-control" placeholder=""
                    aria-describedby="usernameMsg">
                <small id="usernameMsg" class="text-danger"></small>
            </div>

            <div class="form-group">
                <label for="">Password:</label>
                <input type="password" name="password" id="password" class="form-control" placeholder=""
                    aria-describedby="passowrdMsg">
                <small id="passwordMsg" class="text-danger"></small>
            </div>
            <div class="text-center">
                <button type="submit" name="register" class="btn btn-primary">Submit</button>
                <button type="reset" class="btn btn-danger">Reset</button>
            </div>

        </form>
    </div>

    <script>
        document.querySelector("form").addEventListener("submit", function (e) {
            let valid = true;

            let firstname = document.querySelector("[name='firstname']").value.trim();
            let lastname = document.querySelector("[name='lastname']").value.trim();
            let email = document.querySelector("[name='username']").value.trim();
            let password = document.querySelector("[name='password']").value.trim();

            let namePattern = /^[A-Z][a-z]+(?: [A-Z][a-z]+)*$/;
            let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#]).{8,}$/;
            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            document.getElementById("firstnameMsg").innerText = "";
            document.getElementById("lastnameMsg").innerText = "";
            document.getElementById("usernameMsg").innerText = "";
            document.getElementById("passwordMsg").innerText = "";

            if (!namePattern.test(firstname)) {
                document.getElementById("firstnameMsg").innerText = "Firstname must start with capital letter";
                valid = false;
            }
            if (!namePattern.test(lastname)) {
                document.getElementById("lastnameMsg").innerText = "Invalid lastname format";
                valid = false;
            }
            if (!emailPattern.test(email)) {
                document.getElementById("usernameMsg").innerText = "Invalid email format";
                valid = false;
            }
            if (!passwordPattern.test(password)) {
                document.getElementById("passwordMsg").innerText =
                    "Password must contain: uppercase, lowercase, number, special character, and be 8+ characters long";
                valid = false;
            }

            if (!valid) {
                e.preventDefault(); // stop submission
            }
        });

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