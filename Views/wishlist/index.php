<?php
include __DIR__ . "/../../Config/connection.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT products.*, wishlist.id AS wid 
        FROM wishlist
        JOIN products ON wishlist.product_id = products.id
        WHERE wishlist.user_id = ?";
//join  = inner join hunxa 
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Wishlist</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
<div class="container mt-4">
    <h2>My Wishlist</h2>

    <div class="row text-center">
        <?php 
        $rows = $result->fetch_all(MYSQLI_ASSOC);
    foreach($rows as $row)
        { ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="../../<?= $row['imgPath']; ?>" height="200">

                    <div class="card-body">
                        <h5><?= $row['name']; ?></h5>
                        <p><?= $row['price']; ?></p>

                        <a href="wishlist-delete.php?id=<?= $row['wid']; ?>" 
                           class="btn btn-danger">
                           Remove
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <a href="../landing/index.php" class="btn btn-secondary mt-3">Back</a>
</div>
</body>
</html>