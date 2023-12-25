<?php

session_start();

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
    // TODO: need to send email/ notifications to the eligible students when the job is added
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
else if(isset($_POST['deleteJob'])) {
    $jobId = $_POST['jobId'];
    $role = $_SESSION['role'];

    if($role == 1) {
        try {
            $jobExistQuery = "SELECT * FROM jobs WHERE id = $jobId";
            $jobExistQueryRun = mysqli_query($conn, $jobExistQuery);
            if(mysqli_num_rows($jobExistQueryRun) > 0) {
                $deleteQuery = "DELETE FROM jobs WHERE id = $jobId";
                $deleteQueryRun = mysqli_query($conn, $deleteQuery);
                if($deleteQueryRun) {
                    echo sendResponse(200, 'Job deleted successfully');
                }
                else {
                    echo sendResponse(500, 'Could not delete job');
                }
            }
            else {
                echo sendResponse(401, 'Job not found');
            }
        } 
        catch (Exception $e) {
            echo sendResponse(500, 'Something went wrong. Try again later');
        }
    }
    else {
        echo sendResponse(401, 'Unauthorized access');
    }
}
else if(isset($_POST['deleteCompany'])) {
    $companyId = $_POST['companyId'];
    $role = $_SESSION['role'];

    // disable autocommit
    mysqli_autocommit($conn, false);

    if($role == 1) {
        try {
            // start transaction
            mysqli_begin_transaction($conn);

            $companyExistQuery = "SELECT * FROM companies WHERE id = $companyId";
            $companyExistQueryRun = mysqli_query($conn, $companyExistQuery);
            if(mysqli_num_rows($companyExistQueryRun) > 0) {
                // delete all jobs related to company
                $deleteJobsQuery = "DELETE FROM jobs WHERE company_id = $companyId";
                $deleteJobsQueryRun = mysqli_query($conn, $deleteJobsQuery);
                if($deleteJobsQueryRun) {
                    $deleteCompanyQuery = "DELETE FROM companies WHERE id = $companyId";
                    $deleteCompanyQueryRun = mysqli_query($conn, $deleteCompanyQuery);

                    if($deleteCompanyQueryRun) {
                        // commit all transactions if all queries succeed 
                        mysqli_commit($conn);
                        echo sendResponse(200, 'Company deleted successfully');
                    }
                    else {
                        mysqli_rollback($conn);
                        echo sendResponse(500, 'Could not delete company');
                    }
                }
                else {
                    mysqli_rollback($conn);
                    echo sendResponse(500, 'Could not delete company');
                }
            }
            else {
                echo sendResponse(401, 'Company not found');
            }
        } 
        catch (Exception $e) {
            mysqli_rollback($conn);
            echo sendResponse(500, 'Something went wrong. Try again later');
        }
        finally {
            // enable autocommit
            mysqli_autocommit($conn, true);
        }
    }
    else {
        echo sendResponse(401, 'Unauthorized access');
    }
}

?>