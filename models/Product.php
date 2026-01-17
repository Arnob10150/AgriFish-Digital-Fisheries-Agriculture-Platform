<?php

require_once 'database.php';

class Product {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = getDB();
        } catch (Exception $e) {
            $this->pdo = null;
        }
    }


    public function getAllActive() {
        if (!$this->pdo) {
            return $this->getDemoProducts();
        }

        try {
            $stmt = $this->pdo->query("SELECT * FROM products WHERE is_active = 1 AND approval_status = 'approved' ORDER BY created_at DESC");
            $products = $stmt->fetchAll();
            return empty($products) ? $this->getDemoProducts() : $products;
        } catch (PDOException $e) {
            return $this->getDemoProducts();
        }
    }


    public function getById($id) {
        if (!$this->pdo) return null;

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE product_id = ? AND is_active = 1");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }


    public function getByCategory($category) {
        if (!$this->pdo) return [];

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE category = ? AND is_active = 1 ORDER BY created_at DESC");
            $stmt->execute([$category]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

  
    public function create($data) {
        if (!$this->pdo) return false;

        try {
            $stmt = $this->pdo->prepare("INSERT INTO products (name, description, price, category, image, stock_quantity, unit, seller_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['name'],
                $data['description'],
                $data['price'],
                $data['category'],
                $data['image'],
                $data['stock_quantity'],
                $data['unit'],
                $data['seller_id']
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }


    public function update($id, $data) {
        if (!$this->pdo) return false;

        try {
            $stmt = $this->pdo->prepare("UPDATE products SET name=?, description=?, price=?, category=?, image=?, stock_quantity=?, unit=? WHERE product_id=?");
            $stmt->execute([
                $data['name'],
                $data['description'],
                $data['price'],
                $data['category'],
                $data['image'],
                $data['stock_quantity'],
                $data['unit'],
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
            $stmt = $this->pdo->prepare("DELETE FROM products WHERE product_id = ?");
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function approveProduct($id) {
        if (!$this->pdo) return false;

        try {
            $stmt = $this->pdo->prepare("UPDATE products SET approval_status = 'approved' WHERE product_id = ?");
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function rejectProduct($id) {
        if (!$this->pdo) return false;

        try {
            $stmt = $this->pdo->prepare("UPDATE products SET approval_status = 'rejected' WHERE product_id = ?");
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getPendingProducts() {
        if (!$this->pdo) return [];

        try {
            $stmt = $this->pdo->query("SELECT p.*, u.full_name as seller_name FROM products p JOIN users u ON p.seller_id = u.user_id WHERE p.approval_status = 'pending' ORDER BY p.created_at DESC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getAllProductsForAdmin() {
        if (!$this->pdo) return [];

        try {
            $stmt = $this->pdo->query("SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getBySeller($sellerId) {
        if (!$this->pdo) return [];

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE seller_id = ? ORDER BY created_at DESC");
            $stmt->execute([$sellerId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }


    public function search($query) {
        if (!$this->pdo) return [];

        try {
            $searchTerm = "%$query%";
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE (name LIKE ? OR description LIKE ?) AND is_active = 1 ORDER BY created_at DESC");
            $stmt->execute([$searchTerm, $searchTerm]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }


    public function getDemoProducts() {
        return [
            ['product_id' => 1, 'name' => 'Ilish (Hilsa)', 'price' => 2400, 'category' => 'Freshwater', 'image' => '/DFAP/storage/resources/images/fish/Fresh Fish/Rui.JPG', 'unit' => 'kg'],
            ['product_id' => 2, 'name' => 'Rui (River)', 'price' => 750, 'category' => 'Freshwater', 'image' => '/DFAP/storage/resources/images/fish/Fresh Fish/Rui.JPG', 'unit' => 'kg'],
            ['product_id' => 3, 'name' => 'Katla (River)', 'price' => 750, 'category' => 'Freshwater', 'image' => '/DFAP/storage/resources/images/fish/Fresh Fish/Katla.jpg', 'unit' => 'kg'],
            ['product_id' => 4, 'name' => 'Ayre (Giant Catfish)', 'price' => 1500, 'category' => 'Freshwater', 'image' => '/DFAP/storage/resources/images/fish/Fresh Fish/Magur.jpg', 'unit' => 'kg'],
            ['product_id' => 5, 'name' => 'Chitol (Featherback)', 'price' => 1250, 'category' => 'Freshwater', 'image' => '/DFAP/storage/resources/images/fish/Fresh Fish/Mrigel.jpg', 'unit' => 'kg'],
            ['product_id' => 6, 'name' => 'Boal (Wallago)', 'price' => 800, 'category' => 'Freshwater', 'image' => '/DFAP/storage/resources/images/fish/Fresh Fish/Boal.jpg', 'unit' => 'kg'],
            ['product_id' => 7, 'name' => 'Shing (Stinging Catfish)', 'price' => 570, 'category' => 'Freshwater', 'image' => '/DFAP/storage/resources/images/fish/Fresh Fish/Sing.jpg', 'unit' => 'kg'],
            ['product_id' => 8, 'name' => 'Pabda (Pabo Catfish)', 'price' => 450, 'category' => 'Freshwater', 'image' => '/DFAP/storage/resources/images/fish/Fresh Fish/pabda.jpg', 'unit' => 'kg'],
            ['product_id' => 9, 'name' => 'Rupchanda (Pomfret)', 'price' => 1200, 'category' => 'Sea Fish', 'image' => '/DFAP/storage/resources/images/fish/Sea Fish/rupchada.jpg', 'unit' => 'kg'],
            ['product_id' => 10, 'name' => 'Koral (Seabass)', 'price' => 800, 'category' => 'Sea Fish', 'image' => '/DFAP/storage/resources/images/fish/Sea Fish/gura.jpg', 'unit' => 'kg'],
            ['product_id' => 11, 'name' => 'Tuna', 'price' => 500, 'category' => 'Sea Fish', 'image' => '/DFAP/storage/resources/images/fish/Sea Fish/Tuna.jpg', 'unit' => 'kg'],
            ['product_id' => 12, 'name' => 'Loitta (Bombay Duck)', 'price' => 350, 'category' => 'Sea Fish', 'image' => '/DFAP/storage/resources/images/fish/Sea Fish/Squid.jpg', 'unit' => 'kg'],
            ['product_id' => 13, 'name' => 'Surma (King Fish)', 'price' => 600, 'category' => 'Sea Fish', 'image' => '/DFAP/storage/resources/images/fish/Dry Fish/Surma.jpg', 'unit' => 'kg'],
            ['product_id' => 14, 'name' => 'Poa (Yellow Croaker)', 'price' => 550, 'category' => 'Sea Fish', 'image' => '/DFAP/storage/resources/images/fish/Sea Fish/Sting rey.jpg', 'unit' => 'kg'],
            ['product_id' => 15, 'name' => 'Golda Chingri (Prawn)', 'price' => 1350, 'category' => 'Shellfish', 'image' => '/DFAP/storage/resources/images/fish/Sea Fish/golda.jpg', 'unit' => 'kg'],
            ['product_id' => 16, 'name' => 'Bagda/Tiger Shrimp', 'price' => 1000, 'category' => 'Shellfish', 'image' => '/DFAP/storage/resources/images/fish/Sea Fish/Bagda.jpg', 'unit' => 'kg'],
            ['product_id' => 17, 'name' => 'Lobster', 'price' => 2000, 'category' => 'Shellfish', 'image' => '/DFAP/storage/resources/images/fish/Shell Fish/Lobster.jpg', 'unit' => 'kg'],
            ['product_id' => 18, 'name' => 'Crab (Mud/Blue)', 'price' => 700, 'category' => 'Shellfish', 'image' => '/DFAP/storage/resources/images/fish/Sea Fish/Crab.jpg', 'unit' => 'kg'],
            ['product_id' => 19, 'name' => 'Churi Shutki (Dried)', 'price' => 1200, 'category' => 'Dried Fish', 'image' => '/DFAP/storage/resources/images/fish/Dry Fish/Shidol.jpg', 'unit' => 'kg'],
            ['product_id' => 20, 'name' => 'Basa/Dory Fillet', 'price' => 580, 'category' => 'Frozen', 'image' => '/DFAP/storage/resources/images/fish/Frozen Fish/Mrigel.jpg', 'unit' => 'kg']
        ];
    }
}
?>