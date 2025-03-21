<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "user_management";
$conn = mysqli_connect($servername,$username,$password,$database);
if (!$conn){
    die("Connection error".mysqli_connect_error());
}
?>