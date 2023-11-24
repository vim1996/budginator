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
    $amount = round($amount, 2);
    $budget_id = $_POST['budget_id'];

    $fixed_budget_item = new BudgetItem($con);
    $fixed_budget_item->createBudgetItem($name, $amount, $budget_id, $date, $date, $user_id);
    if($fixed_budget_item){    
        $add = 1;
    }else{
        $add = 2;
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
        <form action="" method='POST' class="add_item_form" id="budgetForm">
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('budgetForm').addEventListener('submit', function (event) {
                // Get form values
                var name = document.getElementById('name').value;
                var amount = document.getElementById('amount').value;
                var date = document.getElementById('date').value;

                // Check if fields are filled
                if (name.trim() === '' || amount.trim() === '' || date.trim() === '') {
                    // Prevent form submission
                    event.preventDefault();
                    alert('Alle felter skal udfyldes');
                }
            });
        });
    </script>
</body>
</html>