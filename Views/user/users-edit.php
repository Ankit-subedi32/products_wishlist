<?php
include __DIR__ . "/../../Config/connection.php";
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: ../../auth/login.php");
  exit();
} else {

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
    if (isset($_GET['id'])) {
      $id = $_GET['id'];
      $stmt = $conn->prepare("SELECT username, firstname, lastname ,role FROM users WHERE id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();

      if (!$result) {
        die("Query Failed: " . $conn->error);
      } else {
        $row = $result->fetch_assoc();
      }
    }
    ?>

    <?php
    if (isset($_POST['user_edit'])) {
      $username = $_POST['username'];
      $firstname = $_POST['firstname'];
      $lastname = $_POST['lastname'];
      $role = $_POST['role'];
      $stmt = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, role = ?   WHERE id = ?");
      $stmt->bind_param("sssi", $firstname, $lastname, $role, $id);
      $update_result = $stmt->execute();
      if (!$update_result) {
        die("Query Failed: " . $conn->error);
      } else {
        header('Location: users.php?update_msg=You have successfully updated the data.');
        exit;
      }
    }

    ?>
    <div class="container">


      <form action="users-edit.php?id=<?php echo $id; ?>" method="post">
        <div class="form-group">
          <label for="">Username:</label>
          <input type="text" name="username" id="username" class="form-control" value="<?php echo $row['username']; ?>"
            placeholder="" aria-describedby="helpId" readonly>
        </div>
        <div class="form-group">
          <label for="">firstname:</label>
          <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo $row['firstname']; ?>"
            placeholder="" aria-describedby="helpId">
        </div>
        <div class="form-group">
          <label for="">lastname:</label>
          <input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo $row['lastname']; ?>"
            placeholder="" aria-describedby="helpId">
        </div>

        <?php if ($_SESSION['role'] === 'superadmin') { ?>

          <div class="form-group">
            <label>Role:</label><br>
            <!-- role user vaya checked hunxa natra khali hunxa   -->
            <input type="radio" name="role" value="user" <?= ($row['role'] == 'user') ? 'checked' : '' ?>>
            User

            <input type="radio" name="role" value="admin" <?= ($row['role'] == 'admin') ? 'checked' : '' ?>>
            Admin
          </div>

        <?php } ?>
        <div class="text-center">
          <button name="user_edit" class="bg-success rounded text-light" type="submit">Submit</button>
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

  <?php
}
?>