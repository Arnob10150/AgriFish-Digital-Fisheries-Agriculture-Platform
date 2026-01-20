<?php

require_once 'database.php';

class Notice {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = getDB();
        } catch (Exception $e) {
            $this->pdo = null;
        }
    }

    public function getAll($category = null) {
        if (!$this->pdo) return [];

        try {
            $sql = "SELECT * FROM notices";
            $params = [];

            if ($category && $category !== 'all') {
                $sql .= " WHERE category = ? OR category = 'all'";
                $params[] = $category;
            }

            $sql .= " ORDER BY created_at DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $notices = $stmt->fetchAll();

            // Add creator_name as "Admin" since only admins can create
            foreach ($notices as &$notice) {
                $notice['creator_name'] = 'Admin';
            }
            return $notices;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getById($id) {
        if (!$this->pdo) return null;

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM notices WHERE notice_id = ?");
            $stmt->execute([$id]);
            $notice = $stmt->fetch();
            if ($notice) {
                $notice['creator_name'] = 'Admin';
            }
            return $notice;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function create($data) {
        if (!$this->pdo) return false;

        try {
            $stmt = $this->pdo->prepare("INSERT INTO notices (title, content, category, created_by) VALUES (?, ?, ?, ?)");
            return $stmt->execute([
                $data['title'],
                $data['content'],
                $data['category'] ?? 'all',
                $data['created_by']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update($id, $data) {
        if (!$this->pdo) return false;

        try {
            $stmt = $this->pdo->prepare("UPDATE notices SET title = ?, content = ?, updated_at = CURRENT_TIMESTAMP WHERE notice_id = ?");
            return $stmt->execute([
                $data['title'],
                $data['content'],
                $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($id) {
        if (!$this->pdo) return false;

        try {
            $stmt = $this->pdo->prepare("DELETE FROM notices WHERE notice_id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>