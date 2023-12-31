<?php 

class BudgetItem {
    private $db;
    public function __construct($con) {
        $this->db = $con;
    }

    // Create a new budget item
    public function createBudgetItem($name, $amount, $budget_id, $startdate, $enddate, $user_id, $used_amount = 0, $no_end = 0) {
        $sql = "INSERT INTO budget_items (name, amount, used_amount, budget_id, startdate, enddate, no_end, user_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sddissii", $name, $amount, $used_amount, $budget_id, $startdate, $enddate, $no_end, $user_id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Update an existing budget item
    public function updateBudgetItem($id, $name, $amount, $used_amount, $budget_id, $startdate, $enddate, $no_end) {
        $sql = "UPDATE budget_items 
                SET name = ?, amount = ?, used_amount = ?, budget_id = ?, startdate = ?, enddate = ?, no_end = ? 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sddissii", $name, $amount, $used_amount, $budget_id, $startdate, $enddate, $no_end, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Delete a budget item by ID
    public function deleteBudgetItem($id) {
        $sql = "DELETE FROM budget_items WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Retrieve budget item details by ID
    public function getBudgetItemById($id) {
        $sql = "SELECT * FROM budget_items WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }
    public function afstemBudgetItem($where) {
        $sql = "UPDATE budget_items SET afstemt = 1 WHERE " . $where;
        $result = mysqli_query($this->db, $sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}

?>