<?php 
include "../../main.php";

$email = $_SESSION['email']; // Get the users email
if(isset($_GET['month'])){
    $month = $_GET['month'];
}
if(isset($_GET['budget'])){
    $budgetId = $_GET['budget'];
}

$users = get($con, 'budget_items', "budget_id = " . $budgetId . " AND startdate LIKE '" . $month . "%'", "sum(budget_items.amount) AS total, users.first_name", "budget_items.user_id", "INNER JOIN users ON users.user_id = budget_items.user_id");

foreach ($users as $user) {
}
$items = get(
    $con,
    'budget_items',
    "budget_id = " . $budgetId . " AND startdate LIKE '" . $month . "%'",
    "budget_items.*, `users`.`first_name`",
    "budget_items.id",
    "INNER JOIN users ON users.user_id = budget_items.user_id"
);

$total = 0.00;
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
        <div class="table_box col-md-2">
            <table class="table">
                <thead class="thead-dark">
                    <tr class="table-primary">
                        <th>Person</th>
                        <th>Total Beløb</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($users as $user) {
                        $u_name = $user["first_name"];
                        $u_total = $user['total'];
                        echo "<tr class='table-info'>";
                            echo "<td>$u_name</td>";
                            echo "<td>$u_total</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="table_box col-md-10">
            <div class="fixed_items">
            <table class="table table-striped table-dark">
                <thead>
                    <tr>
                        <th>Beskrivelse</th>
                        <th>Beløb</th>
                        <th>Dato</th>
                        <th>Person</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item) { ?>
                    <tr>
                        <td><?= $item['name']; ?></td>
                        <td><?= $item['amount']; ?></td> <?php $total += $item['amount']; ?>
                        <td><?= date('d-m-Y', strtotime($item['startdate'])); ?></td>
                        <td><?= $item['first_name']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td>Total</td>
                        <td><?=$total; ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </div>
        </div>
    </div>  
</body>
</html>