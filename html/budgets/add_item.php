<?php 
include "../../main.php";

$email = $_SESSION['email']; // Get the users email
if(isset($_GET['budget'])){
    $budgetId = $_GET['budget'];
}
$created = "";
$name = "";
if(isset($_GET['created']) && $_GET['created'] != ""){
    $created = $_GET['created'];
    $name = $_GET['name'];
}
$budgets = getBudget($con,$budgetId); // Get budget that fits the budgetId
$budget_name = $budgets['name'];
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
        <form action="../../config/functions/budget_items.php" method='POST' class="add_item_form">
            <input type="hidden" class="form-control" id="fixed" name="fixed" value="<?php echo $budgets['fixed']; ?>">
            <input type="hidden" class="form-control" id="budget_id" name="budget_id" value="<?php echo $budgetId; ?>">
                <div class="col-md-6"> 
                    <?php if ($created === "true"){ 
                        echo "<p style='color: green;'>" . $name . " blev tilføjet til " . $budget_name . "</p>";
                    }elseif($created === "false"){
                        echo "<p style='color: red;'>" . $name . " blev ikke tilføjet til " . $budget_name . "</p>";
                    } ?>
                </div>
                <h2>Tilføj til <?php echo $budget_name; ?></h2>
                <div class="col-md-6">
                    <div class="form-floating col-md-6">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Udgift navn">
                        <label for="name">Udgift navn</label>
                    </div>
                    <div class="form-floating col-md-6">
                        <input type="text" class="form-control" id="amount" name="amount" placeholder="Beløb">
                        <label for="amount">Beløb</label>
                    </div>
                    <div class="form-floating col-md-6">
                        <input type="date" class="form-control" id="start" name="start" placeholder="Start Dato">
                        <label for="start">Start Dato</label>
                    </div>
                    <div class="form-floating col-md-6">
                        <input type="date" class="form-control" id="end" name="end" placeholder="Slut Dato">
                        <label for="end">Slut Dato</label>
                    </div>
                    <div class="form-floating col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="no_end" name="no_end">
                            <label class="form-check-label" for="no_end">Ingen slut dato</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating col-md-6">
                        <button type="submit" class="btn save" name="create" id="create">Tilføj</button>
                    </div>
                </div>
        </form>
    </div>
</body>
</html>