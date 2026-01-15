<?php
    session_start();
    if(!isset($_SESSION["user_id"]) || $_SESSION["role"] != "customer")
    {
        header("Location:../login.php");
        exit;
    }

    $cart = $_SESSION['cart'] ?? [];
    $products = [
        'Ilish (Hilsa)' => 2400,
        'Rui (River)' => 750,
        'Katla (River)' => 750,
        'Ayre (Giant Catfish)' => 1500,
        'Chitol (Featherback)' => 1250,
        'Boal (Wallago)' => 800,
        'Shing (Stinging Catfish)' => 570,
        'Pabda (Pabo Catfish)' => 450,
        'Rupchanda (Pomfret)' => 1200,
        'Koral (Seabass)' => 800,
        'Tuna' => 500,
        'Loitta (Bombay Duck)' => 350,
        'Surma (King Fish)' => 600,
        'Poa (Yellow Croaker)' => 550,
        'Golda Chingri (Prawn)' => 1350,
        'Bagda/Tiger Shrimp' => 1000,
        'Lobster' => 2000,
        'Crab (Mud/Blue)' => 700,
        'Churi Shutki (Dried)' => 1200,
        'Basa/Dory Fillet' => 580
    ];

    $total = 0;
    foreach ($cart as $item) {
        if (isset($products[$item])) {
            $total += $products[$item];
        }
    }

    if (isset($_POST['checkout'])) {
        // Generate bill
        $_SESSION['bill'] = [
            'items' => $cart,
            'total' => $total,
            'date' => date('Y-m-d H:i:s')
        ];
        $_SESSION['cart'] = []; // Clear cart
        header("Location: bill.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - DFAP</title>
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="Css/dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">🐟 DFAP</div>
            <div class="sidebar-subtitle">Customer Portal</div>
        </div>
        <nav class="sidebar-nav">
            <a href="customer.php" class="nav-item">🏠 Marketplace</a>
            <a href="cart.php" class="nav-item active">🛒 My Cart (<?php echo count($cart); ?>)</a>
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
                <h1 class="dashboard-title">Shopping Cart</h1>
                <p class="dashboard-subtitle">Review your selected items</p>
            </div>
            <div class="header-actions">
                <a href="customer.php" class="btn-secondary">← Continue Shopping</a>
            </div>
        </div>

        <?php if (empty($cart)): ?>
            <div class="empty-cart">
                <h2>Your cart is empty</h2>
                <p>Add some delicious fish to your cart!</p>
                <a href="customer.php" class="btn-primary">Browse Products</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach ($cart as $item): ?>
                    <div class="cart-item">
                        <div class="item-info">
                            <h3><?php echo $item; ?></h3>
                            <p>৳<?php echo $products[$item] ?? 0; ?> per kg</p>
                        </div>
                        <div class="item-price">৳<?php echo $products[$item] ?? 0; ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <div class="summary-row">
                    <span>Total Items:</span>
                    <span><?php echo count($cart); ?></span>
                </div>
                <div class="summary-row total">
                    <span>Total Amount:</span>
                    <span>৳<?php echo $total; ?></span>
                </div>
            </div>

            <form method="post" style="text-align: center; margin-top: 2rem;">
                <button type="submit" name="checkout" class="btn-primary" style="padding: 1rem 2rem; font-size: 1.1rem;">
                    Proceed to Checkout 🛒
                </button>
            </form>
        <?php endif; ?>
    </div>

    <style>
        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .empty-cart h2 {
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .empty-cart p {
            color: #64748b;
            margin-bottom: 2rem;
        }

        .cart-items {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-info h3 {
            font-size: 1.125rem;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .item-info p {
            color: #64748b;
        }

        .item-price {
            font-size: 1.125rem;
            font-weight: bold;
            color: #3b82f6;
        }

        .cart-summary {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-row.total {
            font-size: 1.25rem;
            font-weight: bold;
            color: #1e293b;
            border-top: 2px solid #e2e8f0;
            margin-top: 1rem;
            padding-top: 1rem;
        }

        .btn-secondary {
            padding: 0.75rem 1.5rem;
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }
    </style>
</body>
</html>