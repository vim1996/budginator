<?php 



function emptyInputSignup($email, $password, $rePassword, $name, $lastname){
    $result;
    if(empty($email) || empty($password) || empty($rePassword) || empty($name) || empty($lastname)){
        $result = true;
    }else{
        $result = false;
    }

    return $result;

}

function invalidEmail($email){
    $result;
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $result = true;
    }else{
        $result = false;
    }

    return $result;
}

function passwordMatch($password, $rePassword){
    $result;
    if($password !== $rePassword){
        $result = true;
    }else{
        $result = false;
    }

    return $result;
}

function emailExsists($con, $email){
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_stmt_init($con);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header('location:' . BASE_URL . '/html/signup.php?error=stmtFailed');
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)){
        mysqli_stmt_close($stmt);
        return $row;
    }else{
        $result = false;
        mysqli_stmt_close($stmt);
        return $result;
    }
}

function createUser($con, $email, $password, $first_name, $last_name){
    $sql = "INSERT INTO users (first_name,last_name,email,`password`) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($con);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header('location: ../../html/signup.php?error=stmtFailed');
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hashes the password before saving in the database

    mysqli_stmt_bind_param($stmt, "ssss", $first_name, $last_name, $email, $hashedPassword);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Get the user_id of the newly inserted user
        $user_id = mysqli_insert_id($con);
        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['email'] = $email;
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        header('location: ../../main.php?userCreated');
        exit();
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        header('location: ../../main.php?failedCreateUser');
        exit();
    }
}

function login($con, $email, $password){
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_stmt_init($con);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header('location: ../../main.php?error=stmtFailed');
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    if(mysqli_num_rows($result = mysqli_stmt_get_result($stmt)) > 0){
        $row = mysqli_fetch_assoc($result); // User was found, you can fetch the data if needed
        $checkpass = password_verify($password, $row['password']);
        if($checkpass === false){ // checks if the password is false
            mysqli_stmt_close($stmt);
            mysqli_close($con);
            header('location: ../../main.php?error=wrongPass');
            exit();
        }elseif($checkpass === true){
            session_start();
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            mysqli_stmt_close($stmt);
            mysqli_close($con);
            header('location: ../../html/profile.php');
            exit();
        }
    }else{
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        header('location: ../../main.php?error=noUser');
        exit();
    }
}

function getUserId($con,$email){
    $sql = "SELECT user_id FROM users WHERE email = ?";
    $stmt = mysqli_stmt_init($con);

    if(!mysqli_stmt_prepare($stmt, $sql)){ // Check if user exsist
        header('location: ../../main.php?error=stmtFailed');
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    if(mysqli_num_rows($result = mysqli_stmt_get_result($stmt)) > 0){
        $row = mysqli_fetch_assoc($result);
        $userId = $row['user_id'];
        return $userId;
    }else{
        return false;
    }
}

function getBudgets($con,$email){
    $userId = getUserid($con,$email);
    if($userId !== false){
        $budgets = [];
        $sql = "SELECT * FROM budget_users bu
        INNER JOIN budget b ON b.budget_id = bu.budget_id
        WHERE bu.user_id = ?";
        $stmt = mysqli_stmt_init($con);
        if(!mysqli_stmt_prepare($stmt, $sql)){ // Check if user exsist
            header('location: ../../main.php?error=stmtFailed');
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);

        if(mysqli_num_rows($result = mysqli_stmt_get_result($stmt)) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $budgetId = $row['budget_id'];
                $name = $row['name'];
                $totalAmount = $row['total_amount'];
                $usedAmount = $row['used_amount'];
                $fixed = $row['fixed'];
                $favorite = $row['favorite'];
                $deleted = $row['deleted'];
                $budget_id = $row['budget_id'];

                $budget = [ // Create an associative array for the current budget
                    "name" => $name,
                    "totalAmount" => $totalAmount,
                    "usedAmount" => $usedAmount,
                    "fixed" => $fixed,
                    "favorite" => $favorite,
                    "deleted" => $deleted,
                    "budget_id" => $budget_id
                ];

                $budgets[$budgetId] = $budget; // Store the budget array in the $budgets array using the budget ID as key
            }
            return $budgets;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function getBudgetUsers($con, $budgetId){
    $budgetUsers = []; // Initialize an empty array
    $sql = "SELECT bu.*, u.first_name FROM budget_users bu 
        INNER join users u ON u.user_id = bu.user_id 
        WHERE budget_id = ?";
    $stmt = mysqli_stmt_init($con);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header('location: ../../main.php?error=stmtFailed');
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $budgetId);
    mysqli_stmt_execute($stmt);
    if(mysqli_num_rows($result = mysqli_stmt_get_result($stmt)) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $usersName = $row['first_name'];
            $primary = $row['primary_user'];
            $budgetUsers[$usersName] = $primary; // Add user_id to the $budgetUsers array
        }
        return $budgetUsers;
    }
    return $budgetUsers;
}


function getBudget($con, $budgetId){
    // Gets a specific budget
    $row = array();
    $sql = "SELECT * FROM budget WHERE budget_id = ?";
    $stmt = mysqli_stmt_init($con);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header('location: ../../main.php?error=stmtFailed');
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $budgetId);
    mysqli_stmt_execute($stmt);
    if(mysqli_num_rows($result = mysqli_stmt_get_result($stmt)) > 0){
        $row = mysqli_fetch_assoc($result);
        return $row;
    }else{
        return $row;
    }
}

