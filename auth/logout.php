<?php
session_start();
session_destroy();
header("Location: /wishlist/auth/login.php");
exit();

?>