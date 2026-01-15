<?php
session_start();
if(!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location:../home.php");
    exit;
}


$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../../models/Product.php';
    $productModel = new Product();

    if (isset($_POST['add_product'])) {
        $data = [
            'name' => trim($_POST['name']),
            'description' => trim($_POST['description']),
            'price' => floatval($_POST['price']),
            'category' => $_POST['category'],
            'image' => trim($_POST['image']) ?: '🐟',
            'stock_quantity' => intval($_POST['stock_quantity']),
            'unit' => trim($_POST['unit']) ?: 'kg',
            'seller_id' => $_SESSION['user_id']
        ];

        if ($productModel->create($data)) {
            $message = '<div class="alert alert-success">Product added successfully!</div>';
        } else {
            $message = '<div class="alert alert-error">Failed to add product. Database connection may be unavailable.</div>';
        }
    } elseif (isset($_POST['update_product'])) {
        $id = intval($_POST['product_id']);
        $data = [
            'name' => trim($_POST['name']),
            'description' => trim($_POST['description']),
            'price' => floatval($_POST['price']),
            'category' => $_POST['category'],
            'image' => trim($_POST['image']) ?: '🐟',
            'stock_quantity' => intval($_POST['stock_quantity']),
            'unit' => trim($_POST['unit']) ?: 'kg'
        ];

        if ($productModel->update($id, $data)) {
            $message = '<div class="alert alert-success">Product updated successfully!</div>';
        } else {
            $message = '<div class="alert alert-error">Failed to update product. Database connection may be unavailable.</div>';
        }
    } elseif (isset($_POST['delete_product'])) {
        $id = intval($_POST['product_id']);
        if ($productModel->delete($id)) {
            $message = '<div class="alert alert-success">Product deleted successfully!</div>';
        } else {
            $message = '<div class="alert alert-error">Failed to delete product. Database connection may be unavailable.</div>';
        }
    }
}


try {
    require_once '../../models/Product.php';
    $productModel = new Product();
    $products = $productModel->getAllActive();
} catch (Exception $e) {
    $products = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - DFAP</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">🐟 DFAP</div>
            <div class="sidebar-subtitle">Admin Console</div>
        </div>
        <nav class="sidebar-nav">
            <a href="admin.php" class="nav-item">🏠 Dashboard</a>
            <a href="products.php" class="nav-item active">📦 Products</a>
            <a href="#" class="nav-item">👥 User Management</a>
            <a href="#" class="nav-item">✅ Verification</a>
            <a href="#" class="nav-item">📊 Reports</a>
            <a href="#" class="nav-item">⚙️ System</a>
            <a href="../profile.php" class="nav-item">👤 Profile</a>
            <a href="../?logout=1" class="nav-item">🚪 Logout</a>
        </nav>
    </div>


    <div class="main-content">
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">Product Management</h1>
                <p class="dashboard-subtitle">Add, edit, and manage marketplace products</p>
            </div>
            <button class="btn-primary" onclick="showAddForm()">+ Add Product</button>
        </div>

        <?php echo $message; ?>

->
        <div class="data-table">
            <div class="table-header">
                <h2 class="table-title">All Products (<?php echo count($products); ?>)</h2>
            </div>
            <div class="table-content">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['product_id']; ?></td>
                            <td>
                                <img src="<?php echo htmlspecialchars($product['image']); ?>"
                                     alt="Product"
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                                <span style="display: none;"><?php echo $product['image'] ?? '🐟'; ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                            <td>৳<?php echo number_format($product['price'], 0); ?></td>
                            <td><?php echo $product['stock_quantity'] ?? 0; ?> <?php echo htmlspecialchars($product['unit'] ?? 'kg'); ?></td>
                            <td><span class="status-badge">Active</span></td>
                            <td class="text-right">
                                <button class="btn-outline" onclick="editProduct(<?php echo $product['product_id']; ?>)">Edit</button>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                    <button type="submit" name="delete_product" class="btn-outline" onclick="return confirm('Delete this product?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div id="productModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Add Product</h3>
                <button class="modal-close" onclick="closeModal()">×</button>
            </div>
            <div class="modal-body">
                <form method="post" id="productForm">
                    <input type="hidden" name="product_id" id="product_id">
                    <div class="form-group">
                        <label for="name" class="form-label">Product Name *</label>
                        <input type="text" id="name" name="name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-input" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price" class="form-label">Price (৳) *</label>
                        <input type="number" id="price" name="price" class="form-input" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="category" class="form-label">Category *</label>
                        <select id="category" name="category" class="form-input" required>
                            <option value="Freshwater">Freshwater</option>
                            <option value="Sea Fish">Sea Fish</option>
                            <option value="Shellfish">Shellfish</option>
                            <option value="Dried Fish">Dried Fish</option>
                            <option value="Frozen">Frozen</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image" class="form-label">Image (Emoji or URL)</label>
                        <input type="text" id="image" name="image" class="form-input" placeholder="🐟">
                    </div>
                    <div class="form-group">
                        <label for="stock_quantity" class="form-label">Stock Quantity</label>
                        <input type="number" id="stock_quantity" name="stock_quantity" class="form-input" value="0">
                    </div>
                    <div class="form-group">
                        <label for="unit" class="form-label">Unit</label>
                        <input type="text" id="unit" name="unit" class="form-input" value="kg">
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="closeModal()">Cancel</button>
                        <button type="submit" name="add_product" id="submitBtn" class="btn-primary">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showAddForm() {
            document.getElementById('modalTitle').textContent = 'Add Product';
            document.getElementById('productForm').reset();
            document.getElementById('product_id').value = '';
            document.getElementById('submitBtn').name = 'add_product';
            document.getElementById('submitBtn').textContent = 'Add Product';
            document.getElementById('productModal').style.display = 'block';
        }

        function editProduct(id) {

            document.getElementById('modalTitle').textContent = 'Edit Product';
            document.getElementById('submitBtn').name = 'update_product';
            document.getElementById('submitBtn').textContent = 'Update Product';
            document.getElementById('productModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('productModal').style.display = 'none';
        }


        window.onclick = function(event) {
            const modal = document.getElementById('productModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>

    <style>
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 0.75rem;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #64748b;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }

        textarea.form-input {
            resize: vertical;
            min-height: 80px;
        }
    </style>
</body>
</html>