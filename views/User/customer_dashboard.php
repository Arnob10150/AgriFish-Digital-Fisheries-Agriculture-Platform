<?php
session_start();
include("../includes/db_connect.php");
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
if($_SESSION['role'] != 'buyer'){ header("Location: login.php"); exit; }
$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace - DFAP</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="/AgriFish-Digital-Fisheries-Agriculture-Platform-main/storage/resources/images/icon/icon.png" alt="DFAP" class="sidebar-icon"> DFAP
            </div>
            <div class="sidebar-subtitle">Buyer Portal</div>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-item active">Marketplace</a>
            <a href="#" class="nav-item">My Orders</a>
            <a href="#" class="nav-item">Wishlist</a>
            <a href="#" class="nav-item">Profile</a>
            <a href="../controllers/logout.php" class="nav-item">Logout</a>
        </nav>
    </div>

    
    <div class="main-content">
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">Marketplace</h1>
                <p class="dashboard-subtitle">Fresh from the source to your kitchen.</p>
            </div>
            <div class="header-actions">
                <div class="search-wrapper">
                    <span class="search-icon">🔍</span>
                    <input type="text" placeholder="Search for fish, prawns..." class="search-input">
                </div>
                <button class="filter-btn">🔧</button>
                <button class="cart-btn">🛒 Cart (0)</button>
            </div>
        </div>

      
        <div class="categories">
            <button class="category-btn active">All</button>
            <button class="category-btn">Sea Fish</button>
            <button class="category-btn">Freshwater</button>
            <button class="category-btn">Shellfish</button>
            <button class="category-btn">Dried Fish</button>
            <button class="category-btn">Frozen</button>
        </div>

      
        <div class="product-grid">
            <div class="product-card">
                <div class="product-image">🐟</div>
                <div class="product-content">
                    <div class="product-header">
                        <div>
                            <h3 class="product-title">Fresh Hilsa (Ilish)</h3>
                            <p class="product-seller">River Delta Fisheries</p>
                        </div>
                        <div class="product-rating">★ 4.8</div>
                    </div>
                    <div class="product-footer">
                        <div>
                            <p class="product-price">$12.50</p>
                            <p class="product-unit">per kg</p>
                        </div>
                        <button class="add-cart-btn">Add to Cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">🦐</div>
                <div class="product-content">
                    <div class="product-header">
                        <div>
                            <h3 class="product-title">Tiger Prawns</h3>
                            <p class="product-seller">Bay Cultivators</p>
                        </div>
                        <div class="product-rating">★ 4.9</div>
                    </div>
                    <div class="product-footer">
                        <div>
                            <p class="product-price">$18.00</p>
                            <p class="product-unit">per kg</p>
                        </div>
                        <button class="add-cart-btn">Add to Cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">🐠</div>
                <div class="product-content">
                    <div class="product-header">
                        <div>
                            <h3 class="product-title">Rui Fish (Rohu)</h3>
                            <p class="product-seller">Green Pond Farms</p>
                        </div>
                        <div class="product-rating">★ 4.5</div>
                    </div>
                    <div class="product-footer">
                        <div>
                            <p class="product-price">$6.20</p>
                            <p class="product-unit">per kg</p>
                        </div>
                        <button class="add-cart-btn">Add to Cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">🦀</div>
                <div class="product-content">
                    <div class="product-header">
                        <div>
                            <h3 class="product-title">Live Crab</h3>
                            <p class="product-seller">Sundarban Catch</p>
                        </div>
                        <div class="product-rating">★ 4.7</div>
                    </div>
                    <div class="product-footer">
                        <div>
                            <p class="product-price">$15.00</p>
                            <p class="product-unit">per kg</p>
                        </div>
                        <button class="add-cart-btn">Add to Cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">🦈</div>
                <div class="product-content">
                    <div class="product-header">
                        <div>
                            <h3 class="product-title">Catfish (Pangash)</h3>
                            <p class="product-seller">Local Pond</p>
                        </div>
                        <div class="product-rating">★ 4.2</div>
                    </div>
                    <div class="product-footer">
                        <div>
                            <p class="product-price">$4.50</p>
                            <p class="product-unit">per kg</p>
                        </div>
                        <button class="add-cart-btn">Add to Cart</button>
                    </div>
                </div>
            </div>

            <div class="product-card">
                <div class="product-image">🐡</div>
                <div class="product-content">
                    <div class="product-header">
                        <div>
                            <h3 class="product-title">Dried Fish (Shutki)</h3>
                            <p class="product-seller">Cox's Bazar Dry</p>
                        </div>
                        <div class="product-rating">★ 4.6</div>
                    </div>
                    <div class="product-footer">
                        <div>
                            <p class="product-price">$22.00</p>
                            <p class="product-unit">per kg</p>
                        </div>
                        <button class="add-cart-btn">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
