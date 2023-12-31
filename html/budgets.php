<?php
$activePage = "budgets";
include "../main.php";

$email = $_SESSION['email']; // Get the users email
$currentMonth = date('m'); // Get current month
$budgets = getBudgets($con,$email); // Get all budgets connected to the user
$noBudgets = false;
if(!$budgets){
    $noBudgets = true;
}
$deleted = false;
if(isset($_GET['delete'])){
    $b_id = $_GET['delete'];
    $b = new Budget($con);
    $b = $b->getBudgetById($b_id);
    $b_name = $b['name'];
    $deleted = true;
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
    <div class="container" style="display:flex;">
        <div class="col-md-12 create_budget">
            <a href="<?php echo BASE_URL; ?>/html/budgets/create_budget.php" class="create_budget">Lav budget</a>
        </div>
    </div>
    <?php 
        if($deleted){?>
        <div class="container" style="display:flex;">
            <div class="col-md-12 create_budget">
                <p style="color:red;">Budget: <?=$b_name?> Er blevet slettet</p>
            </div>
        </div>
        <?php }
        ?>
    <div class="container profil_information">
            <div class="col-md-6 budgets_column">
                <?php 
                foreach($budgets as $budgetId => $budget){
                    if($budget['deleted'] != '1'){
                        $budgetName = $budget['name'];
                        $favorite = $budget['favorite'];
                        $budgetTotalAmount = $budget['totalAmount'];
                        $budgetUsedAmount = $budget['usedAmount'];
                        $allBudgetUsers = "";
                        $budgetUsers = getBudgetUsers($con, $budgetId); // Gets the users associated with that budget
                        foreach($budgetUsers as $budgetUser => $primaryUser){
                            if($primaryUser != '1'){ // Add users that are not primary
                                if($allBudgetUsers == ''){
                                    $allBudgetUsers .= $budgetUser;
                                }else{
                                    $allBudgetUsers .= ", " . $budgetUser;
                                }
                            }
                        }
        
                        $percentageUsed = round(($budgetUsedAmount / $budgetTotalAmount) * 100); // Calculate the percentage
                    ?>
                        <div class="col-md-10">
                            <div class="budgets" style="display:flex;">
                                <i class="bi bi-heart" name="favorite" style="display:<?= $favorite == '1' ? 'none;' : 'unset;'; ?>" value="<?=$budgetId?>"></i>
                                <i class="bi bi-heart-fill" name="favorite_picked" style="display:<?= $favorite == '1' ? 'unset;' : 'none;'; ?>" value="<?=$budgetId?>"></i>
                                <a href="<?php echo BASE_URL; ?>/html/budgets/budget_page.php?budget=<?php echo $budgetId;?>" class="budget_link">
                                    <div class="col-md-12">
                                        <div class="budget_name col-md-12">
                                            <?php echo $budgetName; ?>
                                        </div>
                                        <div class="budget_inf col-md-12">
                                            <div class="progress">
                                                <div class="progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $percentageUsed;?>" style="width:<?php echo $percentageUsed;?>%;" aria-valuemin="0" aria-valuemax="100"><?php echo $budgetUsedAmount . " / " . $budgetTotalAmount . " DKK";?></div>
                                            </div>
                                        </div>
                                        <?php if ($allBudgetUsers != ''){ ?>
                                        <div class="budget_shared col-md-12">
                                            <div class="shared_text">Delt med: <?php echo $allBudgetUsers;?></div>
                                        </div>
                                        <?php }?>
                                    </div>
                                </a>
                            </div>
                        </div>
                        
                    <?php }
                } ?>
            </div>
    </div>
    <script src="../Javascript/budgets.js"></script>
</body>
</html>