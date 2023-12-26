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
    // TODO: need to send email/ notifications to the eligible students when the job is added (also check for which chance)
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

    mysqli_autocommit($conn, false);
    mysqli_begin_transaction($conn);

    $insertQuery = "INSERT INTO jobs(title, company_id, package, required_skills, location, website, description, ssc_grade, hsc_or_diploma_grade, current_grade) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insertQueryStmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($insertQueryStmt, "ssssssssss", $title, $companyId, $package, $skills, $location, $website, $description, $sscGrade, $hscOrDiplomaGrade, $currentGrade);

    if(mysqli_stmt_execute($insertQueryStmt)) {
        $jobId = mysqli_insert_id($conn);

        try {
            // send notifications
            $selectEligibleStudent = "SELECT i.user_id FROM information i JOIN jobs j ON i.ssc_grade >= j.ssc_grade AND i.hsc_or_diploma_grade >= j.hsc_or_diploma_grade AND i.current_grade >= j.current_grade WHERE j.id = ?";
            $selectEligibleStudentStmt = mysqli_prepare($conn, $selectEligibleStudent);
            mysqli_stmt_bind_param($selectEligibleStudentStmt, "i", $jobId);
            mysqli_stmt_execute($selectEligibleStudentStmt);    
            $selectEligibleStudentResult = mysqli_stmt_get_result($selectEligibleStudentStmt);

            while ($row = mysqli_fetch_assoc($selectEligibleStudentResult)) {
                $userId = $row['user_id'];
                $notificationMessage = "New Registration: " . $title . ' (' . $package . ' LPA)';

                $insertNotification = "INSERT INTO notifications (user_id, job_id, message) VALUES (?, ?, ?, ?)";
                $insertNotificationStmt = mysqli_prepare($conn, $insertNotification);
                mysqli_stmt_bind_param($insertNotificationStmt, "iiss", $userId, $jobId, $notificationMessage);
                mysqli_stmt_execute($insertNotificationStmt);
                mysqli_stmt_close($insertNotificationStmt);
            }

            mysqli_commit($conn);
            mysqli_stmt_close($insertQueryStmt);
            mysqli_autocommit($conn, true);
            redirect("Job registered successfully", "jobs.php");
        } 
        catch (Error $e) {
            mysqli_rollback($conn);
            mysqli_autocommit($conn, true);
            redirect("Could not register job. Please try again", "add-job.php", "error");
        }
    }
    else {
        mysqli_stmt_close($insertQueryStmt);
        mysqli_autocommit($conn, true);
        redirect("Could not register job. Please try again", "add-job.php", "error");
    }
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
            $jobExist = "SELECT * FROM jobs WHERE id = $jobId";
            $jobExistRun = mysqli_query($conn, $jobExist);
            if(mysqli_num_rows($jobExistRun) > 0) {
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
else if(isset($_POST['getApplicants'])) {
    $jobId = $_POST['jobId'];

    $selectApplicants = "SELECT u.*, a.id as application_id FROM applications a JOIN users u ON a.user_id = u.id WHERE a.job_id = ?";
    $selectApplicantsStmt = mysqli_prepare($conn, $selectApplicants);
    mysqli_stmt_bind_param($selectApplicantsStmt, "i", $jobId);
    mysqli_stmt_execute($selectApplicantsStmt);
    $result = mysqli_stmt_get_result($selectApplicantsStmt);

    if($result) {
        $applicants = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $applicants[] = [
                'userId' => $row['id'],
                'applicationId' => $row['application_id'],
                'name' => $row['name'],
                'email' => $row['email'],
            ];
        }

        echo sendDataWithResponse(200, $applicants);
    }
    else {
        echo sendResponse("404", "Applicants not found");
    }
}

?>