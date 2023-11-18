<?php 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/style.css"> <!-- Link to your CSS file -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="../config/functions/signup.inc.php" method='POST' class="signupForm">
    <div class="titel">
        <a href="../main.php" style="color: black; text-decoration: none;">Budginator</a>
    </div>
    <h2 style="text-align:center;">Opret bruger</h2>
    <div class="container signup">
        <div class="row">
            <div class="form-floating col-md-6">
                <input type="text" class="form-control" id="name" name="name" placeholder="Fornavn">
                <label for="name">Fornavn</label>
            </div>
            <div class="form-floating col-md-6">
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Efternavn">
                <label for="lastname">Efternavn</label>
            </div>
        </div>
        <div class="row">
            <div class="form-floating col-md-12">
                <input type="email" class="form-control" id="email" name="email" placeholder="E-mail">
                <label for="email">E-mail</label>
            </div>
        </div>
        <div class="row">
            <div class="form-floating col-md-12">
                <input type="password" class="form-control" id="pass" name="pass" placeholder="Password">
                <label for="pass">Password</label>
            </div>
        </div>
        <div class="row">
            <div class="form-floating col-md-12">
                <input type="password" class="form-control" id="rePassword" name="rePassword" placeholder="Bekræft password">
                <label for="rePassword">Bekræft password</label>
            </div>
        </div>
        <div class="row">
            <div class="form-floating col-md-6">
                <button type="submit" class="btn signup" name="submit" id="submit">Opret bruger</button>
            </div>
        </div>
    </div>
</form>
</body>
</html>