<?php
session_start();
if (isset($_GET['location']) && $_GET['location'] === 'landing') {
    $redirect = "/wishlist/views/landing/index.php";
} else {
    $redirect = "/login.php";
}
unset($_SESSION['user_id']);
session_destroy();
header("Location: " .$redirect);
exit();

?>