<?php
session_start();
$logged_in = false;
if(isset($_SESSION['email'])){
    $logged_in = true;
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $email = $_SESSION['email'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="header">
        <div class="titel">
            Budginator
        </div>
        <?php 
        if($logged_in !== false){
        ?>
        <div class="header_links">
        <a href="<?php echo BASE_URL; ?>/html/budgets.php" class="links <?php if ($activePage === 'budgets') echo 'active'; ?>">Budgets</a>
        <a href="<?php echo BASE_URL; ?>/html/profile.php" class="links <?php if ($activePage === 'profile') echo 'active'; ?>">Profile</a>
        </div>
        <?php
        }
        ?>
        <?php 
        if($logged_in === false){
        ?>
        <div class="login">
            <form action="<?php echo BASE_URL; ?>/config/functions/login.inc.php" method="POST" class="form login">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="email" name="email" placeholder="E-mail">
                <label for="email">E-mail</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="pass" name="pass" placeholder="Password">
                <label for="pass">Password</label>
            </div>
                <button type="submit" name="login" class="btn login" id="login">Login</button>
            </form>
            <form action="<?php echo BASE_URL; ?>/html/signup.php" method="POST" class="form signup">
                <button type="submit" name="signup" class="btn signup" id="signup">Create user</button>
            </form>
        </div>
        <?php }else{ ?>
        <div class="logout">
            <form action="<?php echo BASE_URL; ?>/config/functions/logout.inc.php" method="POST">
                <button type="submit" name="logout" class="btn logout" id="logout">Log out</button>
            </form>
        </div>
        <?php } ?>
    </div>
</body>
</html>