<?php 
include "../../main.php";
$user_id = $_SESSION['user_id'];

$added = null;
$budget = new Budget($con);
if(isset($_GET['budget'])){
    $budget_id = $_GET['budget'];
    $budget_inf = $budget->getBudgetById($budget_id);
    $budget_name = $budget_inf['name'];
}

if(isset($_POST) && isset($_POST['share'])){
    $email = $_POST['email'];
    $budget_id = $_POST['budget_id'];
    $add_user = $budget->addBudgetUser($email, $budget_id);
    if($add_user){
        $added = true;
    }else{
        $added = false;
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
    <div class="container budget_item">
        <div class="col-md-6">
            <div class="form-floating col-md-6">
                <a href="budget_page.php?budget=<?php echo $budgetId;?>" class="btn save" name="back" id="back">Tilbage</a>
            </div>
        </div>
        <?php if($added === false){?>
            <div class="col-md-10">
                <div class="form-floating col-md-6" style="color:red;">
                    Findes ingen bruger med E-mail: <?=$email?>
                </div>
            </div>
        <?php }elseif($added === true){ ?>
            <div class="col-md-10">
                <div class="form-floating col-md-6" style="color:green;">
                    Budgettet er nu delt med <?=$email?>
                </div>
            </div>
        <?php } ?>
        <form action="" method='POST' class="share_budget_form">
            <input type="hidden" class="form-control" id="budget_id" name="budget_id" value="<?=$budget_id?>">
                <div class="col-md-6"> 
                </div>
                <h2>Del <?php echo $budget_name; ?></h2>
                <div class="col-md-6">
                    <div class="form-floating col-md-6">
                        <input type="text" class="form-control" id="email" name="email" placeholder="E-mail">
                        <label for="email">E-mail</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating col-md-6">
                        <button type="submit" class="btn save" name="share" id="share">Del budget</button>
                    </div>
                </div>
        </form>
    </div>
</body>
</html>