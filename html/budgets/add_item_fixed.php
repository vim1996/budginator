<?php 
include "../../main.php";
$email = $_SESSION['email']; // Get the users email
$user_id = $_SESSION['user_id'];
if(isset($_GET['budget'])){
    $budgetId = $_GET['budget'];
}

$add = 0;
$name = "";
if(isset($_POST['add'])){
    $date = $_POST['date'];
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $budget_id = $_POST['budget_id'];
    $item = new BudgetItem();
    if($item->createBudgetItem($name, $amount, 0, $budget_id, $date, $date, 0, $user_id)){
        $add = 1;
    }else{
        $add = 2;
    }

}

$item = new BudgetItem();
print_r($item->getBudgetItemById(1));
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
        <form action="" method='POST' class="add_item_form">
            <input type="hidden" name="budget_id" id="budget_id" value="<?=$budgetId;?>">
                <div class="col-md-6"> 
                    <?php if ($add == 1){ 
                        echo "<p style='color: green;'>" . $name . " blev tilføjet </p>";
                    }elseif($add == 2){
                        echo "<p style='color: red;'>" . $name . " blev ikke tilføjet </p>";
                    } ?>
                </div>
                <h2>Indskriv forbrug</h2>
                <div class="col-md-6">
                    <div class="form-floating col-md-6">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Beskrivelse">
                        <label for="name">Beskrivelse</label>
                    </div>
                    <div class="form-floating col-md-6">
                        <input type="text" class="form-control" id="amount" name="amount" placeholder="Beløb">
                        <label for="amount">Beløb</label>
                    </div>
                    <div class="form-floating col-md-6">
                        <input type="date" class="form-control" id="date" name="date" placeholder="Dato">
                        <label for="date">Dato</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating col-md-6">
                        <button type="submit" class="btn save" name="add" id="add">Tilføj</button>
                    </div>
                </div>
        </form>
    </div>  
</body>
</html>