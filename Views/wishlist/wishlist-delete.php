<?php
include __DIR__ . "/../../Config/connection.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

// delete only user's own wishlist item 
$stmt = $conn->prepare("DELETE FROM wishlist WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
if($stmt->execute()){
       echo "<script>
        alert('Deleted successfully!');
        window.location.href = 'index.php';
    </script>";
    exit();
}

$stmt->close();
exit();
?>