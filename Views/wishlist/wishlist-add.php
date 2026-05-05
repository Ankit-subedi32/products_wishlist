<?php
include __DIR__ . "/../../Config/connection.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['product_id'];

// prevent duplicate (user_id ra product_id xa ke nai vanyara)
$check = $conn->prepare("SELECT * FROM wishlist WHERE user_id=? AND product_id=?");
$check->bind_param("ii", $user_id, $product_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
}

// back to index page of wishlist
header("Location: index.php");
exit();

?>