<?php
include __DIR__ . "/../../Config/connection.php";
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
  <div class="container">
    <a href="/wishlist/auth/login.php" class="btn btn-danger">Logout</a>
    <a href="product-add.php" class="btn btn-warning">Add Product</a>
    <div>Hello, 
      <?=  $_SESSION['name']; ?>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th>Id</th>
          <th>Product Name</th>
          <th>Image</th>
          <th>Description</th>
          <th>Price</th>
          <th>EDIT</th>
          <th>DELETE</th>

        </tr>
      </thead>
      <tbody>

        <?php
        if ($_SESSION['role'] == "superadmin") {
          $sql = "SELECT * FROM products";
          $result = $conn->query($sql);
        } else {
          // specific user ko laghi
        
          $user_id = $_SESSION['user_id'];
          $stmt = $conn->prepare("SELECT * FROM products WHERE user_id = ?");
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $result = $stmt->get_result();
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $row) {

          ?>
          <tr>
            <td scope="row"><?= $row['id']; ?></td>
            <td scope="row"><?= $row['name']; ?></td>
            <td>
              <img src="../../<?= $row['imgPath'] ?>" width="150">
            </td>
            <td scope="row"><?= $row['description']; ?></td>
            <td scope="row"><?= $row['price']; ?></td>

            <td>
              <a class="btn btn-success " href="product-edit.php?id=<?php echo $row['id']; ?>">EDIT</a>
            </td>
            <td>
              <a class="btn btn-danger " href="product-delete.php?id=<?php echo $row['id']; ?>">Delete</a>
            </td>
          </tr>

          <?php
        }



        ?>
      </tbody>
    </table>
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