function createBudget($con, $data){
    $sql = "INSERT INTO budget (name,user_id,total_amount,fixed) VALUES (?,?,?,?)";
    $stmt = mysqli_stmt_init($con);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        return false;
    }
    $name = $data['name'];
    $user_id = $data['user_id'];
    $total_amount = $data['total_amount'];
    $fixed = $data['fixed'];
    mysqli_stmt_bind_param($stmt, "siii", $name, $user_id, $total_amount, $fixed);
    mysqli_stmt_execute($stmt);
    if(mysqli_stmt_affected_rows($stmt) > 0){
        $budget_id = mysqli_insert_id($con);
        if(createBudgetUser($con,$budget_id,$user_id)){
            return true;
        }else{
            deleteBudget($con,$budget_id);
            return false;
        }
    }else{
        return false;
    }
}

function deleteBudget($con, $budget_id){
    $sql = "DELETE FROM budget WHERE budget_id = ?";
    $stmt = mysqli_stmt_init($con);
    if(mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $budget_id);
        mysqli_stmt_execute($stmt);
    }
}

function createBudgetUser($con,$budget_id,$user_id,$primary = 1){
    $sql = "INSERT INTO budget_users (budget_id,user_id,primary_user) VALUES (?,?,?)";
    $stmt = mysqli_stmt_init($con);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        return false;
    }
    mysqli_stmt_bind_param($stmt, "iii", $budget_id, $user_id, $primary);
    mysqli_stmt_execute($stmt);
    if(mysqli_stmt_affected_rows($stmt) > 0){
        return true;
    }else{
        return false;
    }
}

function getBudgetItems($con, $budgetId){ // Gets the items from a budget
    $budgetItems = [];
    $sql = "SELECT * FROM budget_items WHERE budget_id = ?";
    $stmt = mysqli_stmt_init($con);
    if(!mysqli_stmt_prepare($stmt, $sql)){ 
        header('location: ../../main.php?error=stmtFailed');
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $budgetId);
    mysqli_stmt_execute($stmt);
    if(mysqli_num_rows($result = mysqli_stmt_get_result($stmt)) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $items = [
                "amount"=> $row['amount'],
                "used_amount"=> $row['used_amount'],
                "budget_id"=> $row['budget_id'],
                "startdate"=> $row['startdate'],
                "enddate"=> $row['enddate'],
                "no_end"=> $row['no_end'],
                "id"=>$row['id'],
            ];
            $budgetItems[$row['name']] = $items;
        }
        return $budgetItems;
    }else{
        return $budgetItems;
    }
}

