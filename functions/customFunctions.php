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

function sendDataWithResponse($statusCode, $data) {
    $response = ['status' => $statusCode, 'data' => $data];
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

function getJobById($id) {
    global $conn;

    $selectQuery = "SELECT jobs.*, companies.name as company_name FROM jobs INNER JOIN companies ON jobs.company_id = companies.id WHERE jobs.id = $id";
    $selectQueryRun = mysqli_query($conn, $selectQuery);
    
    return $selectQueryRun;
}

function getAppliedJobs($userId) {
    global $conn;

    $selectQuery = "SELECT a.id as application_id, j.*, c.name as company_name FROM applications a JOIN jobs j ON a.job_id = j.id JOIN companies c ON j.company_id = c.id WHERE a.user_id = $userId";
    $selectQueryRun = mysqli_query($conn, $selectQuery);

    return $selectQueryRun;
}

// TODO: while applying check the package gap and how many offers does user holds (max 2 offers and package gap 2 LPA)
function checkEligibleForJob($userId, $jobId) {
    global $conn;

    // 0: not eligible
    // 1: eligible
    // 2 : already applied

    // check application already registered
    $applicationExist = "SELECT * FROM applications WHERE user_id = ? AND job_id = ?";
    $applicationExistStmt = mysqli_prepare($conn, $applicationExist);
    mysqli_stmt_bind_param($applicationExistStmt, "ii", $userId, $jobId);
    mysqli_stmt_execute($applicationExistStmt);
    mysqli_stmt_store_result($applicationExistStmt);

    if(mysqli_stmt_num_rows($applicationExistStmt) > 0) {
        return 2;
    }

    // select user's grades
    $selectUsersGradeQuery = "SELECT ssc_grade, hsc_or_diploma_grade, current_grade FROM information WHERE user_id = ?";
    $selectUsersGradeStmt = mysqli_prepare($conn, $selectUsersGradeQuery);
    mysqli_stmt_bind_param($selectUsersGradeStmt, "i", $userId);
    mysqli_stmt_execute($selectUsersGradeStmt);
    mysqli_stmt_bind_result($selectUsersGradeStmt, $sscGrade, $hscOrDiplomaGrade, $currentGrade);
    mysqli_stmt_fetch($selectUsersGradeStmt);
    mysqli_stmt_close($selectUsersGradeStmt);

    // select job's eligibility criteria
    $selectJobCriteriaQuery = "SELECT ssc_grade, hsc_or_diploma_grade, current_grade FROM jobs WHERE id = ?";
    $selectJobCriteriaStmt = mysqli_prepare($conn, $selectJobCriteriaQuery);
    mysqli_stmt_bind_param($selectJobCriteriaStmt, "i", $jobId);
    mysqli_stmt_execute($selectJobCriteriaStmt);
    mysqli_stmt_bind_result($selectJobCriteriaStmt, $minSSCGrade, $minHSCOrDiplomaGrade, $minCurrentGrade);
    mysqli_stmt_fetch($selectJobCriteriaStmt);
    mysqli_stmt_close($selectJobCriteriaStmt);

    if($sscGrade >= $minSSCGrade && $hscOrDiplomaGrade >= $minHSCOrDiplomaGrade && $currentGrade >= $minCurrentGrade) {
        return 1;
    }

    return 0;
}

function getById($table, $id) {
    global $conn;
    
    $selectQuery = "SELECT * FROM $table WHERE id = $id";
    $selectQueryRun = mysqli_query($conn, $selectQuery);
    
    return $selectQueryRun;
}

function getAllNotifications($userId) {
    global $conn;
    
    $selectQuery = "SELECT * FROM notifications WHERE user_id = $userId";
    $selectQueryRun = mysqli_query($conn, $selectQuery);
    
    return $selectQueryRun;
}

function getStudentProfile() {
    global $conn;

    $selectQuery = "SELECT users.*, information.* FROM users LEFT JOIN information ON users.id = information.user_id";
    $selectQueryRun = mysqli_query($conn, $selectQuery);

    return $selectQueryRun;
}

?>