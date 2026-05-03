<?php
include __DIR__ . "/../../Config/connection.php";
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>

<div class="container mt-4">
    <h2>All Products</h2>

    <div class="row text-center">
        <?php
        $result = $conn->query("SELECT * FROM products");

        while ($row = $result->fetch_assoc()) {
        ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="../../<?= $row['imgPath']; ?>" height="200">

                    <div class="card-body">
                        <h5><?= $row['name']; ?></h5>
                        <p><?= $row['description']; ?></p>
                        <p>Price: <?= $row['price']; ?></p>

                        <?php if (isset($_SESSION['user_id'])) { ?>
                            <a  href="../wishlist/wishlist-add.php?product_id=<?= $row['id']; ?>" class="btn btn-info ">
                                Add to Wishlist
                            </a>
                        <?php } else { ?>
                            <a href="../../auth/login.php" class="btn btn-secondary">
                                Login first
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

</div>

</body>
</html>