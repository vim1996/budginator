<?php
session_start();
define('DB_CONNECT_PATH', __DIR__ . '/../../config/dbConnect.php');
require DB_CONNECT_PATH;

$userId = $_SESSION['userId'];

if(isset($_POST)){
    $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, income = ?, job_title = ? WHERE user_id = ?";
    if(isset($_POST) != ''){
        $firstName = $_POST['name'];
        $lastName = $_POST['lastname'];
        $email = $_POST['email'];
        if($email === ''){
            header('location: ../../html/profile.php?error=EmailNeeded');
            exit();
        }
        $income = $_POST['income'];
        $jobTitle = $_POST['jobtitle'];
        $stmt = mysqli_stmt_init($con);
        if(!mysqli_stmt_prepare($stmt, $sql)){ 
            header('location: ../../html/static/main.php?error=stmtFailed');
            exit();
        }
        mysqli_stmt_bind_param($stmt, "sssisi", $firstName, $lastName, $email, $income, $jobTitle, $userId);
        if(mysqli_stmt_execute($stmt)){
            header('location: ../../html/profile.php?user=Updated');
            exit();
        }else{
            header('location: ../../html/profile.php?error=notUpdated');
            exit();
        }
    }

}


?>