<?php

class Product {
    private $conn;

    public function __construct() {
        try {
            include_once 'db_connect.php';
            global $conn;
            if ($conn && !$conn->connect_error) {
                $this->conn = $conn;
            } else {
                $this->conn = null;
            }
        } catch (Exception $e) {
            $this->conn = null;
        }
    }


    public function getAllActive() {
        if (!$this->conn) {
            return $this->getDemoProducts();
        }

        $sql = "SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC";
        $result = $this->conn->query($sql);

        $products = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }

        return empty($products) ? $this->getDemoProducts() : $products;
    }


    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE product_id = ? AND is_active = 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }


    public function getByCategory($category) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE category = ? AND is_active = 1 ORDER BY created_at DESC");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

  
    public function create($data) {
        if (!$this->conn) return false;

        $stmt = $this->conn->prepare("INSERT INTO products (name, description, price, category, image, stock_quantity, unit, seller_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdssiss",
            $data['name'],
            $data['description'],
            $data['price'],
            $data['category'],
            $data['image'],
            $data['stock_quantity'],
            $data['unit'],
            $data['seller_id']
        );
        return $stmt->execute();
    }


    public function update($id, $data) {
        if (!$this->conn) return false;

        $stmt = $this->conn->prepare("UPDATE products SET name=?, description=?, price=?, category=?, image=?, stock_quantity=?, unit=? WHERE product_id=?");
        $stmt->bind_param("ssdssisi",
            $data['name'],
            $data['description'],
            $data['price'],
            $data['category'],
            $data['image'],
            $data['stock_quantity'],
            $data['unit'],
            $id
        );
        return $stmt->execute();
    }

    public function delete($id) {
        if (!$this->conn) return false;

        $stmt = $this->conn->prepare("UPDATE products SET is_active = 0 WHERE product_id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }


    public function search($query) {
        $searchTerm = "%$query%";
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE (name LIKE ? OR description LIKE ?) AND is_active = 1 ORDER BY created_at DESC");
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
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