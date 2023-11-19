<?php
$activePage = "budgets";
include "../../main.php";

$email = $_SESSION['email']; // Get the users email
$currentMonth = date('m'); // Get current month
$budgets = getBudgets($con,$email); // Get all budgets connected to the user
$user_id = getUserId($con, $email);


if(isset($_POST['create'])){
    $name = $_POST['name'];
    $user_id = $_POST['user_id'];
    $total_amount = $_POST['total_amount'];
    if(!empty($_POST['fixed'])){
        $fixed = 1;
    }else{
        $fixed = 0;
    }
    
    $data = array(
        "name" => $name,
        "user_id"=> $user_id,
        "total_amount"=> $total_amount,
        "fixed"=> $fixed
    );

    if($create_budget = createBudget($con,$data)){
        header('location: ../budgets.php');
        exit();
    }else{
        echo "Kunne ikke lave budget";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budgets</title>
</head>
<body>
    <div class="container">
        <div class="col-md-6">
            <div class="form-floating col-md-6">
                <a href="<?php echo BASE_URL; ?>/html/budgets.php" class="btn save" name="back" id="back">Tilbage</a>
            </div>
        </div>
        <div class="col-md-12 create_budget">
            <form action="" method="POST">
                <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>">
                <div class="col-md-6">
                    <div class="form-floating col-md-6">
                        <input type="text" class="form-control" id="create_budget_name" name="name" placeholder="Budget navn">
                        <label for="create_budget_name">Budget navn</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating col-md-6">
                        <input type="text" class="form-control" id="create_budget_total_amount" name="total_amount" placeholder="Budget total pris">
                        <label for="create_budget_total_amount">Budget total pris</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="create_budget_fixed" name="fixed">
                        <label class="form-check-label" for="create_budget_fixed">Er prisen fast?</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating col-md-6">
                        <button type="submit" class="btn save" name="create" id="create">Tilføj</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>