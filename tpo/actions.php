<?php

session_start();

include("../config/db.php");
include("../functions/customFunctions.php");

if(isset($_POST['addCompany'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contactNo = mysqli_real_escape_string($conn, $_POST['contactNo']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $insertQuery = "INSERT INTO companies(name, email, contact_no, website, address, description) values(?, ?, ?, ?, ?, ?)";
    $insertQueryStmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($insertQueryStmt, "ssssss", $name, $email, $contactNo, $website, $address, $description);

    if(mysqli_stmt_execute($insertQueryStmt)) {
        mysqli_stmt_close($insertQueryStmt);
        redirect("Company registered successfully", "companies.php");
    }
    
    mysqli_stmt_close($insertQueryStmt);
    redirect("Could not register company. Please try again", "add-company.php", "error");
}
else if(isset($_POST['addJob'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $companyId = mysqli_real_escape_string($conn, $_POST['companyId']);
    $package = mysqli_real_escape_string($conn, $_POST['package']);
    $skills = mysqli_real_escape_string($conn, $_POST['skills']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $sscGrade = mysqli_real_escape_string($conn, $_POST['sscGrade']);
    $hscOrDiplomaGrade = mysqli_real_escape_string($conn, $_POST['hscOrDiplomaGrade']);
    $currentGrade = mysqli_real_escape_string($conn, $_POST['currentGrade']);

    $insertQuery = "INSERT INTO jobs(title, company_id, package, required_skills, location, website, description, ssc_grade, hsc_or_diploma_grade, current_grade) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insertQueryStmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($insertQueryStmt, "ssssssssss", $title, $companyId, $package, $skills, $location, $website, $description, $sscGrade, $hscOrDiplomaGrade, $currentGrade);

    if(mysqli_stmt_execute($insertQueryStmt)) {
        mysqli_stmt_close($insertQueryStmt);
        redirect("Job registered successfully", "jobs.php");
    }
    
    mysqli_stmt_close($insertQueryStmt);
    redirect("Could not register job. Please try again", "add-job.php", "error");
}
else if(isset($_POST['editCompany'])) {
    $companyId = mysqli_real_escape_string($conn, $_POST['companyId']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contactNo = mysqli_real_escape_string($conn, $_POST['contactNo']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $updateQuery = "UPDATE companies SET name = ?, email = ?, contact_no = ?, website = ?, address = ?, description = ? WHERE id = ?";
    $updateQueryStmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($updateQueryStmt, "ssssssi", $name, $email, $contactNo, $website, $address, $description, $companyId);
    mysqli_stmt_execute($updateQueryStmt);

    if(mysqli_stmt_affected_rows($updateQueryStmt) > 0) {
        mysqli_stmt_close($updateQueryStmt);
        redirect("Company information updated successfully", "edit-company.php?id=$companyId");
    }

    mysqli_stmt_close($updateQueryStmt);
    redirect("Could not update company. Please try again", "edit-company.php", "error");
}
else if(isset($_POST['editJob'])) {
    $jobId = mysqli_real_escape_string($conn, $_POST['jobId']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $companyId = mysqli_real_escape_string($conn, $_POST['companyId']);
    $package = mysqli_real_escape_string($conn, $_POST['package']);
    $skills = mysqli_real_escape_string($conn, $_POST['skills']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $website = mysqli_real_escape_string($conn, $_POST['website']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $sscGrade = mysqli_real_escape_string($conn, $_POST['sscGrade']);
    $hscOrDiplomaGrade = mysqli_real_escape_string($conn, $_POST['hscOrDiplomaGrade']);
    $currentGrade = mysqli_real_escape_string($conn, $_POST['currentGrade']);

    $updateQuery = "UPDATE jobs SET title = ?, company_id = ?, package = ?, required_skills = ?, location = ?, website = ?, description = ?, ssc_grade = ?, hsc_or_diploma_grade = ?, current_grade = ? WHERE id = ?";
    $updateQueryStmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($updateQueryStmt, "sissssssssi", $title, $companyId, $package, $skills, $location, $website, $description, $sscGrade, $hscOrDiplomaGrade, $currentGrade, $jobId);
    mysqli_stmt_execute($updateQueryStmt);

    if(mysqli_stmt_affected_rows($updateQueryStmt) > 0) {
        mysqli_stmt_close($updateQueryStmt);
        redirect("Job information updated successfully", "edit-job.php?id=$jobId");
    }

    mysqli_stmt_close($updateQueryStmt);
    redirect("Could not update job. Please try again", "edit-job.php", "error");
}

?>