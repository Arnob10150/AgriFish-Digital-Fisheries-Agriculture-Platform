<?php
    session_start();
    require_once __DIR__ . '/../../config.php';
    if(!isset($_SESSION["user_id"]) || $_SESSION["role"] != "customer")
    {
        header("Location:../home.php");
        exit;
    }

  
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (!isset($_SESSION['wishlist'])) $_SESSION['wishlist'] = [];

   
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

   
    try {
        require_once '../../models/Product.php';
        $productModel = new Product();
        $products = $productModel->getAllActive();

       
        if (empty($products)) {
            $products = $productModel->getDemoProducts();
        }
    } catch (Exception $e) {
       
        $products = [];
        $error_message = "Unable to load products. Please try again later.";
    }

  
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
    <link rel="stylesheet" href="Css/customer.css">
</head>
<body>
    
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="<?php echo IMAGE_BASE_PATH; ?>icon/icon.png" alt="DFAP" class="sidebar-icon"> DFAP
            </div>
            <div class="sidebar-subtitle">Buyer Portal</div>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-item active">🏠 Marketplace</a>
            <a href="cart.php" class="nav-item">🛒 My Cart (<?php echo count($_SESSION['cart'] ?? []); ?>)</a>
            <a href="orders.php" class="nav-item">📦 My Orders</a>
            <a href="notice.php" class="nav-item">📢 Notices</a>
            <a href="wishlist.php" class="nav-item">❤️ Wishlist</a>
            <a href="../profile.php" class="nav-item">👤 Profile</a>
            <a href="../../?logout=1" class="nav-item">🚪 Logout</a>
        </nav>
    </div>


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

    
        <div class="categories">
            <button class="category-btn active">All</button>
            <button class="category-btn">Sea Fish</button>
            <button class="category-btn">Freshwater</button>
            <button class="category-btn">Shellfish</button>
            <button class="category-btn">Dried Fish</button>
            <button class="category-btn">Frozen</button>
        </div>

     
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <?php $image = $product['image'] ?? '🐟'; ?>
                    <?php if (filter_var($image, FILTER_VALIDATE_URL)): ?>
                        <img src="<?php echo htmlspecialchars($image); ?>"
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div style="display: none; font-size: 3rem; text-align: center; line-height: 1;"><?php echo htmlspecialchars($image); ?></div>
                    <?php else: ?>
                        <img src="<?php echo htmlspecialchars(IMAGE_BASE_PATH . $image); ?>"
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div style="display: none; font-size: 3rem; text-align: center; line-height: 1;"><?php echo htmlspecialchars($image); ?></div>
                    <?php endif; ?>
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
    
        const categoryBtns = document.querySelectorAll('.category-btn');
        const productCards = document.querySelectorAll('.product-card');
        const searchInput = document.querySelector('.search-input');
        const addToCartBtns = document.querySelectorAll('.add-cart-btn');

      
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

</body>
</html>
