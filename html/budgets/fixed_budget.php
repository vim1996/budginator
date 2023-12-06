<?php 
$primary_user = false;
$user_id = $_SESSION['user_id']; // USER ID
$budgetItems = getBudgetItems($con, $budgetId);
$user = getUser($con, $email);

$sql = "SELECT min(startdate) AS start FROM budget_items WHERE budget_id = $budgetId AND afstemt != 1";
$dates = array();
$startdate = date('d-m-Y', strtotime(mysqli_fetch_assoc(mysqli_query($con, $sql))['start']));
$enddate = date('d-m-Y');
// Gets the earliest date from all the items in the selected budget, then gets the current date, and then using that to get all the months between that date, and today.
$period = new DatePeriod(new DateTimeImmutable($startdate), new DateInterval('P1M'), new DateTimeImmutable($enddate));
foreach ($period as $date) {
    $dates[] = $date->format('m-Y'); // Insert the month and year to dates
}
$totalArray = array();
$startdate = date_create_from_format('d-m-Y', $startdate)->format('Y-m-d');
$enddate = date_create_from_format('d-m-Y', $enddate)->format('Y-m-d');
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
//$amount_of_months = date_diff(date_create($start), date_create($current_date))->format('%m') + 1;
foreach ($budgetMonths as $monthData) { if (isset($monthData['total'])) { $totalValueMonth[] = $monthData['total']; } }
// Use array_values to reindex the array if needed

$b = new Budget($con);
$b_users = $b->getUsers($budgetId);
foreach ($b_users as $b_user) {
    if ($b_user['user_id'] == $user_id && $b_user['primary_user'] == '1') {
        $primary_user = true;
    }
}

$afstemt = "";
if(isset($_GET["afstemt"])){
    $afstemt = $_GET["afstemt"];
    $afstemtDate = date_create_from_format('Y-m', $afstemt)->format('M-y');
}
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
        a {
            text-decoration: none;
            color:white;
        }
        a:hover {
            color:white;
        }
    </style>
</head>
<body>
    <div>
        <?php
        if($updated){?>
            <div class="col-md-12">
                <div style="text-align: center; color: green;"><?=$budget['name'];?> blev opdatéret</div>
            </div>
        <?php }elseif($afstemt !== ''){ ?>
            <div class="col-md-12">
                <div style="text-align: center; color: green;"><?=$afstemtDate;?> er blevet afstemt</div>
            </div>
        <?php } ?>
        <div class="col-md-12">
            <div class="budget_title"><?=$budget['name'];?></div>
        </div>
    </div>
    <div class="container budget_information">
        <div class="col-md-10" style="display: flex; justify-content: space-between; margin: 0 auto;">
            <div class="col-md-6" style="margin-bottom: 5px; text-align: left;">
                <a href="../budgets.php" name="back" class="btn save" id="back">Tilbage</a>
            </div>
            <div class="col-md-6" style="margin-bottom: 5px; text-align: right; display: flex; flex-direction: row; justify-content: flex-end;">
                <div class="gear_setting" id="gearIcon" onclick="togglePopupMenu()"><i class="bi bi-gear-fill"></i></div>
                <a href="add_item_fixed.php?budget=<?=$budgetId;?>" name="insert_item" class="btn create" id="insert_item">Indsæt linje</a>
            </div>
            
        </div>
        <!-- Pop-up menu -->
        <div class="popup-menu" id="popupMenu">
            <!-- Add your menu options here -->
            <a href="">Rediger budget</a>
            <a href="share_budget.php?budget=<?=$budgetId?>">Del budget</a>
            <?php if($primary_user){?> 
                <a href="" id="delete-budget" onclick="handleDeleteBudget()">Slet budget</a>
            <?php } ?>
        </div>
        <div class="table_box col-md-10" id="table_container" style="display:inline-block;">
            <table class="table table-striped table-dark" id="scrollable_table">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <?php
                            foreach ($dates as $date) {
                                $formattedDate = date_create_from_format('m-Y', $date)->format('M-y');
                                $fDate = date_create_from_format('m-Y', $date)->format('Y-m');
                                echo "<th><a href='budget_month_page.php?month=$fDate&budget=$budgetId'>$formattedDate</a></th>";
                            }
                        ?>
                    </tr>
                </thead>
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
                        <td><?php echo $budget['total_amount']; ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td>Diff</td>
                        <?php  foreach ($totalValueMonth as $total) { 
                            $diff = $budget['total_amount'] - $total;
                            $color = $diff > 0 ? "green" : "red";
                            ?>
                        <td style="color:<?php echo $color; ?>"><?php echo $budget['total_amount'] - $total; ?></td>
                        <?php } ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- The Modal -->
    <div id="customModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModalBtn">&times;</span>
            <p style="text-align:center;">Er du sikker på du vil slette budget <?=$budget['name'];?>?</p>
            <div style="display:flex; justify-content: center;">
                <button id="confirmBtn" class="btn save">Ja</button>
                <button id="cancelBtn" class="btn delete">Nej</button>
            </div>
            <div id="modal_data" data-budget-id="<?=$budgetId;?>"></div>
        </div>
    </div>

    <script src="../../Javascript/budget_page.js"></script>


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