<?php
include __DIR__ . "/../../Config/connection.php";
session_start();

if (!isset($_SESSION['user_id'])) {
 header("Location: /../../auth/login.php");
    exit();
}
// user le admin ko delete garna mildaina 
if ($_SESSION['role'] !== 'admin') {
    echo "Access denied!";
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    //  delete ko laghi safe garyako 
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: users.php");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>