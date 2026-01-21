<?php
require_once 'models/database.php';
try {
    $pdo = getDB();
    echo "Database connected successfully.<br>";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM notices");
    $result = $stmt->fetch();
    echo "Notices table has " . $result['count'] . " records.";
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>
