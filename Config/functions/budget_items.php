<?php 
include "../../main.php";
$budget_item = new BudgetItem();

if(isset($_POST['create'])){
    $name = $_POST['name'];
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $fixed = $_POST['fixed'];
    $budget_id = $_POST['budget_id'];
    $used_amount = 0;
    if(isset($_POST['no_end']) && $_POST['no_end'] === 'on'){
        $no_end = 1;
        $end = null;
    }else{
        $no_end = 0;
    }
    $result = $budget_item->createBudgetItem($name,$amount,$used_amount,$budget_id,$start,$end,$no_end,$user_id);
    if($result){
        header('location: ../../html/budgets/add_item.php?budget= ' . $budget_id . '&created=true&name=' . $name);
    }else{
        header('location: ../../html/budgets/add_item.php?budget= ' . $budget_id . 'created=false&name=' . $name);
    }
    
}

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $fixed = $_POST['fixed'];
    $budget_id = $_POST['budget_id'];
    $item_id = $_POST['item_id'];
    $used_amount = 0;
    if(isset($_POST['no_end']) && $_POST['no_end'] === 'on'){
        $no_end = 1;
        $end = null;
    }else{
        $no_end = 0;
    }
    $result = $budget_item->updateBudgetItem($item_id,$name,$amount,$used_amount,$budget_id,$start,$end,$no_end);
    if($result){
        header('location: ../../html/budgets/update_item.php?budget= ' . $budget_id . '&updated=true&itemId=' . $item_id);
    }else{
        header('location: ../../html/budgets/update_item.php?budget= ' . $budget_id . 'updated=false&itemId=' . $item_id);
    }
    
}

?>