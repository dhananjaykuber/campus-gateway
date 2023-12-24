<?php

    define('DB_HOST', 'localhost');
    define('DB_USER', 'dhananjay');
    define('DB_PASS', '123456');
    define('DB_NAME', 'campus_gateway');

    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if (!$conn) {
        die('Connection failed ' . mysqli_connect_error());
    }

?>