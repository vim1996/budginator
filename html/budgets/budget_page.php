<?php 
include "../../main.php";

$email = $_SESSION['email']; // Get the users email
$budgetId = $_GET['budget'];
$budgets = getBudget($con,$budgetId); // Get all budgets connected to the user
$budgetItems = getBudgetItems($con, $budgetId);
$user = getUser($con, $email);
$currentMonth = date('m'); // Get the current month
$monthsArray = array(); // Initialize an array to store the months
for ($i = 0; $i <= 12; $i++) { // Loop through the months
    $month = date('M-y', strtotime("+$i months")); // Calculate the month for the current iteration
    $monthsArray[] = $month; // Add the month to the array
}

$totalArray = array();

$uStartDate = date("m-Y");
// Add 24 months to the current date
$uEndDate = date("m-Y", strtotime("+24 months"));
$startdate = date_create_from_format('m-Y', $uStartDate)->format('Y-m');
$enddate = date_create_from_format('m-Y', $uEndDate)->format('Y-m');
$budgetMonths = getTotalBudget($con, $budgetId, $startdate, $enddate);
$uniqueItems = []; // Initialize an empty array to store unique items (categories)
$uniqueDates = []; // Initialize an empty array to store unique dates
$totalValueMonth = []; // Initialize an empty array to store unique dates

foreach ($budgetMonths as $monthData) { 
    foreach ($monthData as $category => $amount) {
        if ($category !== 'total') {
            $uniqueItems[$amount['id']] = $category;
        } 
    } 
}
foreach ($budgetMonths as $monthData) { if (isset($monthData['total'])) { $totalValueMonth[] = $monthData['total']; } }
// Use array_values to reindex the array if needed

// Now, $uniqueItems contains all the unique items (categories) excluding "total"
foreach (array_keys($budgetMonths) as $yearMonth) { $uniqueDates[] = $yearMonth; } // Loop through the keys of $budgetMonths to find unique dates
echo "<br><br>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budgets</title>
    <style>
        .table-dark thead th {
            position: sticky;
            top: 0;
            min-width: 75px;
        }
        td:nth-child(1) {
            min-width: 155px;
            position: sticky;
            left: 0;
        }
        .table-dark thead th:nth-child(1) {
            left: 0;
            z-index: 1; /* added */
        }
        .table-dark{
            margin-bottom: 0px;
        }
    </style>
</head>
<body>
    <div class="container budget_information">
        <div class="col-md-10" style="display: flex; justify-content: space-between; margin: 0 auto;">
            <div class="col-md-6" style="margin-bottom: 5px; text-align: left;">
                <a href="../budgets.php" name="back" class="btn save" id="back">Tilbage</a>
            </div>
            <div class="col-md-6" style="margin-bottom: 5px; text-align: right;">
                <a href="add_item.php?budget=<?php echo $budgetId ?>" name="insert_item" class="btn create" id="insert_item">Indsæt linje</a>
            </div>
        </div>
        <div class="table_box col-md-10" id="table_container" style="display:inline-block;">
            <table class="table table-striped table-dark" id="scrollable_table">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <?php 
                            foreach ($uniqueDates as $dates) {
                                $formattedDate = date('M-y', strtotime($dates));
                                echo "<th>$formattedDate</th>";
                            }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($uniqueItems as $item_id => $items) {
                        echo "<tr>";
                            echo "<td style='z-index: 1;'>";
                                echo "<a style='color:white;' href='update_item.php?itemId=$item_id'>$items</a>";
                            echo "</td>";
                            foreach ($budgetMonths as $yearMonth => $budgetData) {
                                echo "<td>";
                                foreach ($budgetData as $category => $amount) {
                                    if ($items == $category) {
                                        foreach ($amount as $key => $value) {
                                            if ($key == 'value') {
                                                echo $value . "<br>"; // Echo only the 'value' key
                                            }
                                        }
                                    }
                                }
                                echo "</td>";
                            }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr style="--bs-table-bg: #5d3a3b;">
                        <td>Total</td>
                        <?php  foreach ($totalValueMonth as $total) { ?>
                        <td><?php echo $total; ?></td>
                        <?php } ?>
                    </tr>
                    <tr style="--bs-table-bg: #2d452c;">
                        <td>Income</td>
                        <?php  foreach ($totalValueMonth as $total) { ?>
                        <td><?php echo $user['income']; ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td>Diff</td>
                        <?php  foreach ($totalValueMonth as $total) { 
                            $diff = $user['income'] - $total;
                            $color = $diff > 0 ? "green" : "red";
                            ?>
                        <td style="color:<?php echo $color; ?>"><?php echo $user['income'] - $total; ?></td>
                        <?php } ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <script>
        // Get references to the table container and the scrollable table
        const table_container = document.querySelector('#table_container');

        let startY;
        let startX;
        let scrollLeft;
        let scrollTop;
        let isDown;
        table_container.addEventListener('mousedown',e => mouseIsDown(e));  
        table_container.addEventListener('mouseup',e => mouseUp(e))
        table_container.addEventListener('mouseleave',e=>mouseLeave(e));
        table_container.addEventListener('mousemove',e=>mouseMove(e));
        function mouseIsDown(e){
            isDown = true;
            startY = e.pageY - table_container.offsetTop;
            startX = e.pageX - table_container.offsetLeft;
            scrollLeft = table_container.scrollLeft;
            scrollTop = table_container.scrollTop; 
        }
        function mouseUp(e){
            isDown = false;
        }
        function mouseLeave(e){
            isDown = false;
        }
        function mouseMove(e){
            if(isDown){
                e.preventDefault();
                //Move vertcally
                const y = e.pageY - table_container.offsetTop;
                const walkY = y - startY;
                table_container.scrollTop = scrollTop - walkY;

                //Move Horizontally
                const x = e.pageX - table_container.offsetLeft;
                const walkX = x - startX;
                table_container.scrollLeft = scrollLeft - walkX;

            }
        }
    </script>
</body>
</html>