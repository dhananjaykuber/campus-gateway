<?php

include("../config/db.php");

function redirect($message, $url, $type='success') {
    $alertMessage = ['text' => $message, 'type' => $type];

    $_SESSION['message'] = $alertMessage;
    header('Location: '.$url);
    exit;
}

function sendResponse($statusCode, $message) {
    $response = ['status' => $statusCode, 'message' => $message];
    return json_encode($response);
}

function getAll($table) {
    global $conn;
    
    $selectQuery = "SELECT * FROM $table";
    $selectQueryRun = mysqli_query($conn, $selectQuery);
    
    return $selectQueryRun;
}

function getJobs() {
    global $conn;

    $selectQuery = "SELECT jobs.*, companies.name as company_name FROM jobs INNER JOIN companies ON jobs.company_id = companies.id";
    $selectQueryRun = mysqli_query($conn, $selectQuery);
    
    return $selectQueryRun;
}

function getById($table, $id) {
    global $conn;
    
    $selectQuery = "SELECT * FROM $table WHERE id = $id";
    $selectQueryRun = mysqli_query($conn, $selectQuery);
    
    return $selectQueryRun;
}

?>