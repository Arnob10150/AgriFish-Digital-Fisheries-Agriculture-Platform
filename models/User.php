<?php

require_once 'database.php';

class User {
    private $db;

    public function __construct() {
        try {
            $this->db = getDB();
        } catch (Exception $e) {
            $this->db = null;
        }
    }

    public function findByMobile($mobile) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE mobile_number = ?");
        $stmt->execute([$mobile]);
        return $stmt->fetch();
    }

    public function findByEmail($email) {
        if (!$this->db) return null;

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findByName($name) {
        if (!$this->db) return null;

        $stmt = $this->db->prepare("SELECT * FROM users WHERE full_name = ?");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    public function create($data) {
        if (!$this->db) return false;

        $stmt = $this->db->prepare("
            INSERT INTO users (email, password, mobile_number, nid, full_name, role, location, language_preference, is_verified, account_status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['email'] ?? null,
            $data['password'] ?? null,
            $data['mobile_number'] ?? null,
            $data['nid'] ?? null,
            $data['full_name'] ?? null,
            $data['role'],
            $data['location'] ?? null,
            $data['language_preference'] ?? 'bengali',
            $data['is_verified'] ?? false,
            $data['account_status'] ?? 'pending'
        ]);
    }

    public function updateProfile($userId, $data) {
        $stmt = $this->db->prepare("
            UPDATE users
            SET full_name = ?, location = ?, language_preference = ?, profile_picture = ?, updated_at = CURRENT_TIMESTAMP
            WHERE user_id = ?
        ");
        return $stmt->execute([
            $data['full_name'],
            $data['location'],
            $data['language_preference'],
            $data['profile_picture'] ?? null,
            $userId
        ]);
    }

    public function updateRole($userId, $role) {
        $stmt = $this->db->prepare("UPDATE users SET role = ?, updated_at = CURRENT_TIMESTAMP WHERE user_id = ?");
        return $stmt->execute([$role, $userId]);
    }


    public function incrementFailedAttempts($mobile) {
        $stmt = $this->db->prepare("
            UPDATE users
            SET failed_login_attempts = failed_login_attempts + 1,
                locked_until = CASE
                    WHEN failed_login_attempts >= 2 THEN DATE_ADD(NOW(), INTERVAL 15 MINUTE)
                    ELSE NULL
                END,
                account_status = CASE
                    WHEN failed_login_attempts >= 2 THEN 'locked'
                    ELSE account_status
                END,
                updated_at = CURRENT_TIMESTAMP
            WHERE mobile_number = ?
        ");
        return $stmt->execute([$mobile]);
    }


    public function resetFailedAttempts($mobile) {
        $stmt = $this->db->prepare("
            UPDATE users
            SET failed_login_attempts = 0, locked_until = NULL, account_status = 'active', updated_at = CURRENT_TIMESTAMP
            WHERE mobile_number = ?
        ");
        return $stmt->execute([$mobile]);
    }


    public function lockAccount($mobile, $duration = 15) {
        $stmt = $this->db->prepare("
            UPDATE users
            SET account_status = 'locked', locked_until = DATE_ADD(NOW(), INTERVAL ? MINUTE), updated_at = CURRENT_TIMESTAMP
            WHERE mobile_number = ?
        ");
        return $stmt->execute([$duration, $mobile]);
    }


    public function unlockAccount($mobile) {
        $stmt = $this->db->prepare("
            UPDATE users
            SET account_status = 'active', locked_until = NULL, failed_login_attempts = 0, updated_at = CURRENT_TIMESTAMP
            WHERE mobile_number = ?
        ");
        return $stmt->execute([$mobile]);
    }


    public function isAccountLocked($mobile) {
        $stmt = $this->db->prepare("
            SELECT account_status, locked_until
            FROM users
            WHERE mobile_number = ?
        ");
        $stmt->execute([$mobile]);
        $user = $stmt->fetch();

        if (!$user) return false;

        if ($user['account_status'] === 'locked') {
            if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
                return true;
            } else {
                $this->unlockAccount($mobile);
                return false;
            }
        }
        return false;
    }


    public function getUserById($userId) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }


    public function getAllUsers() {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }


    public function getUsersByRole($role) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE role = ? ORDER BY created_at DESC");
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }

 
    public function getPendingUsers() {
        if (!$this->db) return [];

        $stmt = $this->db->prepare("SELECT * FROM users WHERE is_verified = FALSE AND account_status = 'pending' ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function verifyUser($userId) {
        if (!$this->db) return false;

        $stmt = $this->db->prepare("UPDATE users SET is_verified = TRUE, account_status = 'active', updated_at = CURRENT_TIMESTAMP WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }


    public function updateAccountStatus($userId, $status) {
        if (!$this->db) return false;

        $stmt = $this->db->prepare("UPDATE users SET account_status = ?, updated_at = CURRENT_TIMESTAMP WHERE user_id = ?");
        return $stmt->execute([$status, $userId]);
    }
}
?>