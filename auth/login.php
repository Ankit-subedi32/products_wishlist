<!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>

<?php

session_start();
    include "../config/connection.php";

if(isset($_POST['login'])){
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT id , username , password FROM users where username = ?");
  $stmt->bind_param("s" ,$username);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows >0){
    $row = $result->fetch_assoc();
     if(password_verify($password , $row['password'])){
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      header("location: ../Views/product/index.php");
      exit();
  }
  

  else {
            echo "<div class='container'><div class='alert alert-danger text-center'>Invalid password.</div></div>";
        }
    } else {
        echo "<div class='container'><div class='alert alert-warning text-center'>No user found with that username.</div></div>";
    }

    $stmt->close();
    $conn->close();

 
}
?>
    <div class="container">
        <form action="" method="post" autocomplete="off">
            <div class="form-group">
              <label for="">Username:</label>
              <input type="text" name="username" id="username" class="form-control" placeholder="" aria-describedby="helpId">
            </div>

            <div class="form-group">
              <label for="">Password:</label>
              <input type="password" name="password" id="password" class="form-control" placeholder="" aria-describedby="helpId">
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary" name="login">Login</button>
              <a href="register.php" class="btn btn-info">Register</a>
 
            </div>
        </form>
    </div>
      
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>