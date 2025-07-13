<?php
require_once "db.php";

class AccountManagerSQL {
    private $db;

    public function __construct() {
        $this->db = getDatabaseConnection();
    }

    public function getAccounts() {
        $stmt = $this->db->query("
            SELECT 
                a.id, 
                a.name, 
                a.ip, 
                a.serial,
                (SELECT su.last_login_ip FROM serialusage su WHERE su.userid = a.id ORDER BY su.last_login_date DESC LIMIT 1) as last_ip,
                (SELECT su.serial FROM serialusage su WHERE su.userid = a.id ORDER BY su.last_login_date DESC LIMIT 1) as last_serial
            FROM accounts a
            ORDER BY a.id ASC
        ");
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
            ':password' => $password
        ]);
    }

    public function changePassword($id, $newPassword) {
        $stmt = $this->db->prepare("UPDATE accounts SET password = :password WHERE id = :id");
        return $stmt->execute([
            ':password' => $newPassword,
            ':id' => $id
        ]);
    }
	public function getConnection() {
    return $this->db;
}

}

?>
