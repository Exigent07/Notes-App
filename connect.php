<?php 
    $servername = "localhost";
    $username_1 = "ctf";
    $password = "password";
    $database = "bi0s";
    $table = "users";

    $conn = mysqli_connect($servername, $username_1, $password, $database);

    if (!$conn) {
        die('<p style="color: orange;">Error: Couldnt Connect </p>');
    }
?>