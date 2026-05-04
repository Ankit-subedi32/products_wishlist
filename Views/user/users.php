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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  </head>

  <body>

    <?php
    if ($_SESSION['role'] == 'superadmin') {
      $sql = "SELECT * FROM users";
      $result = $conn->query($sql);
    } else {
      $id = $_SESSION['user_id'];
      $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();
    }
    ?>

    <div class="container">

      <?php if ($_SESSION['role'] == 'superadmin') { ?>
        <a href="../product/index.php" class="btn btn-secondary mb-3">All Products</a>
      <?php } ?>

      <?php if ($_SESSION['role'] == 'admin') { ?>
        <a href="news.php" class="btn btn-secondary mb-3">all products</a>
      <?php } ?>

      <!-- TABLE START (ONLY ONCE) -->
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Name</th>
            <?php
            if ($_SESSION['role'] == 'superadmin') {
              ?>
              <th>Role</th>

              <?php

            }
            ?>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>

        <tbody>

          <?php
          while ($row = mysqli_fetch_assoc($result)) {

            $a = $row['id'];
            $b = $row['username'];
            $c = $row['firstname'];
            $d = $row['lastname'];
            $f = $row['role'];
            $name = $c . " " . $d;
            ?>

            <tr>
              <td><?php echo $a; ?></td>
              <td><?php echo $b; ?></td>
              <td><?php echo $name; ?></td>
              <?php
              if ($_SESSION['role'] == 'superadmin') {

                ?>
                <td><?php echo $f; ?></td>
                <?php

              }
              ?>
              <td>
                <a href="users-edit.php?id=<?php echo $a; ?>">
                  <button class="bg-info text-light rounded">Edit</button>
                </a>
              </td>

              <td>
                <?php if ($_SESSION['role'] == 'superadmin') { ?>
                  <a href="users-delete.php?id=<?php echo $a; ?>">
                    <button class="bg-danger text-light rounded">Delete</button>
                  </a>
                <?php } else { ?>
                  -
                <?php } ?>
              </td>

            </tr>

          <?php } ?>

        </tbody>
      </table>
    </div>

  </body>

  </html>

<?php } ?>