<?php 

if(isset($_POST['submit'])){
    require_once '../dbConnect.php';
    require_once 'functions.php';
    $email = $_POST['email'];
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $password = $_POST['pass'];
    $rePassword = $_POST['rePassword'];

    if(emptyInputSignup($email, $password, $rePassword, $name, $lastname) !== false){
        header('location: ../../html/signup.php?error=emptyinput');
        exit();
    }
    if(invalidEmail($email) !== false){
        header('location: ../../html/signup.php?error=invalidEmail');
        exit();
    }
    if(passwordMatch($password, $rePassword) !== false){
        header('location: ../../html/signup.php?error=passNoMatch');
        exit();
    }
    if(emailExsists($con, $email) !== false){
        header('location: ../../html/signup.php?error=emailExsists');
        exit();
    }

    createUser($con, $email, $password, $name, $lastname);
    
}else{
    header('location: ../../main.php');
}

?>