function getTotalBudget($con, $budgetId, $start = null, $end = null){
    if($start != null && $end != null){
        $startdate = new DateTime("$start");
        $sdate = date('d-m-Y', strtotime($start));
        $sdate = date_create_from_format('d-m-Y', $sdate)->format('Y-m-d');
        $enddate = new DateTime("$end");
        $interval = $startdate->diff($enddate);
        $amountMonths = $interval->y * 12 + $interval->m;
    }else{
        $amountMonths = 12;
        $startdate = date('Y-m'); // Default to the current month if $start is not provided
    }
    $budgetItems = getBudgetItems($con, $budgetId); // Get all the budget items
    $totalArray = array();
    $monthsArray = array(); // Initialize an array to store the months
    for ($i = 0; $i <= $amountMonths; $i++) { // Loop through the months
        //$month = (new DateTime($startdate))->modify('+' . $i . ' months');
        $month = date('Y-m', strtotime("$sdate +$i months")); // Calculate the month for the current iteration
        $monthsArray[] = $month; // Add the month to the array

        foreach($monthsArray as $month){
            $month = date('Y-m',strtotime($month));
            $itemValue = 0;
            $itemsArray = array ();
            foreach($budgetItems as $item => $value){
                $startdate = date('Y-m',strtotime($value['startdate']));
                $enddate = date('Y-m',strtotime($value['enddate']));
                if($startdate <= $month && ($enddate >= $month || $value['no_end'] === 1)){
                    //echo "Name: " . $item . " -- Value: " . $value['amount'] . "<br>";
                    $itemValue += $value['amount'];
                    $itemsArray[$item] = array(
                        'value' => $value['amount'],
                        'id' => $value['id'],
                    );
                }
            }
            $itemsArray['total'] = $itemValue;
            $totalArray[$month] = $itemsArray;
        }

    }
    return $totalArray;
}

function getUser($con, $email){
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_stmt_init($con);
    if(!mysqli_stmt_prepare($stmt, $sql)){ 
        header('location: ../../main.php?error=stmtFailed');
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    if(mysqli_num_rows($result = mysqli_stmt_get_result($stmt)) > 0){
        $row = mysqli_fetch_assoc($result);
        return $row;
    }else{
        return array('No users');
    }
}


function get ($con, $table, $where, $select = '*', $groupBy = null, $join = null){
    $sql = "SELECT " . $select .  " FROM " . $table;
    if(!is_null($join)){
        $sql .= " ". $join;
    }
    $sql.= " WHERE " . $where;
    if(!is_null($groupBy)){
        $sql .= " GROUP BY " . $groupBy;
    }
    $stmt = mysqli_stmt_init($con);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        return array();
    }
    if(mysqli_stmt_execute($stmt)){
        if(mysqli_num_rows($result = mysqli_stmt_get_result($stmt)) > 0){
            $rows = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
            return $rows;
        }else{
            return array();
        }
    }
}

function createBudgetItem($con, $name, $amount, $budget_id, $startdate, $user_id, $enddate = NULL, $no_end = 0 , $used_amount = 0){
    $sql = "INSERT INTO budget_items (name,amount,used_amount,budget_id,startdate,enddate,no_end,user_id) VALUES(?,?,?,?,?,?,?,?)";
    $stmt = mysqli_stmt_init($con);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        return false;
    }
    mysqli_stmt_bind_param($stmt, "sddissii", $name, $amount, $used_amount, $budget_id, $startdate, $enddate, $no_end, $user_id);
    mysqli_stmt_execute($stmt);
    if(mysqli_stmt_affected_rows($stmt) > 0){
        return true;
    }else{
        return false;
    }
}
?>