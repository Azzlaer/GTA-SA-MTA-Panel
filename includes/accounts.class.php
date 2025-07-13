<?php
require_once "db.php";

class AccountManagerSQL {
    private $db;

    public function __construct() {
        $this->db = getDatabaseConnection();
    }

    public function getAccounts() {
        $stmt = $this->db->query("SELECT id, name, ip, serial FROM accounts ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function accountExists($username) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM accounts WHERE LOWER(name) = LOWER(:name)");
        $stmt->execute([':name' => $username]);
        return $stmt->fetchColumn() > 0;
    }

    public function createAccount($username, $password) {
        if ($this->accountExists($username)) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO accounts (name, password, ip, serial, httppass) VALUES (:name, :password, '', '', '')");
        return $stmt->execute([
            ':name' => $username,
            ':password' => $password // Ya debe venir en SHA-256
        ]);
    }
}
?>
