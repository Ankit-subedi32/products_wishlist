<?php
session_start();
include __DIR__ . "/../../Config/connection.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role']; 

if (isset($_GET['id'])) {

    $id = $_GET['id'];  
    $stmt = $conn->prepare("SELECT user_id FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "Products not found!";
        exit();
    }
    if ($row['user_id'] == $user_id || $user_role === 'superadmin' ) {
        $del = $conn->prepare("DELETE FROM products WHERE id = ?");
        $del->bind_param("i", $id);
        $result = $del->execute();
        if ($result) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }

        $del->close();

    } else {
        echo "You can only delete your own products!";
        exit();
    }

    $stmt->close();
}

$conn->close();
?>