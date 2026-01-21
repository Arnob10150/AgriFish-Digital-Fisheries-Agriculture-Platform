<?php

require_once 'database.php';

class FishCatch {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = getDB();
        } catch (Exception $e) {
            $this->pdo = null;
        }
    }

    public function getAllByUser($user_id) {
        if (!$this->pdo) return [];

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM catches WHERE user_id = ? ORDER BY catch_date DESC");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getById($id) {
        if (!$this->pdo) return null;

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM catches WHERE catch_id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    public function create($data) {
        if (!$this->pdo) return false;

        try {
            $stmt = $this->pdo->prepare("INSERT INTO catches (user_id, catch_date, fish_type, weight_kg, price_per_kg) VALUES (?, ?, ?, ?, ?)");
            return $stmt->execute([
                $data['user_id'],
                $data['catch_date'],
                $data['fish_type'],
                $data['weight_kg'],
                $data['price_per_kg']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update($id, $data) {
        if (!$this->pdo) return false;

        try {
            $stmt = $this->pdo->prepare("UPDATE catches SET catch_date = ?, fish_type = ?, weight_kg = ?, price_per_kg = ? WHERE catch_id = ?");
            $stmt->execute([
                $data['catch_date'],
                $data['fish_type'],
                $data['weight_kg'],
                $data['price_per_kg'],
                $id
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($id) {
        if (!$this->pdo) return false;

        try {
            $stmt = $this->pdo->prepare("DELETE FROM catches WHERE catch_id = ?");
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>