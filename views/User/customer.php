<?php
    session_start();
    if(!isset($_SESSION["user_id"]) || $_SESSION["role"] != "customer")
    {
        header("Location:../home.php");
        exit;
    }

    // Initialize cart and wishlist
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (!isset($_SESSION['wishlist'])) $_SESSION['wishlist'] = [];

    // Handle add to cart/wishlist
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $action = $_POST['action'] ?? '';
        $product = $_POST['product'] ?? '';

        if ($action == 'add_cart' && $product) {
            if (!in_array($product, $_SESSION['cart'])) {
                $_SESSION['cart'][] = $product;
            }
        } elseif ($action == 'add_wishlist' && $product) {
            if (!in_array($product, $_SESSION['wishlist'])) {
                $_SESSION['wishlist'][] = $product;
            }
        }
    }

    // Load products from database or use demo data
    try {
        require_once '../../models/Product.php';
        $productModel = new Product();
        $products = $productModel->getAllActive();

        // If no products in database, use demo data
        if (empty($products)) {
            $products = $productModel->getDemoProducts();
        }
    } catch (Exception $e) {
        // Show error if database connection fails
        $products = [];
        $error_message = "Unable to load products. Please try again later.";
    }

    // Load notices
    try {
        require_once __DIR__ . '/../../controllers/NoticeController.php';
        $noticeController = new NoticeController();
        $notices = $noticeController->getAll();
    } catch (Exception $e) {
        $notices = [];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace - DFAP</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="Css/dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">🐟 DFAP</div>
            <div class="sidebar-subtitle">Buyer Portal</div>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-item active">🏠 Marketplace</a>
            <a href="cart.php" class="nav-item">🛒 My Cart (<?php echo count($_SESSION['cart'] ?? []); ?>)</a>
            <a href="orders.php" class="nav-item">📦 My Orders</a>
            <a href="#" class="nav-item">💬 Messages</a>
            <a href="wishlist.php" class="nav-item">❤️ Wishlist</a>
            <a href="../profile.php" class="nav-item">👤 Profile</a>
            <a href="../../?logout=1" class="nav-item">🚪 Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">Marketplace</h1>
                <p class="dashboard-subtitle">Fresh from the source to your kitchen</p>
            </div>
            <div class="header-actions">
                <div class="search-wrapper">
                    <span class="search-icon">🔍</span>
                    <input type="text" placeholder="Search for fish, prawns..." class="search-input">
                </div>
                <button class="filter-btn">🔧</button>
                <button class="wishlist-btn">❤️ Wishlist (<?php echo count($_SESSION['wishlist']); ?>)</button>
                <a href="cart.php" class="cart-btn">🛒 Cart (<?php echo count($_SESSION['cart']); ?>)</a>
            </div>
        </div>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-error">
                <span class="alert-icon">⚠️</span>
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Notices -->
        <?php if (!empty($notices)): ?>
        <div class="notices-section">
            <h2 class="section-title">📢 Important Notices</h2>
            <div class="notices-container">
                <?php foreach ($notices as $notice): ?>
                <div class="notice-card">
                    <div class="notice-header">
                        <h3 class="notice-title"><?php echo htmlspecialchars($notice['title']); ?></h3>
                        <span class="notice-date"><?php echo date('M j, Y', strtotime($notice['created_at'])); ?></span>
                    </div>
                    <div class="notice-content">
                        <?php echo nl2br(htmlspecialchars($notice['content'])); ?>
                    </div>
                    <div class="notice-footer">
                        <span class="notice-author">By: <?php echo htmlspecialchars($notice['creator_name']); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Categories -->
        <div class="categories">
            <button class="category-btn active">All</button>
            <button class="category-btn">Sea Fish</button>
            <button class="category-btn">Freshwater</button>
            <button class="category-btn">Shellfish</button>
            <button class="category-btn">Dried Fish</button>
            <button class="category-btn">Frozen</button>
        </div>

        <!-- Product Grid -->
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>"
                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div style="display: none; font-size: 3rem; text-align: center; line-height: 1;"><?php echo $product['image'] ?? '🐟'; ?></div>
                </div>
                <div class="product-content">
                    <div class="product-header">
                        <div>
                            <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-seller"><?php echo htmlspecialchars($product['category']); ?> • DFAP</p>
                        </div>
                        <div class="product-rating">★ 4.8</div>
                    </div>
                    <div class="product-footer">
                        <div>
                            <p class="product-price">৳<?php echo number_format($product['price'], 0); ?></p>
                            <p class="product-unit">per <?php echo htmlspecialchars($product['unit'] ?? 'kg'); ?></p>
                        </div>
                        <div class="product-actions">
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="action" value="add_wishlist">
                                <input type="hidden" name="product" value="<?php echo htmlspecialchars($product['name']); ?>">
                                <button type="submit" class="wishlist-btn-small">❤️</button>
                            </form>
                            <?php if (($product['stock_quantity'] ?? 0) > 0): ?>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="action" value="add_cart">
                                <input type="hidden" name="product" value="<?php echo htmlspecialchars($product['name']); ?>">
                                <button type="submit" class="add-cart-btn">Add to Cart</button>
                            </form>
                            <?php else: ?>
                            <button class="out-of-stock-btn" disabled>Out of Stock</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Get DOM elements
        const categoryBtns = document.querySelectorAll('.category-btn');
        const productCards = document.querySelectorAll('.product-card');
        const searchInput = document.querySelector('.search-input');
        const addToCartBtns = document.querySelectorAll('.add-cart-btn');

        // Category filtering
        categoryBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                categoryBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const selectedCategory = this.textContent.trim();
                filterProducts(selectedCategory, searchInput.value.toLowerCase().trim());
            });
        });

        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const activeCategory = document.querySelector('.category-btn.active').textContent.trim();
            filterProducts(activeCategory, searchTerm);
        });

        // Combined filter function
        function filterProducts(category, searchTerm) {
            productCards.forEach(card => {
                const productName = card.querySelector('.product-title').textContent.toLowerCase();
                const productCategory = card.querySelector('.product-seller').textContent.split(' • ')[0];

                const categoryMatch = category === 'All' || productCategory === category;
                const searchMatch = searchTerm === '' ||
                    productName.includes(searchTerm) ||
                    productCategory.toLowerCase().includes(searchTerm);

                if (categoryMatch && searchMatch) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Add to cart functionality
        addToCartBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                this.textContent = 'Added!';
                this.style.background = '#10b981';
                setTimeout(() => {
                    this.textContent = 'Add to Cart';
                    this.style.background = '';
                }, 2000);
            });
        });
    </script>

    <style>
        .out-of-stock-btn {
            background: #dc2626;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .notices-section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: white;
            margin-bottom: 1rem;
        }

        .notices-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .notice-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            padding: 1.5rem;
            transition: all 0.2s ease;
        }

        .notice-card:hover {
            border-color: #475569;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .notice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .notice-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: white;
            margin: 0;
        }

        .notice-date {
            font-size: 0.875rem;
            color: #64748b;
        }

        .notice-content {
            color: #e2e8f0;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .notice-footer {
            border-top: 1px solid #334155;
            padding-top: 0.75rem;
        }

        .notice-author {
            font-size: 0.875rem;
            color: #64748b;
        }
    </style>
</body>
</html>