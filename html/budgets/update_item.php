<?php 
include "../../main.php";

$email = $_SESSION['email']; // Get the users email
if(isset($_GET['itemId'])){
    $itemId = $_GET['itemId'];
    $items = new budgetItem();
    $item = $items->getBudgetItemById($itemId);
    $budget_id = $item['budget_id'];
    $item_name = $item['name'];
    $item_amount = $item['amount'];
    $item_start = date('Y-m-d', strtotime($item['startdate']));
    if(is_null($item['enddate'])){
        $item_end = 0000-00-00;
    }else{
        $item_end = date('Y-m-d', strtotime($item['enddate']));
    }

    $budgets = new Budget();
    $budget = $budgets->getBudgetById($budget_id);
    $budget_name = $budget['name'];
}


$updated = "";
$name = "";
if(isset($_GET['updated']) && $_GET['updated'] != ""){
    $updated = $_GET['updated'];
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
        <form action="../../config/functions/budget_items.php" method='POST' class="add_item_form">
            <input type="hidden" class="form-control" id="fixed" name="fixed" value="<?php echo $budget['fixed']; ?>">
            <input type="hidden" class="form-control" id="budget_id" name="budget_id" value="<?php echo $budget_id; ?>">
            <input type="hidden" class="form-control" id="item_id" name="item_id" value="<?php echo $itemId; ?>">
                <div class="col-md-6"> 
                    <?php if ($updated === "true"){ 
                        echo "<p style='color: green;'>" . $item_name . " blev opdateret </p>";
                    }elseif($updated === "false"){
                        echo "<p style='color: red;'>" . $item_name . " blev ikke opdateret </p>";
                    } ?>
                </div>
                <h2>Opdatér <?php echo $item_name; ?></h2>
                <div class="col-md-6">
                    <div class="form-floating col-md-6">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Udgift navn" value="<?php echo $item_name; ?>">
                        <label for="name">Udgift navn</label>
                    </div>
                    <div class="form-floating col-md-6">
                        <input type="text" class="form-control" id="amount" name="amount" placeholder="Beløb" value="<?php echo $item_amount; ?>">
                        <label for="amount">Beløb</label>
                    </div>
                    <div class="form-floating col-md-6">
                        <input type="date" class="form-control" id="start" name="start" placeholder="Start Dato" value="<?php echo $item_start; ?>">
                        <label for="start">Start Dato</label>
                    </div>
                    <div class="form-floating col-md-6">
                        <input type="date" class="form-control" id="end" name="end" placeholder="Slut Dato" value="<?php echo $item_end; ?>">
                        <label for="end">Slut Dato</label>
                    </div>
                    <div class="form-floating col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="no_end" name="no_end" <?php if($item['no_end'] === 1){echo "checked";} ?>>
                            <label class="form-check-label" for="no_end">Ingen slut dato</label>
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
