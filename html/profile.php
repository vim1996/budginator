<?php
$activePage = "profile";
include "../main.php";

$email = $_SESSION['email']; // Get the users email
$currentMonth = date('m'); // Get current month
$budgets = getBudgets($con,$email); // Get all budgets connected to the user
$noBudgets = false;
if(!$budgets){
    $noBudgets = true;
}
$user = getUser($con, $email);
$_SESSION['userId'] = $user['user_id'];
if (isset($_GET['user']) && $_GET['user'] == "Updated") {
    $update = "Bruger opdateret";
    $color = "green";
} elseif (isset($_GET['error']) && $_GET['error'] == 'notUpdated') {
    $update = "Kunne ikke opdatere bruger";
    $color = "red";
}elseif (isset($_GET['error']) && $_GET['error'] == 'EmailNeeded') {
    $update = "Der skal være en E-mail";
    $color = "red";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <div class="container profil_information">
        <div class="col-md-6">
            <?php if(!empty($_GET)){ ?>
                <div class="form-floating col-md-8" style="width: 67.46666667%;">
                    <p style="color: <?php echo $color; ?>; font-weight: bold;"><?php echo $update; ?></p>
                </div>
            <?php } ?>
            <form action="../Config/functions/update_user.php" method="POST">
                <div class="form-floating col-md-4">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Fornavn" value="<?php echo $user['first_name'];?>">
                    <label for="name">Fornavn</label>
                </div>
                <div class="form-floating col-md-4">
                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Efternavn" value="<?php echo $user['last_name'];?>"> 
                    <label for="lastname">Efternavn</label>
                </div>
                <div class="form-floating col-md-8" style="width: 67.46666667%;">
                    <input type="text" class="form-control" id="email" name="email" placeholder="E-mail" value="<?php echo $user['email'];?>"> 
                    <label for="email">E-mail</label>
                </div>
                <div class="form-floating col-md-4">
                    <input type="text" class="form-control" id="income" name="income" placeholder="Indkomst" value="<?php echo $user['income'];?>"> 
                    <label for="income">Indkomst</label>
                </div>
                <div class="form-floating col-md-4">
                    <input type="text" class="form-control" id="jobtitle" name="jobtitle" placeholder="Job Titel" value="<?php echo $user['job_title'];?>"> 
                    <label for="jobtitle">Job Titel</label>
                </div>
                <div class="form-floating col-md-8" style="display: flex; justify-content: flex-end;">
                    <button type="submit" name="save" class="btn save" id="save">Gem</button>
                </div>
            </form>
        </div>
        <?php 
        if($noBudgets){?>
            <div class="col-md-6 budgets_column">
                <p>Der er ingen budgetter endnu.</p>
            </div>
        <?php }else{ ?>
            <div class="col-md-6 budgets_column">
                <?php 
                foreach($budgets as $budgetId => $budget){
                    if($budget['favorite'] == '1'){
                        $budgetName = $budget['name'];
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
                            <a href="<?php echo BASE_URL; ?>/html/budgets/budget_page.php?budget=<?php echo $budgetId; ?>" class="budget_link">
                                <div class="budgets col-md-12">
                                    <div class="budget_name col-md-12">
                                        <?php echo $budgetName; ?>
                                    </div>
                                    <div class="budget_inf col-md-12">
                                        <?php if(!$budget['fixed']){?>
                                        <div class="progress">
                                            <div class="progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $percentageUsed;?>" style="width:<?php echo $percentageUsed;?>%;" aria-valuemin="0" aria-valuemax="100"><?php echo $budgetUsedAmount . " / " . $budgetTotalAmount . " DKK";?></div>
                                        </div>
                                        <?php }else{}?>
                                    </div>
                                    <?php if ($allBudgetUsers != ''){ ?>
                                    <div class="budget_shared col-md-12">
                                        <div class="shared_text">Delt med: <?php echo $allBudgetUsers;?></div>
                                    </div>
                                    <?php }?>
                                </div>
                            </a>
                        </div>
                    <?php }
                } ?>
            </div>
        <?php }
        ?>
    </div>
</body>
</html>