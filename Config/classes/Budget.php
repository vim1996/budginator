<?php 
class Budget {
    private $db;
    public function __construct($con) {
        $this->db = $con;
    }

    // Create a new budget row
    public function createBudget($name, $user_id, $total_amount, $used_amount, $fixed) {
        $sql = "INSERT INTO budget (name, user_id, total_amount, used_amount, fixed) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sddii", $name, $user_id, $total_amount, $used_amount, $fixed);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Update an existing budget row
    public function updateBudget($budget_id, $name, $user_id, $total_amount, $used_amount, $fixed) {
        $sql = "UPDATE budget 
                SET name = ?, user_id = ?, total_amount = ?, used_amount = ?, fixed = ? 
                WHERE budget_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sddiii", $name, $user_id, $total_amount, $used_amount, $fixed, $budget_id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Delete a budget row by budget_id
    public function deleteBudget($budget_id) {
        $sql = "DELETE FROM budget WHERE budget_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $budget_id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Retrieve budget details by budget_id
    public function getBudgetById($budget_id) {
        $sql = "SELECT * FROM budget WHERE budget_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $budget_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }
}


?>