<?php
$conn = new mysqli('localhost', 'root', '', 'kawaii');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else{
    // echo "successfuly";
}
mysqli_set_charset($conn,"utf8"); //กำหนด charset เป็น utf8

?>