<?php 
include "../../main.php";
$email = $_SESSION['email']; // Get the users email
$user_id = $_SESSION['user_id'];

if(isset($_GET['update_budget'])){
    $budget_id = $_GET['update_budget'];
    $budget = new Budget($con);
    $budget = $budget->getBudgetById($budget_id);
    $budget_name = $budget['name'];
}
$error = false;
if(isset($_POST) && isset($_POST['update'])){
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $budget_id = $_POST['budget_id'];
    if($_POST['fixed'] == 'on'){
        $fixed = 1;
    }else{
        $fixed = 0;
    }
    $budget = new Budget($con);
    if($budget->updateBudget($budget_id, $name, $user_id, $amount, 0, $fixed)){
        header('Location: budget_page.php?budget=' . $budget_id . '&updated=true');
    }else{
        $error = true;
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
                <a href="budget_page.php?budget=<?php echo $budget_id;?>" class="btn save" name="back" id="back">Tilbage</a>
            </div>
        </div>
        <?php if($error){?>
            <div class="col-md-6">
                <div class="form-floating col-md-6" style="color:red;">
                    Kunne ikke opdatér <?=$name?>
                </div>
            </div>
        <?php } ?>
        <form action="" method='POST' class="update_budget_form">
            <input type="hidden" class="form-control" id="budget_id" name="budget_id" value="<?=$budget_id?>">
                <div class="col-md-6"> 
                </div>
                <h2>Opdatér <?php echo $budget_name; ?></h2>
                <div class="col-md-6">
                    <div class="form-floating col-md-6">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Budget navn">
                        <label for="name">Budget navn</label>
                    </div>
                    <div class="form-floating col-md-6">
                        <input type="text" class="form-control" id="amount" name="amount" placeholder="Beløb">
                        <label for="amount">Beløb</label>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="update_budget_fixed" name="fixed">
                            <label class="form-check-label" for="update_budget_fixed">Fast budget? 
                                <i class="bi bi-question-circle"><span class="tooltip-text">Fast budget betyder, at det er en fast månedlig pris. For eksempel kan du have et madbudget på 2000 kr. pr. måned, og du kan tilføje til det, når du køber ind.</span></i>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating col-md-6">
                        <button type="submit" class="btn save" name="update" id="update">Opdatér</button>
                    </div>
                </div>
        </form>
    </div>
</body>
</html>