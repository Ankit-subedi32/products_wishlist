<?php


$servername = "localhost";
$username ="root";
$password ="";
$dbname = "wishlist";

$conn = new mysqli($servername, $username ,$password ,$dbname );

if($conn->connect_error)
{
    die("Database Error" .$conn->connect_error);

}

echo "Established";

?>