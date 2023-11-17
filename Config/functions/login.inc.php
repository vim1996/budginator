<?php 

if(isset($_POST['login'])){
    require_once '../dbConnect.php';
    require_once 'functions.php';
    $email = $_POST['email'];
    $password = $_POST['pass'];

    if(invalidEmail($email) !== false){
        header('location: ../../html/static/main.php?error=invalidEmail');
        exit();
    }

    if(emailExsists($con, $email) === false){
        header('location: ../../html/static/main.php?error=emailNoExsists');
        exit();
    }
    login($con, $email, $password);
    
}else{
    header('location: ../../main.php');
}

?>